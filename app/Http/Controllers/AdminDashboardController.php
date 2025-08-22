<?php

namespace App\Http\Controllers;

use App\Models\Name;
use App\Models\Submit;
use App\Notifications\SendMusicPathNotification;
use App\RequestStatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
	public function index(Request $request): View
	{
		$mostUsedName = Name::orderByDesc('use_count')->first();
		$pendingCount = Submit::where('request_status', RequestStatusEnum::REQUESTED->value)->count();
		$pendingSubmits = Submit::select(['id','mobile','name'])
			->where('request_status', RequestStatusEnum::REQUESTED->value)
			->latest('id')
			->limit(50)
			->get();

		$sort = $request->query('sort', 'use_count_desc');
		$namesQuery = Name::query();
		switch ($sort) {
			case 'name_asc':
				$namesQuery->orderBy('name');
				break;
			case 'name_desc':
				$namesQuery->orderByDesc('name');
				break;
			case 'latest':
				$namesQuery->latest('id');
				break;
			case 'use_count_desc':
			default:
				$namesQuery->orderByDesc('use_count');
		}
		/** @var LengthAwarePaginator $names */
		$names = $namesQuery->paginate(12)->withQueryString();

		return view('admin.dashboard', compact('mostUsedName','pendingCount','pendingSubmits','names','sort'));
	}

	public function fetchPendingByName(Request $request): JsonResponse
	{
		$name = trim((string) $request->query('name', ''));
		if ($name === '') {
			return response()->json(['items' => []]);
		}
		$items = Submit::select(['id','mobile','name'])
			->where('request_status', RequestStatusEnum::REQUESTED->value)
			->where('name', $name)
			->latest('id')
			->limit(100)
			->get();
		return response()->json(['items' => $items]);
	}

	public function storeNameAndProcess(Request $request): RedirectResponse
	{
		$validator = Validator::make($request->all(), [
			'name' => ['required','string','max:255'],
			'music' => ['required','file','mimes:mp3,wav,ogg','max:10240'],
			'submits' => ['array'],
			'submits.*' => ['integer','exists:submits,id'],
		]);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		try {
			DB::beginTransaction();

			$musicFile = $request->file('music');
			$originalName = pathinfo($musicFile->getClientOriginalName(), PATHINFO_FILENAME);
			$ext = $musicFile->getClientOriginalExtension();
			$dir = public_path('music');
			File::ensureDirectoryExists($dir);
			$filename = uniqid('music_').'_'.str_replace(' ','_', $originalName).'.'.$ext;
			$musicFile->move($dir, $filename);
			$publicPath = '/music/'.$filename;

			$nameText = trim($request->input('name'));
			$nameModel = Name::updateOrCreate(
				['name' => $nameText],
				['path' => $publicPath]
			);

			$selectedIds = collect($request->input('submits', []))->unique()->values();
			if ($selectedIds->isNotEmpty()) {
				$submits = Submit::whereIn('id', $selectedIds)
					->where('request_status', RequestStatusEnum::REQUESTED->value)
					->get();

				foreach ($submits as $submit) {
					$submit->notify(new SendMusicPathNotification($submit->mobile, url($publicPath), $nameText));
					$submit->request_status = RequestStatusEnum::DONE->value;
					$submit->save();
				}

				// Increment use_count by number processed
				$nameModel->increment('use_count', $submits->count());
			}

			DB::commit();

			return redirect()->route('admin.dashboard')->with('status', 'نام و فایل با موفقیت ثبت شد و پیامک‌ها ارسال شدند.');
		} catch (\Throwable $e) {
			DB::rollBack();
			Log::error('Admin storeNameAndProcess error: '.$e->getMessage());
			return back()->withErrors(['error' => 'خطایی رخ داد. لطفا دوباره تلاش کنید.'])->withInput();
		}
	}
}