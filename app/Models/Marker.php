<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;
    // app/Models/Marker.php
    protected $fillable = ['name', 'latitude', 'longitude'];

}
