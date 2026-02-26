<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('creator')
            ->orderBy('sort_order')
            ->paginate(15);
            
        return view('admin.pages.index', compact('pages'));
    }
    
    public function create()
    {
        return view('admin.pages.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/pages'), $filename);
            $validated['featured_image'] = 'uploads/pages/' . $filename;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_published'] = $request->has('is_published');
        
        Page::create($validated);
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'पेज यशस्वीरित्या तयार झाले');
    }
    
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }
    
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if ($request->hasFile('featured_image')) {
            if ($page->featured_image && file_exists(public_path($page->featured_image))) {
                unlink(public_path($page->featured_image));
            }
            
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/pages'), $filename);
            $validated['featured_image'] = 'uploads/pages/' . $filename;
        }
        
        $validated['updated_by'] = Auth::guard('admin')->id();
        $validated['is_published'] = $request->has('is_published');
        
        $page->update($validated);
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'पेज यशस्वीरित्या अपडेट झाले');
    }
    
    public function destroy(Page $page)
    {
        if ($page->featured_image && file_exists(public_path($page->featured_image))) {
            unlink(public_path($page->featured_image));
        }
        
        $page->delete();
        
        return redirect()->route('admin.pages.index')
            ->with('success', 'पेज यशस्वीरित्या हटवले गेले');
    }
}
