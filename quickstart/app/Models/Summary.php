<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;
    protected $table = "summary";
    protected $fillable = [
        "id",
        "gender",
        "ageGroup",
        "cabinType",
        "q1",
        "q2",
        "q3",
        "q4"
    ];


    public static function loadFromFile($departure, $arrival, $age, $gender, $cabinType, $q1, $q2, $q3, $q4){

    }
}
