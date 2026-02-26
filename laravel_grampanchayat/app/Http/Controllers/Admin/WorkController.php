<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkController extends Controller
{
    public function index()
    {
        $works = DevelopmentWork::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.works.index', compact('works'));
    }
    
    public function create()
    {
        return view('admin.works.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,on_hold',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'contractor_name' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/works'), $filename);
            $validated['featured_image'] = 'uploads/works/' . $filename;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_published'] = $request->has('is_published');
        
        DevelopmentWork::create($validated);
        
        return redirect()->route('admin.works.index')
            ->with('success', 'विकासकाम यशस्वीरित्या जोडले गेले');
    }
    
    public function edit(DevelopmentWork $work)
    {
        return view('admin.works.edit', compact('work'));
    }
    
    public function update(Request $request, DevelopmentWork $work)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'spent_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,on_hold',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'contractor_name' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('featured_image')) {
            if ($work->featured_image && file_exists(public_path($work->featured_image))) {
                unlink(public_path($work->featured_image));
            }
            
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/works'), $filename);
            $validated['featured_image'] = 'uploads/works/' . $filename;
        }
        
        $validated['is_published'] = $request->has('is_published');
        
        $work->update($validated);
        
        return redirect()->route('admin.works.index')
            ->with('success', 'विकासकाम यशस्वीरित्या अपडेट झाले');
    }
    
    public function destroy(DevelopmentWork $work)
    {
        if ($work->featured_image && file_exists(public_path($work->featured_image))) {
            unlink(public_path($work->featured_image));
        }
        
        $work->delete();
        
        return redirect()->route('admin.works.index')
            ->with('success', 'विकासकाम यशस्वीरित्या हटवले गेले');
    }
}
