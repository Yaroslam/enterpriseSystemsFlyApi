<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\Route;
use App\Models\Schedule;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{

//  TODO
//      1)получить список всех вылетов
//      2)сортировать список вылетов
//      3)получать код аэропорта
//      4)получать аэропорт по id
//      5)получать роут по ид
//      6)импорт файла
//      7)отмена рейса
//      8) расчет цены рейса

    public function getSchedule(Request $request){
        $schedule = Schedule::getSchedule($request['from'], $request['to'], $request["outbound"], $request['flight'], $request['sort']);
        $res = [];
        foreach ($schedule as $sched){
            $route = Route::getRouteById($sched["RouteID"]);
            $sched['aircraft'] = Aircraft::getAircraftById($sched["AircraftID"])["Name"];
            $sched['to'] = Airport::getAirportCode($route["ArrivalAirportID"]);
            $sched['from'] = Airport::getAirportCode($route["DepartureAirportID"]);
            $sched['businessClass'] = (int)($sched["EconomyPrice"]*1.35);
            $sched['firstClass'] = (int)($sched["EconomyPrice"]*1.30);
            $res[] = $sched;
        }
        return $res;
    }

//    public function loadSchedule(){}

    public function update(Request $request)
    {
        $path = $request->file('avatar')->store('avatars');
        var_dump($path);
        $path = storage_path() . "/app/${path}";
        $a = kama_parse_csv_file($path);
        foreach ($a as $data) {
        }
        return 1;
    }

    public function changeFlightConfirm(Request $request){
        $flight = Schedule::where("ID",$request->input("id"))->firstOrFail();
        Schedule::changeFlightConfirm($flight->ID, $flight->Confirmed);
    }

    public function updateFlight(Request $request){
        $flight = Schedule::where("ID",$request->input("id"))->firstOrFail();
        return Schedule::updateFlight($flight->ID, $request->input('date'), $request->input("time"), (int)$request->input("price"));
    }


}
