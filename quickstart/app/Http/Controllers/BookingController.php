<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\TrustHosts;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Ticket;
use App\Servisec\Classes\Graph;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    private function makeGraph($nodes){
        $graph = new Graph(count($nodes));
        foreach (array_keys($nodes) as $k){ //$k = $i
            foreach ($nodes[$k] as $e){
                $graph->addEdgr($k, $e);
            }
        }
        return $graph;
    }

    public function getFlightsForBooking(Request $request){
        return 1;
//        $schedule = Schedule::getFlightsBetweenDates($request['date']);
//        $varRoutes = [];
//        foreach ($schedule as $s){
//            $r = Route::getRouteById($s["RouteID"]);
//            $varRoutes[] = [Airport::getAirportCode($r["DepartureAirportID"]), Airport::getAirportCode($r["ArrivalAirportID"])];
//        }
//        return createGraph($varRoutes);
//        $graph = $this->makeGraph(createGraph($varRoutes));
//        $s = "AUH";
//        $d = "ADE";
//        $graph->printAllPathsInGrapg($s, $d);
//        return $graph->pathes;
    }

    public function checkBooking(Request $request){
        $response = [];
        foreach ($request->input("flights") as $flightParam){
            $flight = Schedule::getScheduleByDateAndFlightNumber($flightParam['flightNumber'], $flightParam['date']);;
            $tickets = Ticket::getFlightTickets($flight[0]["ID"]);
            $aircraft = Aircraft::getAircraftById($flight[0]['AircraftID']);
            if(count($tickets) >= $aircraft["TotalSeats"]){
                $response[] = ['tickets total out' => $flight[0]['FlightNumber'], "date" => $flight[0]["Date"]];
            } else {
                $cabinType = $request->input("cabinType");
                $economyTickets = 0;
                $buisnessTickets = 0;
                $firstClassTickets = 0;
                foreach ($tickets as $ticket){
                    if($ticket['CabinTypeID'] == 1){
                        $economyTickets+=1;
                    } else if ($ticket['CabinTypeID'] == 2){
                        $buisnessTickets+=1;
                    }else if ($ticket['CabinTypeID'] == 3){
                        $firstClassTickets+=1;
                    }
                }
                if ($cabinType == 'Economy'){
                    if($aircraft["EconomySeats"] - $economyTickets < $request->input('passengers')){
                        $response[] = ['tickets' => false, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    } else {
                        $response[] = ['tickets' => True, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    }
                } else if ($cabinType == 'Business'){
                    if ($aircraft["BusinessSeats"] - $buisnessTickets < $request->input('passengers')){
                        $response[] = ['tickets' => false, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    } else {
                        $response[] = ['tickets' => True, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    }
                } else if ($cabinType == 'First Class'){
                    $firstClassSeats = $aircraft['TotalSeats'] - $aircraft['EconomySeats'] - $aircraft['BusinessSeats'];
                    if ($firstClassSeats - $firstClassTickets < $request->input('passengers')){
                        $response[] = ['tickets' => false, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    } else {
                        $response[] = ['tickets' => True, "date" => $flight[0]["Date"], 'flightNumber' => $flight[0]['FlightNumber']];
                    }
                }
            }

        }
        return Response($response, 200);
    }

//    TODO:
//      1)взять полеты между датами
//      2)взять только те полеты, которые удовлетворяют по аэропортам
//
//
//
//
//
//
//
}
