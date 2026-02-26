<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'phone',
        'email',
        'photo',
        'bio',
        'ward_number',
        'term_start',
        'term_end',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'term_start' => 'date',
        'term_end' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDesignationLabelAttribute()
    {
        $labels = [
            'sarpanch' => 'सरपंच',
            'up_sarpanch' => 'उपसरपंच',
            'member' => 'सदस्य',
            'gram_sevak' => 'ग्रामसेवक',
            'secretary' => 'सचिव',
        ];

        return $labels[$this->designation] ?? $this->designation;
    }
}
