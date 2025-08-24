<?php

namespace App\Http\Controllers;

use App\Models\Name;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminNameController extends Controller
{
	public function index(Request $request): View
	{
		$sort = $request->query('sort', 'use_count_desc');
		$q = trim((string) $request->query('q', ''));

		$query = Name::query();
		if ($q !== '') {
			$query->where('name', 'like', "%$q%");
		}
		switch ($sort) {
			case 'name_asc': $query->orderBy('name'); break;
			case 'name_desc': $query->orderByDesc('name'); break;
			case 'latest': $query->latest('id'); break;
			default: $query->orderByDesc('use_count');
		}
		$names = $query->paginate(20)->withQueryString();
		return view('admin.names.index', compact('names','sort','q'));
	}

	public function create(): View
	{
		return view('admin.names.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$validator = Validator::make($request->all(), [
			'name' => ['required','string','max:255'],
			'path' => ['required','string','max:2048'],
		]);
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}
		Name::updateOrCreate(['name' => trim($request->input('name'))], ['path' => trim($request->input('path'))]);
		return redirect()->route('admin.names.index')->with('status', 'نام با موفقیت ثبت شد.');
	}
}