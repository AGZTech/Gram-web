<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'event_date',
        'is_published',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title) . '-' . time();
            }
        });
    }

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id')->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getPhotosCountAttribute()
    {
        return $this->photos()->count();
    }
}
