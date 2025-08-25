<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
	public function index(Request $request): View
	{
		$query = Transaction::query();

		if ($status = $request->query('status')) {
			$query->where('status', $status);
		}
		if ($mobile = $request->query('mobile')) {
			$query->where('mobile', 'like', "%$mobile%");
		}

		$sort = $request->query('sort', 'created_desc');
		switch ($sort) {
			case 'created_asc':
				$query->orderBy('id', 'asc');
				break;
			case 'amount_desc':
				$query->orderBy('amount', 'desc');
				break;
			case 'amount_asc':
				$query->orderBy('amount', 'asc');
				break;
			case 'status':
				$query->orderBy('status');
				break;
			case 'created_desc':
			default:
				$query->orderBy('id', 'desc');
		}

		$transactions = $query->paginate(20)->withQueryString();
		return view('admin.transactions', compact('transactions','sort'));
	}
}