<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by gateway
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }
        
        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 10);
        $transactions = $query->paginate($perPage);
        
        // Get statistics
        $totalTransactions = Transaction::count();
        $successfulTransactions = Transaction::where('status', 'success')->count();
        $failedTransactions = Transaction::where('status', 'failed')->count();
        $totalAmount = Transaction::where('status', 'success')->sum('amount');
        
        return view('admin.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'successfulTransactions',
            'failedTransactions',
            'totalAmount'
        ));
    }
    
    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }
    
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:success,failed,pending'
        ]);
        
        $transaction->update([
            'status' => $request->status
        ]);
        
        return redirect()->route('admin.transactions.index')
            ->with('success', 'وضعیت پرداخت با موفقیت تغییر یافت.');
    }
}
