<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
	public function index(Request $request): View
	{
		$query = Transaction::query()->latest('id');

		if ($status = $request->query('status')) {
			$query->where('status', $status);
		}
		if ($mobile = $request->query('mobile')) {
			$query->where('mobile', 'like', "%$mobile%");
		}

		$transactions = $query->paginate(20)->withQueryString();
		return view('admin.transactions', compact('transactions'));
	}
}