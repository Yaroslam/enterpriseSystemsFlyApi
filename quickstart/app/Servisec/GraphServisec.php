<?php

use App\Models\Airport;
use App\Models\Route;

function createGraph($v){
    $graphNodes = [];
    foreach ($v as $vv){
        if(key_exists($vv[0], $graphNodes)){
            if(!in_array($vv[1], $graphNodes[$vv[0]])){
                $graphNodes[$vv[0]][] = $vv[1];
            }
        } else {
            $graphNodes[$vv[0]] = [];
            $graphNodes[$vv[0]][] = $vv[1];
        }
    }
    return $graphNodes;
}

function getFlightsFromGraph($schedule, $routes){
    $schedules = $schedule;
    $res = [];
    foreach ($routes as $route){
        $i = 0;
        $sche = [];
        while($i<count($route)-1){
            $from = $route[$i];
            $to = $route[$i+1];

            $to = Airport::getAirportByCode($to)['ID'];
            $from = Airport::getAirportByCode($from)['ID'];
            $routeID = Route::getRouteByArrivalAndDepartureAll($to, $from);
            if($routeID != 0){
                foreach ($routeID as $id){
                    $rID = $id["ID"];
                    foreach ($schedules as $s){
                        if($s['RouteID'] == $rID){
                            if(count($sche) > 0){
                                if(strtotime($s["Date"]) >= strtotime($sche[count($sche)-1]['Date'])
                                    && strtotime($s["Time"]) > strtotime($sche[count($sche)-1]['Time']) && $s['Confirmed']) {
                                    $sche[] = $s;
                                }
                            } else {
                                $sche[] = $s;
                            }
                        }
                    }
                }
            }
            $i++;
        }
        if(count($sche) > 0){
            $res[] = $sche;
        }
    }
    return $res;
}
