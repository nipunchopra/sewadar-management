<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'sewadar_id',
    ];

    public function sewadar()
    {
        return $this->belongsTo(Sewadar::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
