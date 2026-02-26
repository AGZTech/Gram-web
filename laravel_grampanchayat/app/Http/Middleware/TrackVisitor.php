<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('admin/*') && !$request->ajax()) {
            Visitor::recordVisit();
        }

        return $next($request);
    }
}
