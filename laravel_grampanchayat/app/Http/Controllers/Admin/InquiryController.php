<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.inquiries.index', compact('inquiries'));
    }
    
    public function show(ContactInquiry $inquiry)
    {
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }
        
        return view('admin.inquiries.show', compact('inquiry'));
    }
    
    public function updateStatus(Request $request, ContactInquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $inquiry->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $inquiry->admin_notes,
            'replied_by' => $validated['status'] === 'replied' ? Auth::guard('admin')->id() : null,
            'replied_at' => $validated['status'] === 'replied' ? now() : null,
        ]);
        
        return redirect()->back()
            ->with('success', 'स्थिती यशस्वीरित्या अपडेट झाली');
    }
    
    public function destroy(ContactInquiry $inquiry)
    {
        $inquiry->delete();
        
        return redirect()->route('admin.inquiries.index')
            ->with('success', 'चौकशी यशस्वीरित्या हटवली गेली');
    }
}
