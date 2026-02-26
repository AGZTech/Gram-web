<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'ईमेल आवश्यक आहे',
            'password.required' => 'पासवर्ड आवश्यक आहे',
        ]);
        
        $admin = Admin::where('email', $credentials['email'])->first();
        
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors([
                'email' => 'ईमेल किंवा पासवर्ड चुकीचे आहे',
            ])->withInput($request->only('email'));
        }
        
        if (!$admin->is_active) {
            return back()->withErrors([
                'email' => 'तुमचे खाते निष्क्रिय आहे. कृपया प्रशासकाशी संपर्क साधा.',
            ]);
        }
        
        Auth::guard('admin')->login($admin, $request->filled('remember'));
        
        $admin->update(['last_login' => now()]);
        
        $request->session()->regenerate();
        
        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'स्वागत आहे, ' . $admin->name);
    }
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'तुम्ही यशस्वीरित्या लॉगआउट झाला आहात');
    }
}
