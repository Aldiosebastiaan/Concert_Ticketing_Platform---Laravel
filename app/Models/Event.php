<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'lokasi_id',
        'judul',
        'deskripsi',
        'gambar',
        'tanggal_waktu',
        'status',
        'status_publikasi',
    ];

    /**
     * Get the status histories for the event.
     */
    public function statusHistories()
    {
        return $this->hasMany(EventStatusHistory::class);
    }

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getStatusAttribute()
    {
        $now = now();
        $eventTime = $this->tanggal_waktu;

        if ($eventTime > $now) {
            return 'Upcoming';
        }

        if ($eventTime <= $now && $eventTime->copy()->addHours(3) > $now) {
            return 'On Going';
        }

        return 'Completed';
    }

    public function hasSales()
    {
        return $this->orders()->exists();
    }


    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_waktu', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('tanggal_waktu', '<=', now())
                     ->where('tanggal_waktu', '>', now()->subHours(3));
    }

    public function scopeCompleted($query)
    {
        return $query->where('tanggal_waktu', '<=', now()->subHours(3));
    }

    public function getImageUrlAttribute(): string
    {
        $url = $this->gambar;

        // Jika URL absolut (http/https) — Unsplash, external, dll
        if ($url && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
            return $url;
        }

        // Jika path file lokal di storage
        if ($url && $url !== 'konser.jpg') {
            return Storage::url($url);
        }

        // Fallback default
        return asset('images/konser.jpg');
    }

}
