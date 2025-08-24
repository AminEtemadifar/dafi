<?php

namespace App\Http\Controllers;

use App\Models\Submit;
use App\RequestStatusEnum;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubmitController extends Controller
{
	public function index(Request $request): View
	{
		$sort = $request->query('sort', 'latest');
		$status = $request->query('status');
		$mobile = $request->query('mobile');
		$name = $request->query('name');

		$query = Submit::query();
		if ($status) { $query->where('request_status', $status); }
		if ($mobile) { $query->where('mobile', 'like', "%$mobile%"); }
		if ($name) { $query->where('name', 'like', "%$name%"); }

		switch ($sort) {
			case 'oldest': $query->oldest('id'); break;
			default: $query->latest('id');
		}

		$submits = $query->paginate(20)->withQueryString();

		return view('admin.submits.index', [
			'submits' => $submits,
			'status' => $status,
			'mobile' => $mobile,
			'name' => $name,
			'sort' => $sort,
			'requestStatuses' => [
				RequestStatusEnum::PREPARE->value => 'آماده‌سازی',
				RequestStatusEnum::REQUESTED->value => 'در انتظار',
				RequestStatusEnum::DONE->value => 'انجام‌شده',
			],
		]);
	}
}