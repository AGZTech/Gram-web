<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Scheme extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'eligibility',
        'benefits',
        'documents_required',
        'how_to_apply',
        'gr_link',
        'pdf_file',
        'featured_image',
        'department',
        'is_active',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($scheme) {
            if (empty($scheme->slug)) {
                $scheme->slug = Str::slug($scheme->title) . '-' . time();
            }
        });
    }

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
        return $query->where('is_active', true);
    }
}
