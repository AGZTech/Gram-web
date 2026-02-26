<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PublicService;

class ServiceController extends Controller
{
    public function index()
    {
        $services = PublicService::published()
            ->orderBy('sort_order')
            ->paginate(12);
            
        return view('frontend.pages.services.index', compact('services'));
    }
    
    public function show($slug)
    {
        $service = PublicService::where('slug', $slug)->published()->firstOrFail();
        
        $otherServices = PublicService::published()
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order')
            ->take(5)
            ->get();
            
        return view('frontend.pages.services.show', compact('service', 'otherServices'));
    }
}
