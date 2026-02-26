<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\News;
use App\Models\Scheme;
use App\Models\DevelopmentWork;
use App\Models\GalleryAlbum;
use App\Models\ContactInquiry;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'notices' => Notice::count(),
            'news' => News::count(),
            'schemes' => Scheme::count(),
            'works' => DevelopmentWork::count(),
            'albums' => GalleryAlbum::count(),
            'inquiries' => ContactInquiry::where('status', 'new')->count(),
            'total_visitors' => Visitor::getTotalVisitors(),
            'today_visitors' => Visitor::getTodayVisitors(),
            'monthly_visitors' => Visitor::getMonthlyVisitors(),
        ];
        
        $recentNotices = Notice::orderBy('created_at', 'desc')->take(5)->get();
        $recentNews = News::orderBy('created_at', 'desc')->take(5)->get();
        $recentInquiries = ContactInquiry::where('status', 'new')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Visitor chart data for last 7 days
        $visitorData = Visitor::select(
                DB::raw('DATE(visit_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('visit_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
            
        return view('admin.dashboard', compact(
            'stats',
            'recentNotices',
            'recentNews',
            'recentInquiries',
            'visitorData'
        ));
    }
}
