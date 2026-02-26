<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.notices.index', compact('notices'));
    }
    
    public function create()
    {
        return view('admin.notices.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:notice_date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_important' => 'boolean',
            'show_in_ticker' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/notices'), $filename);
            $validated['file_path'] = 'uploads/notices/' . $filename;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_important'] = $request->has('is_important');
        $validated['show_in_ticker'] = $request->has('show_in_ticker');
        $validated['is_published'] = $request->has('is_published');
        
        Notice::create($validated);
        
        return redirect()->route('admin.notices.index')
            ->with('success', 'नोटीस यशस्वीरित्या जोडली गेली');
    }
    
    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }
    
    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:notice_date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_important' => 'boolean',
            'show_in_ticker' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('file')) {
            // Delete old file
            if ($notice->file_path && file_exists(public_path($notice->file_path))) {
                unlink(public_path($notice->file_path));
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/notices'), $filename);
            $validated['file_path'] = 'uploads/notices/' . $filename;
        }
        
        $validated['is_important'] = $request->has('is_important');
        $validated['show_in_ticker'] = $request->has('show_in_ticker');
        $validated['is_published'] = $request->has('is_published');
        
        $notice->update($validated);
        
        return redirect()->route('admin.notices.index')
            ->with('success', 'नोटीस यशस्वीरित्या अपडेट झाली');
    }
    
    public function destroy(Notice $notice)
    {
        if ($notice->file_path && file_exists(public_path($notice->file_path))) {
            unlink(public_path($notice->file_path));
        }
        
        $notice->delete();
        
        return redirect()->route('admin.notices.index')
            ->with('success', 'नोटीस यशस्वीरित्या हटवली गेली');
    }
}
