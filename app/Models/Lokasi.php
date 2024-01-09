<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $guarded = [];

     public function apiBarat()
    {
        return $this->belongsTo(ApiBarat::class, 'location');
    }

    public function apiTengah()
    {
        return $this->hasMany(ApiTengah::class, 'location');
    }

    public function weather()
    {
        return $this->hasOneThrough(Weather::class, ApiBarat::class, 'location', 'api_barat_id', 'id', 'id');
    }
}
