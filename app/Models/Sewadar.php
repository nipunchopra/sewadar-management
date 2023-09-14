<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewadar extends Model
{

    protected $fillable = [
        'area_id',
        'group_id',
        'badge_number',
        'photo',
        'first_name',
        'last_name',
        'father_name',
        'dob',
        'mobile',
        'alt_mobile',
        'address',
        'city',
        'blood_group',
        'occupation',
        'education',
        'naamdan',
        'date_of_naamdan',
        'place_of_naamdan',
        'naamdan_by',
        'address_at_time_of_naamdan',
        'address_at_time_of_naamdan_same_as_present',
        'mobile_permission',
        'car_permission',
        'car_number',
        'car_name',
        'car_seats',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
