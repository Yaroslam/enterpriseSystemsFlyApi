<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = "schedules";
    public $timestamps = false;

    public static function getSchedule($from, $to, $outbound, $flight, $sort){
        $schedule = self::all();
        if($from){
            $aiport = Airport::getAirportByCode($from);
            $route = Route::getRouteByDeparture($aiport["ID"]);
            $schedule = $schedule->where("RouteID", $route["ID"]);
        }
        if($to){
            $aiport = Airport::getAirportByCode($to);
            $route = Route::getRouteByArrival($aiport["ID"]);
            $schedule = $schedule->where("RouteID", $route["ID"]);
        }
        if($outbound){
            $schedule = $schedule->where("Date", $from);
        }
        if($flight){
            $schedule = $schedule->where("FlightNumber", $flight);
        }
        if($sort){
            $schedule = sortSchedule($schedule, $sort);
        } else{
            $schedule = sortSchedule($schedule, "Date");
            $schedule = sortSchedule($schedule, "Time");
        }
        return $schedule->toArray();
    }

    public static function changeFlightConfirm($id, $confirm){
        self::where("ID", $id)->update(['Confirmed' => (int)!$confirm]);
    }

    public static function updateFlight($id, $date, $time, $price){
        if($date){
            self::where("ID", $id)->update(['Date' => $date]);
        }
        if($time){
            self::where("ID", $id)->update(['Time' => $time]);
        }
        if($price){
            self::where("ID", $id)->update(['EconomyPrice' => $price]);
        }
        return self::where("ID", $id)->get()->toArray();
    }

    public static function UpdateFromFile($data){
        self::where("Date", $data[1])->where("FlightNumber", $data[3])->findOrFail();
        $arrival = Airport::getAirportByCode($data[5]);
        $departure = Airport::getAirportByCode($data[4]);
        self::where("Date", $data[1])->where("FlightNumber", $data[3])->update([
            "Date" => $data[1],
            "Time" => $data[2],
            "FlightNumber" => $data[3],
            "AircraftID" => $data[6],
            "EconomyPrice" => $data[7],
            "Confirmed" => (int)($data[8] === "OK"),
            "RouteID" => Route::getRouteByArrivalAndDeparture($arrival, $departure)["ID"]
        ]);
        return 1;
    }

    public static function loadFromFile($data){
        if(count(self::where("Date", $data[1])->where("FlightNumber", $data[3])->toArray()) > 0){
            return 0;
        }
        $arrival = Airport::getAirportByCode($data[5]);
        $departure = Airport::getAirportByCode($data[4]);
        self::insert([
            "Date" => $data[1],
            "Time" => $data[2],
            "FlightNumber" => $data[3],
            "AircraftID" => $data[6],
            "EconomyPrice" => $data[7],
            "Confirmed" => (int)($data[8] === "OK"),
            "RouteID" => Route::getRouteByArrivalAndDeparture($arrival, $departure)["ID"]
        ]);
        return 1;
    }

}
