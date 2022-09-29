<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;
    protected $table = 'genders';

    public static function getGenders(){
        return self::all()->toArray();
    }

    public static function getGenderByName($name){
        return self::where('name', $name)->get()->toArray();
    }
}
