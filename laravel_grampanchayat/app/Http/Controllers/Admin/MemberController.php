<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('sort_order')
            ->orderBy('designation')
            ->paginate(15);
            
        return view('admin.members.index', compact('members'));
    }
    
    public function create()
    {
        return view('admin.members.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string|max:1000',
            'ward_number' => 'nullable|string|max:50',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after_or_equal:term_start',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/members'), $filename);
            $validated['photo'] = 'uploads/members/' . $filename;
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        Member::create($validated);
        
        return redirect()->route('admin.members.index')
            ->with('success', 'सदस्य यशस्वीरित्या जोडला गेला');
    }
    
    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }
    
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string|max:1000',
            'ward_number' => 'nullable|string|max:50',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after_or_equal:term_start',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        if ($request->hasFile('photo')) {
            if ($member->photo && file_exists(public_path($member->photo))) {
                unlink(public_path($member->photo));
            }
            
            $image = $request->file('photo');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/members'), $filename);
            $validated['photo'] = 'uploads/members/' . $filename;
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        $member->update($validated);
        
        return redirect()->route('admin.members.index')
            ->with('success', 'सदस्य यशस्वीरित्या अपडेट झाला');
    }
    
    public function destroy(Member $member)
    {
        if ($member->photo && file_exists(public_path($member->photo))) {
            unlink(public_path($member->photo));
        }
        
        $member->delete();
        
        return redirect()->route('admin.members.index')
            ->with('success', 'सदस्य यशस्वीरित्या हटवला गेला');
    }
}
