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
            $schedule = $schedule->where("Date", $outbound);
        }
        if($flight){
            $schedule = $schedule->where("FlightNumber", $flight);
        }
        if($sort){
            $schedule = sortSchedule($schedule, $sort);
        } else{
            $schedule = $schedule->sortBy('Date')->sortBy('Time');
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
        if(count(self::where("Date", $data["date"])->where("FlightNumber", $data["flight"])->get()->toArray()) == 0){
            return -1;
        }
        $arrival = Airport::getAirportByCode($data["to"]);
        $departure = Airport::getAirportByCode($data["from"]);
        self::where("Date", $data["date"])->where("FlightNumber", $data["flight"])->update([
            "Date" => $data["date"],
            "Time" => $data['time'],
            "FlightNumber" => $data["flight"],
            "AircraftID" => $data['aircraft'],
            "EconomyPrice" => $data['price'],
            "Confirmed" => (int)($data['status'] === "OK"),
            "RouteID" => Route::getRouteByArrivalAndDeparture($arrival, $departure)["ID"]
        ]);
        return 1;
    }


    public static function getFlightsBetweenDates($outbound){
        $outbound = strtotime($outbound);
        $date1 = date("Y-m-d", $outbound + 3*24*60*60);
        $date2 = date("Y-m-d", $outbound - 3*24*60*60);
        return self::orderBy('Date')->orderBy('Time')->whereBetween('Date', [$date2, $date1])->get()->toArray();
    }

    public static function getFlightsForBooking($from, $to, $outbound, $advanced){
        $schedule = self::orderBy('Date')->orderBy('Time')->get();
        if($advanced){
            $outbound = strtotime($outbound);
            $date1 = date("Y-m-d", $outbound + 3*24*60*60);
            $date2 = date("Y-m-d", $outbound - 3*24*60*60);
            $schedule = $schedule->whereBetween('Date', [$date2, $date1]);
        } else {
            if($outbound){
                $schedule = $schedule->where("Date", $outbound);
                }
        }

        return $schedule->toArray();
    }

    public static function getScheduleByDateAndFlightNumber($flightNumber, $date){
        return self::where("Date", $date)->where("FlightNumber", $flightNumber)->get()->toArray();
    }
    public static function getScheduleByDate($date){
        return self::where("Date", $date)->get()->toArray();
    }

    public static function loadFromFile($data){
        if(count(self::where("Date", $data["date"])->where("FlightNumber", $data["flight"])->get()->toArray()) > 0){
            return 0;
        }
        $arrival = Airport::getAirportByCode($data["to"]);
        $departure = Airport::getAirportByCode($data["from"]);
        self::insert([
            "Date" => $data["date"],
            "Time" => $data['time'],
            "FlightNumber" => $data["flight"],
            "AircraftID" => $data['aircraft'],
            "EconomyPrice" => $data['price'],
            "Confirmed" => (int)($data['status'] === "OK"),
            "RouteID" => Route::getRouteByArrivalAndDeparture($arrival, $departure)["ID"]
        ]);
        return 1;
    }

    public static function getScheduleById($id){
        return self::where('ID', $id)->get()->toArray();
    }

    public static function getScheduleByStartDate($startDate){
        $saveDate = $startDate;
        $startDate = date("Y-m-d",strtotime($startDate) - 30*24*60*60);
        return self::where('Date', ">", $startDate)->where("Date", "<", $saveDate)->where("Confirmed", 1)->get()->toArray();
    }

    public static function getSchedule7Day($startDate){
        $saveDate = $startDate;
        $startDate = date("Y-m-d",strtotime($startDate) - 7*24*60*60);
        return self::where('Date', ">", $startDate)->where("Date", "<", $saveDate)->where("Confirmed", 1)->get()->toArray();
    }

    public static function getScheduleDay($startDate){
        $saveDate = $startDate;
        $startDate = date("Y-m-d",strtotime($startDate) - 24*60*60);
        return self::where('Date', ">", $startDate)->where("Date", "<", $saveDate)->where("Confirmed", 1)->get()->toArray();
    }


    public static function getDateses($startDate){
        $saveDate = $startDate;
        return self::where('Date', ">", $startDate)->where("Date", "<", $saveDate)->get()->toArray();
    }

}
