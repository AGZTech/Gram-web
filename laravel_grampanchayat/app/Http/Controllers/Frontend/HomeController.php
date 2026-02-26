<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\News;
use App\Models\Scheme;
use App\Models\DevelopmentWork;
use App\Models\GalleryAlbum;
use App\Models\Member;
use App\Models\Setting;
use App\Models\Visitor;

class HomeController extends Controller
{
    public function index()
    {
        $notices = Notice::published()->active()->ticker()
            ->orderBy('notice_date', 'desc')
            ->take(10)
            ->get();
            
        $latestNews = News::published()
            ->orderBy('published_date', 'desc')
            ->take(6)
            ->get();
            
        $featuredNews = News::published()->featured()
            ->orderBy('published_date', 'desc')
            ->first();
            
        $schemes = Scheme::published()->active()
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
            
        $recentWorks = DevelopmentWork::published()
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
            
        $albums = GalleryAlbum::published()
            ->with('photos')
            ->orderBy('event_date', 'desc')
            ->take(4)
            ->get();
            
        $sarpanch = Member::active()
            ->where('designation', 'sarpanch')
            ->first();
            
        $totalVisitors = Visitor::getTotalVisitors();
        
        return view('frontend.pages.home', compact(
            'notices',
            'latestNews',
            'featuredNews',
            'schemes',
            'recentWorks',
            'albums',
            'sarpanch',
            'totalVisitors'
        ));
    }
}
