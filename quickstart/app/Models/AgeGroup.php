<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use HasFactory;
    protected $table = "ageGroups";

    public static function getGroups(){
        return self::all()->toArray();
    }

    public static function getAgeGroupByScope($scope){
        return self::where("scope", $scope)->get()->toArray();
    }
}
