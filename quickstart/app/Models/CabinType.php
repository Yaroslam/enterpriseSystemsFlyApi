<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabinType extends Model
{
    use HasFactory;
    protected $table = "cabintypes";

    public static function getAllCabins(){
        return self::all()->toArray();
    }
}
