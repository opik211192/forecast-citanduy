<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiTengah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jawa_tengah()
    {
        //return $this->belongsTo(Jtengah::class, 'location', 'location');
        return $this->belongsTo(Lokasi::class, 'location', 'location')
                ->where('provinsi', 'Jawa Tengah');
    }

    public function weather()
    {
        return $this->belongsTo(Weather::class, 'weather_code', 'weather_code');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d-m-Y');
    }

    public function getTimestampFormattedAttribute()
    {
        return Carbon::parse($this->attributes['timestamp'])->format('d-m-Y H:i:s');
    }

    public function jTengah()
    {
        return $this->hasOne(Jtengah::class, 'location', 'location');
    }
}