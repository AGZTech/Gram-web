<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::published()
            ->withCount('photos')
            ->orderBy('event_date', 'desc')
            ->paginate(12);
            
        return view('frontend.pages.gallery.index', compact('albums'));
    }
    
    public function show($slug)
    {
        $album = GalleryAlbum::where('slug', $slug)
            ->published()
            ->with('photos')
            ->firstOrFail();
            
        return view('frontend.pages.gallery.show', compact('album'));
    }
}
