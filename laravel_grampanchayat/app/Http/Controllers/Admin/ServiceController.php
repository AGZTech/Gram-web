<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = PublicService::with('creator')
            ->orderBy('sort_order')
            ->paginate(15);
            
        return view('admin.services.index', compact('services'));
    }
    
    public function create()
    {
        return view('admin.services.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'process' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'fees' => 'nullable|string|max:255',
            'time_duration' => 'nullable|string|max:255',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'icon' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('pdf_file')) {
            $pdf = $request->file('pdf_file');
            $pdfname = time() . '_' . Str::slug($validated['title']) . '.pdf';
            $pdf->move(public_path('uploads/services'), $pdfname);
            $validated['pdf_file'] = 'uploads/services/' . $pdfname;
        }
        
        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['is_published'] = $request->has('is_published');
        
        PublicService::create($validated);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'सेवा यशस्वीरित्या जोडली गेली');
    }
    
    public function edit(PublicService $service)
    {
        return view('admin.services.edit', compact('service'));
    }
    
    public function update(Request $request, PublicService $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'process' => 'nullable|string',
            'documents_required' => 'nullable|string',
            'fees' => 'nullable|string|max:255',
            'time_duration' => 'nullable|string|max:255',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'icon' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if ($request->hasFile('pdf_file')) {
            if ($service->pdf_file && file_exists(public_path($service->pdf_file))) {
                unlink(public_path($service->pdf_file));
            }
            
            $pdf = $request->file('pdf_file');
            $pdfname = time() . '_' . Str::slug($validated['title']) . '.pdf';
            $pdf->move(public_path('uploads/services'), $pdfname);
            $validated['pdf_file'] = 'uploads/services/' . $pdfname;
        }
        
        $validated['is_published'] = $request->has('is_published');
        
        $service->update($validated);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'सेवा यशस्वीरित्या अपडेट झाली');
    }
    
    public function destroy(PublicService $service)
    {
        if ($service->pdf_file && file_exists(public_path($service->pdf_file))) {
            unlink(public_path($service->pdf_file));
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'सेवा यशस्वीरित्या हटवली गेली');
    }
}
