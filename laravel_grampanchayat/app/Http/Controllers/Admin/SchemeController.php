<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.schemes.index', compact('schemes'));
    }
    
    public function create()
    {
        return view('admin.schemes.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'eligibility' => 'nullable|string',
            'benefits' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'how_to_apply' => 'nullable|string',
            'gr_link' => 'nullable|url|max:500',
            'department' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/schemes'), $filename);
            $validated['featured_image'] = 'uploads/schemes/' . $filename;
        }
        
        if ($request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfname = time() . '_' . Str::slug($validated['title']) . '.pdf';
            $pdf->move(public_path('uploads/schemes'), $pdfname);
            $validated['pdf_file'] = 'uploads/schemes/' . $pdfname;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_published'] = $request->has('is_published');
        
        Scheme::create($validated);
        
        return redirect()->route('admin.schemes.index')
            ->with('success', 'योजना यशस्वीरित्या जोडली गेली');
    }
    
    public function edit(Scheme $scheme)
    {
        return view('admin.schemes.edit', compact('scheme'));
    }
    
    public function update(Request $request, Scheme $scheme)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'eligibility' => 'nullable|string',
            'benefits' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'how_to_apply' => 'nullable|string',
            'gr_link' => 'nullable|url|max:500',
            'department' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        if ($request->hasFile('featured_image')) {
            if ($scheme->featured_image && file_exists(public_path($scheme->featured_image))) {
                unlink(public_path($scheme->featured_image));
            }
            
            $image = $request->file('featured_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/schemes'), $filename);
            $validated['featured_image'] = 'uploads/schemes/' . $filename;
        }
        
        if ($request->hasFile('pdf_file')) {
            if ($scheme->pdf_file && file_exists(public_path($scheme->pdf_file))) {
                unlink(public_path($scheme->pdf_file));
            }
            
            $pdf = $request->file('pdf_file');
            $pdfname = time() . '_' . Str::slug($validated['title']) . '.pdf';
            $pdf->move(public_path('uploads/schemes'), $pdfname);
            $validated['pdf_file'] = 'uploads/schemes/' . $pdfname;
        }
        
        $validated['is_active'] = $request->has('is_active');
        $validated['is_published'] = $request->has('is_published');
        
        $scheme->update($validated);
        
        return redirect()->route('admin.schemes.index')
            ->with('success', 'योजना यशस्वीरित्या अपडेट झाली');
    }
    
    public function destroy(Scheme $scheme)
    {
        if ($scheme->featured_image && file_exists(public_path($scheme->featured_image))) {
            unlink(public_path($scheme->featured_image));
        }
        if ($scheme->pdf_file && file_exists(public_path($scheme->pdf_file))) {
            unlink(public_path($scheme->pdf_file));
        }
        
        $scheme->delete();
        
        return redirect()->route('admin.schemes.index')
            ->with('success', 'योजना यशस्वीरित्या हटवली गेली');
    }
}
