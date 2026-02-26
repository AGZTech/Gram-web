<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'ip_address',
        'replied_by',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function repliedBy()
    {
        return $this->belongsTo(Admin::class, 'replied_by');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'new' => 'नवीन',
            'read' => 'वाचलेले',
            'replied' => 'उत्तर दिलेले',
            'closed' => 'बंद',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'new' => 'bg-red-100 text-red-800',
            'read' => 'bg-yellow-100 text-yellow-800',
            'replied' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
