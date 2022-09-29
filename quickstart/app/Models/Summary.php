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


    public static function loadFromFile($departure, $arrival, $age, $gender, $cabinType, $q1, $q2, $q3, $q4, $date){
        if($departure == ''){
            $departure = null;
        } else {
            $departure = Airport::getAirportByCode($departure)["ID"];
        }

        if($arrival == ''){
            $arrival = null;
        } else {
            $arrival = Airport::getAirportByCode($arrival)["ID"];
        }

        if($age == ''){
            $age = null;
        } else {
            $age = CalculateAgeGroup($age)[0]["id"];
        }

        if($gender == ''){
            $gender = null;
        } else {
            $gender = Gender::getGenderByName($gender)[0]['id'];
        }

        if($cabinType == '') {
            $cabinType = null;
        } else if ($cabinType == 'First') {
            $cabinType = CabinType::getCabinByName("First Class")["ID"];
        }
        else {
            $cabinType = CabinType::getCabinByName($cabinType)["ID"];
        }

        $q1 = Answer::getAnswerByCode((int)$q1)[0]['id'];
        $q2 = Answer::getAnswerByCode((int)$q2)[0]['id'];
        $q3 = Answer::getAnswerByCode((int)$q3)[0]['id'];
        $q4 = Answer::getAnswerByCode((int)$q4)[0]['id'];

        self::insert([
            'gender' => $gender,
            'ageGroup' => $age,
            'cabinType' => $cabinType,
            'arrival' => $arrival,
            'departure' => $departure,
            'q1' => $q1,
            'q2' => $q2,
            'q3' => $q3,
            'q4' => $q4,
            "date" => $date
        ]);
    }

    public static function getByGender($gender){
        return self::where('gender', $gender)->get()->toArray();
    }

    public static function getByCabin($cabin){
        return self::where('cabinType', $cabin)->get()->toArray();
    }

    public static function getByAge($age){
        return self::where('ageGroup', $age)->get()->toArray();
    }

    public static function getByArrivalAirport($airport){
        return self::where('arrival', $airport)->get()->toArray();
    }

    public static function getByDepartureAirport($airport){
        return self::where('departure', $airport)->get()->toArray();
    }

    public static function count(){
        return self::all()->count();
    }

}
