<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::published()
            ->orderBy('published_date', 'desc')
            ->paginate(12);
            
        return view('frontend.pages.news.index', compact('news'));
    }
    
    public function show($slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();
        $news->incrementViews();
        
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->orderBy('published_date', 'desc')
            ->take(3)
            ->get();
            
        return view('frontend.pages.news.show', compact('news', 'relatedNews'));
    }
}
