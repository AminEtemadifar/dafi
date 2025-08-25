<?php

namespace App\Http\Controllers;

use App\Models\Submit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubmitController extends Controller
{
	public function index(Request $request): View
	{
		$query = Submit::query();

		if ($mobile = $request->query('mobile')) {
			$query->where('mobile', 'like', "%$mobile%");
		}
		if ($name = $request->query('name')) {
			$query->where('name', 'like', "%$name%");
		}
		if ($status = $request->query('status')) {
			$query->where('request_status', $status);
		}

		$sort = $request->query('sort', 'created_desc');
		switch ($sort) {
			case 'created_asc':
				$query->orderBy('id', 'asc');
				break;
			case 'name_asc':
				$query->orderBy('name');
				break;
			case 'name_desc':
				$query->orderByDesc('name');
				break;
			case 'created_desc':
			default:
				$query->orderBy('id', 'desc');
		}

		/** @var LengthAwarePaginator $submits */
		$submits = $query->paginate(20)->withQueryString();
		return view('admin.submits', compact('submits','sort'));
	}
}