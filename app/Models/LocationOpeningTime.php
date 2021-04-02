<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationOpeningTime extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        'day',
        'open_time',
        'closed_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'location_id',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
		'day_and_opening_times'
	];

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function getDayAndOpeningTimesAttribute()
    {
        return ucwords($this->day).': '.$this->open_time.' to '.$this->closed_time;
    }
}
