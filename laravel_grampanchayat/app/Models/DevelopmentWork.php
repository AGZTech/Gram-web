<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentWork extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'budget',
        'spent_amount',
        'start_date',
        'completion_date',
        'status',
        'progress_percentage',
        'contractor_name',
        'featured_image',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
        'budget' => 'decimal:2',
        'spent_amount' => 'decimal:2',
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

    public function getStatusLabelAttribute()
    {
        $labels = [
            'planned' => 'नियोजित',
            'in_progress' => 'प्रगतीपथावर',
            'completed' => 'पूर्ण',
            'on_hold' => 'स्थगित',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'planned' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'on_hold' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
