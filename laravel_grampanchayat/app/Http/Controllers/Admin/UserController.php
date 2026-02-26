<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = Admin::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:super_admin,editor',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'नाव आवश्यक आहे',
            'email.required' => 'ईमेल आवश्यक आहे',
            'email.unique' => 'हा ईमेल आधीच वापरला गेला आहे',
            'password.required' => 'पासवर्ड आवश्यक आहे',
            'password.min' => 'पासवर्ड किमान 8 अक्षरांचा असावा',
            'password.confirmed' => 'पासवर्ड जुळत नाही',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        
        Admin::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'वापरकर्ता यशस्वीरित्या तयार झाला');
    }
    
    public function edit(Admin $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, Admin $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => 'required|in:super_admin,editor',
            'is_active' => 'boolean',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'वापरकर्ता यशस्वीरित्या अपडेट झाला');
    }
    
    public function destroy(Admin $user)
    {
        if ($user->isSuperAdmin() && Admin::where('role', 'super_admin')->count() === 1) {
            return redirect()->back()
                ->with('error', 'शेवटचा Super Admin हटवता येत नाही');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'वापरकर्ता यशस्वीरित्या हटवला गेला');
    }
}
