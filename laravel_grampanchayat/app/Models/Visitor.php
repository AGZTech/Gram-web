<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_visited',
        'referrer',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public static function recordVisit($page = null)
    {
        $ip = request()->ip();
        $today = Carbon::today();

        $exists = self::where('ip_address', $ip)
            ->where('visit_date', $today)
            ->exists();

        if (!$exists) {
            self::create([
                'ip_address' => $ip,
                'user_agent' => request()->userAgent(),
                'page_visited' => $page ?? request()->path(),
                'referrer' => request()->header('referer'),
                'visit_date' => $today,
            ]);
        }
    }

    public static function getTotalVisitors()
    {
        return self::distinct('ip_address')->count();
    }

    public static function getTodayVisitors()
    {
        return self::where('visit_date', Carbon::today())->count();
    }

    public static function getMonthlyVisitors()
    {
        return self::whereMonth('visit_date', Carbon::now()->month)
            ->whereYear('visit_date', Carbon::now()->year)
            ->count();
    }
}
