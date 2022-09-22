<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use HasFactory;

    protected $table = "aircrafts";

    public static function getAircraftById($id){
        return self::where("ID", $id)->get()->toArray()[0];
    }
}
