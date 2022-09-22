<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;
    protected $table = "airports";

    public static function getAllAirports(){
        return self::all()->toArray();
    }

    public static function getAirportCode($id){
        return self::where("ID", $id)->get()->toArray()[0]['IATACode'];
    }

    public static function getAirportByCode($code){
        return self::where("IATACode", $code)->get()->toArray()[0];
    }

    public static function getAirportById($id){
        return self::where("ID", $id)->get()->toArray()[0];
    }
}

