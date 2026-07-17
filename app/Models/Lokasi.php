<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lokasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lokasis';

    protected $fillable = [
        'nama_lokasi',
        'aktif',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'lokasi_id');
    }
}
