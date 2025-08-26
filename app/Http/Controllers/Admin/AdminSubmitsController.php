<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submit;
use App\RequestStatusEnum;
use Illuminate\Http\Request;

class AdminSubmitsController extends Controller
{
    public function index(Request $request)
    {
        $query = Submit::query();
        
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
            $query->where('request_status', $request->status);
        }
        
        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'mobile_asc':
                $query->orderBy('mobile', 'asc');
                break;
            case 'mobile_desc':
                $query->orderBy('mobile', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $perPage = $request->get('per_page', 10);
        $submits = $query->paginate($perPage);
        
        // Get statistics
        $totalSubmits = Submit::count();
        $requestedSubmits = Submit::where('request_status', RequestStatusEnum::REQUESTED)->count();
        $doneSubmits = Submit::where('request_status', RequestStatusEnum::DONE)->count();
        $prepareSubmits = Submit::where('request_status', RequestStatusEnum::PREPARE)->count();
        
        return view('admin.submits.index', compact(
            'submits',
            'totalSubmits',
            'requestedSubmits',
            'doneSubmits',
            'prepareSubmits'
        ));
    }
    
    public function show(Submit $submit)
    {
        return view('admin.submits.show', compact('submit'));
    }
    
    public function updateStatus(Request $request, Submit $submit)
    {
        $request->validate([
            'request_status' => 'required|in:prepare,requested,done'
        ]);
        
        $submit->update([
            'request_status' => $request->request_status
        ]);
        
        return redirect()->route('admin.submits.index')
            ->with('success', 'وضعیت درخواست با موفقیت تغییر یافت.');
    }
    
    public function getByName(Request $request)
    {
        $name = $request->get('name');
        
        if (empty($name)) {
            return response()->json(['items' => []]);
        }
        
        $submits = Submit::where('name', 'like', "%{$name}%")
            ->where('request_status', RequestStatusEnum::REQUESTED)
            ->select('id', 'name', 'mobile', 'created_at')
            ->get()
            ->map(function ($submit) {
                return [
                    'id' => $submit->id,
                    'name' => $submit->name,
                    'mobile' => $submit->mobile,
                    'created_at' => $submit->created_at->format('Y/m/d H:i')
                ];
            });
        
        return response()->json(['items' => $submits]);
    }
}
