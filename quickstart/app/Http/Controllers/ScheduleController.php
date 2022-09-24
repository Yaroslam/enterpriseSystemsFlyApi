<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\loadStringRequest;
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


    public function loadFromFile(Request $request)
    {
        $content = $request->getContent();
        $json = json_decode($content);
        $validator = Validator::make($request->all(), ["action" => 'required|string|in:ADD,EDIT',
            "date" => 'required|date|date_format:Y-m-d',
            "time" => 'required|date_format:H:i',
            'flight' => 'required|integer',
            "from" => "required|string|exists:airports,IATACode",
            "to" => "required|string|exists:airports,IATACode",
            'aircraft' => 'required|integer|exists:aircrafts,ID',
            'price' => 'required|integer',
            'status' => 'required|string|in:OK,CANCELED']);

        if ($validator->fails()) {
            return  Response($validator->errors(), 400);
        }
        if($request['action'] == "ADD"){
            $responseData = Schedule::loadFromFile($request->all());
        } else if($request['action'] == "EDIT"){
            $responseData = Schedule::UpdateFromFile($request->all());
        }
        return  Response($responseData, 200);
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
