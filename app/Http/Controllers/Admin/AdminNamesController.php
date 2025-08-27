<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Name;
use App\Models\Submit;
use App\RequestStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminNamesController extends Controller
{
    public function index(Request $request)
    {
        $query = Name::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Sort
        switch ($request->get('sort', 'name_asc')) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'use_count_desc':
                $query->orderBy('use_count', 'desc');
                break;
            case 'use_count_asc':
                $query->orderBy('use_count', 'asc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $perPage = $request->get('per_page', 10);
        $names = $query->paginate($perPage);

        return view('admin.names.index', compact('names'));
    }

    public function create()
    {
        return view('admin.names.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:names,name',
            'music' => 'required|file|mimes:mp3,wav,ogg|max:10240', // 10MB max
            'submits' => 'array',
            'submits.*' => 'exists:submits,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle file upload
        $file = $request->file('music');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('music', $fileName, 'public');

        // Create name record
        $name = Name::create([
            'name' => $request->name,
            'path' => $fileName,
            'use_count' => 0
        ]);

        // Send SMS to selected submits if any
        if ($request->filled('submits')) {
            $submits = Submit::whereIn('id', $request->submits)
                ->where('request_status', RequestStatusEnum::REQUESTED)
                ->get();

            foreach ($submits as $submit) {
                // Send SMS notification
                $musicUrl = asset('storage/' . $name->path);
                $submit->notify(new \App\Notifications\SendMusicPathNotification(
                    $submit->mobile,
                    $musicUrl,
                    $name->name
                ));

                $submit->request_status = RequestStatusEnum::DONE;
                $submit->save();
            }
        }

        return redirect()->route('admin.names.index')
            ->with('success', 'نام جدید با موفقیت اضافه شد.');
    }

    public function show(Name $name)
    {
        return view('admin.names.show', compact('name'));
    }

    public function edit(Name $name)
    {
        return view('admin.names.edit', compact('name'));
    }

    public function update(Request $request, Name $name)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:names,name,' . $name->id,
            'music' => 'nullable|file|mimes:mp3,wav,ogg|max:10240'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = ['name' => $request->name];

        // Handle file upload if provided
        if ($request->hasFile('music')) {
            // Delete old file
            if ($name->path && Storage::disk('public')->exists($name->path)) {
                Storage::disk('public')->delete($name->path);
            }

            $file = $request->file('music');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('music', $fileName, 'public');
            $data['path'] = $path;
        }

        $name->update($data);

        return redirect()->route('admin.names.index')
            ->with('success', 'نام با موفقیت بروزرسانی شد.');
    }

    public function destroy(Name $name)
    {
        // Delete file
        if ($name->path && Storage::disk('public')->exists($name->path)) {
            Storage::disk('public')->delete($name->path);
        }

        $name->delete();

        return redirect()->route('admin.names.index')
            ->with('success', 'نام با موفقیت حذف شد.');
    }

    public function getMatchingSubmits(Request $request)
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
