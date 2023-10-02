<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hujan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'location', 'location');
    }

    public function weather()
    {
        return $this->belongsTo(Weather::class, 'weather_code', 'weather_code');
    }
}
