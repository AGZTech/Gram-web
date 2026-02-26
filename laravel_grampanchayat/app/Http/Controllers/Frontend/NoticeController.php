<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::published()
            ->orderBy('notice_date', 'desc')
            ->paginate(15);
            
        return view('frontend.pages.notices.index', compact('notices'));
    }
    
    public function show($id)
    {
        $notice = Notice::published()->findOrFail($id);
        return view('frontend.pages.notices.show', compact('notice'));
    }
}
