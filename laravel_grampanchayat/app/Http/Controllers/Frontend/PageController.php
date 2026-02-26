<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Member;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::where('slug', 'about')->first();
        return view('frontend.pages.about', compact('page'));
    }
    
    public function history()
    {
        $page = Page::where('slug', 'history')->first();
        return view('frontend.pages.history', compact('page'));
    }
    
    public function members()
    {
        $members = Member::active()
            ->orderBy('sort_order')
            ->orderBy('designation')
            ->get();
            
        return view('frontend.pages.members', compact('members'));
    }
    
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->published()->firstOrFail();
        return view('frontend.pages.show', compact('page'));
    }
}
