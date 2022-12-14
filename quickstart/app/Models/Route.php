<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = "routes";

    public static function getRouteByArrival($arrival){
        return self::where("ArrivalAirportID", $arrival)->get()->toArray()[0];
    }

    public static function getRouteByDeparture($departure){
        return self::where("DepartureAirportID", $departure)->get()->toArray()[0];
    }

    public static function getRouteById($id){
        return self::where("ID", $id)->get()->toArray()[0];
    }

    public static function getRouteByArrivalAndDeparture($arrival, $departure){
        $res = self::where("DepartureAirportID", $departure)->where("ArrivalAirportID", $arrival)->get()->toArray();
        if(count($res) > 0){
            return $res[0];
        } else {
            return 0;
        }
    }

    public static function getRouteByArrivalAndDepartureAll($arrival, $departure){
        $res = self::where("DepartureAirportID", $departure)->where("ArrivalAirportID", $arrival)->get()->toArray();
        if(count($res) > 0){
            return $res;
        } else {
            return 0;
        }
    }
}
