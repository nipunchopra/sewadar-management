<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListInCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'sewadar_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sewadar()
    {
        return $this->belongsTo(Sewadar::class);
    }
}
