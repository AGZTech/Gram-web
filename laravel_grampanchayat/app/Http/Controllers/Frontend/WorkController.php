<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DevelopmentWork;

class WorkController extends Controller
{
    public function index()
    {
        $works = DevelopmentWork::published()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $stats = [
            'total' => DevelopmentWork::published()->count(),
            'completed' => DevelopmentWork::published()->where('status', 'completed')->count(),
            'in_progress' => DevelopmentWork::published()->where('status', 'in_progress')->count(),
            'planned' => DevelopmentWork::published()->where('status', 'planned')->count(),
        ];
            
        return view('frontend.pages.works.index', compact('works', 'stats'));
    }
    
    public function show($id)
    {
        $work = DevelopmentWork::published()->findOrFail($id);
        return view('frontend.pages.works.show', compact('work'));
    }
}
