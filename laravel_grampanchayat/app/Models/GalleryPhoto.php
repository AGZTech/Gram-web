<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = [
        'album_id',
        'image_path',
        'thumbnail_path',
        'caption',
        'sort_order',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }
}
