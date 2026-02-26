<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'notice_date',
        'expiry_date',
        'is_important',
        'show_in_ticker',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'notice_date' => 'date',
        'expiry_date' => 'date',
        'is_important' => 'boolean',
        'show_in_ticker' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', Carbon::today());
        });
    }

    public function scopeTicker($query)
    {
        return $query->where('show_in_ticker', true);
    }

    public function getFormattedDateAttribute()
    {
        return $this->notice_date->format('d-m-Y');
    }
}
