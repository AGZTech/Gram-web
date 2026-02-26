<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'published_date',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_published',
        'views',
        'created_by',
    ];

    protected $casts = [
        'published_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title) . '-' . time();
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getFormattedDateAttribute()
    {
        return $this->published_date->format('d-m-Y');
    }
}
