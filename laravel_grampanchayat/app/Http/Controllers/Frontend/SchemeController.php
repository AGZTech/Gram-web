<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Scheme;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::published()->active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('frontend.pages.schemes.index', compact('schemes'));
    }
    
    public function show($slug)
    {
        $scheme = Scheme::where('slug', $slug)->published()->firstOrFail();
        
        $relatedSchemes = Scheme::published()->active()
            ->where('id', '!=', $scheme->id)
            ->take(3)
            ->get();
            
        return view('frontend.pages.schemes.show', compact('scheme', 'relatedSchemes'));
    }
}
