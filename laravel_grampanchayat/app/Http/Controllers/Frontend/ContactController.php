<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.pages.contact');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:15',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'नाव आवश्यक आहे',
            'phone.required' => 'मोबाईल नंबर आवश्यक आहे',
            'subject.required' => 'विषय आवश्यक आहे',
            'message.required' => 'संदेश आवश्यक आहे',
        ]);
        
        $validated['ip_address'] = $request->ip();
        
        ContactInquiry::create($validated);
        
        return redirect()->back()->with('success', 'तुमचा संदेश यशस्वीरित्या पाठवला गेला आहे. आम्ही लवकरच तुमच्याशी संपर्क साधू.');
    }
}
