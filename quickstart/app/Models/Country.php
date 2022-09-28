<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = "countries";

    public static function getAllCountries(){
        return self::all()->toArray();
    }

    public static function getCountryByName($name){
        return self::where("Name", $name)->get()->toArray();
    }
}
