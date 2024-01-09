<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ApiBarat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jawa_barat()
    {
        //return $this->belongsTo(Jbarat::class, 'location', 'location');
        return $this->belongsTo(Lokasi::class, 'location', 'location')
                ->where('provinsi', 'Jawa Barat');
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

    public function Jbarat()
    {
        return $this->hasOne(Jbarat::class, 'location', 'location');
    }
}
