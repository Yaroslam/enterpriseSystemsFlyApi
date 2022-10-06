<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\TrustHosts;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\CabinType;
use App\Models\Office;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Ticket;
use App\Servisec\Classes\Graph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $res = [];
        $mult = [
            "Economy" => 1,
            "Business" => 1.35,
            "First Class" => 1.30
        ];
        if($request["advanced"]) {
            $schedule = Schedule::getFlightsBetweenDates($request['date']);
        } else {
            $schedule = Schedule::getScheduleByDate($request['date']);
        }
        $varRoutes = [];
        foreach ($schedule as $s){
            $r = Route::getRouteById($s["RouteID"]);
            $varRoutes[] = [Airport::getAirportCode($r["DepartureAirportID"]), Airport::getAirportCode($r["ArrivalAirportID"])];
        }

        $graph = $this->makeGraph(createGraph($varRoutes));
        $s = $request['from'];
        $d = $request['to'];
        $graph->printAllPathsInGrapg($s, $d);
        $flights = getFlightsFromGraph($schedule ,$graph->pathes);
        foreach ($flights as $flight){
            $flightNumbers = [];
            $price = 0;
            foreach ($flight as $f){
                $flightNumbers[] = [$f["FlightNumber"]];
                $price+= $f["EconomyPrice"];
            }
            $flightRes = [
                "from" => $request['from'],
                "to" => $request['to'],
                "Date" => $flight[0]["Date"],
                "Time" => $flight[0]["Time"],
                "FlightNumbers" =>  $flightNumbers,
                "Price" => (int)($price * $mult[$request['cabinType']]),
                "Stops" => count($flightNumbers)-1,
            ];
            $res[] = $flightRes;
        }
        return $res;
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

    public function createTickets(Request $request){
        $cabins = [
            "Economy" => 1,
            "Business" => 1.35,
            "First Class" => 1.30
        ];

        $flights = $request['flights'];
        $passengers = $request['passengers'];
        $price = 0;
        foreach ($passengers as $passenger){
            $reference = $passenger["firstName"][0].$passenger["lastName"][0].$passenger['country'][0].substr($passenger['phone'], 3, 3);
            $ticket = Ticket::getTicketByReference($reference);
            $i = 0;
            while(count($ticket) != 0){
                $i++;
                $reference = $passenger["firstName"][0].$passenger["lastName"][0].$passenger['country'][0].chr(65+$i).substr($passenger['phone'], 3, 2);
                $ticket = Ticket::getTicketByReference($reference);
            }
            foreach ($flights as $flight){
                $price += (int)(Ticket::createTicket(session("email"), $flight, $request['cabinType'], $passenger, $reference) * $cabins[$request['cabinType']]);
            }
        }
        return $price;
    }

    public function getTicketsByReference(Request $request){
        $res = [];
        $res["flights"] = [];
        $tickets = Ticket::getTicketByReference($request['reference']);
        foreach ($tickets as $ticket){
            $flight = Schedule::where("ID", $ticket["ScheduleID"])->get()->toArray();
            $res["flights"][] = ["flightNUmber" => $flight[0]["FlightNumber"],
                      "date" => $flight[0]["Date"],
                      'ticketID' => $ticket['ID']];
        }

        $cabinType = CabinType::where("ID", $tickets[0]['CabinTypeID'])->get()->toArray();
        $res["FullName"] = $tickets[0]['Firstname']." ".$tickets[0]['Lastname'];
        $res["Passport"] = $tickets[0]['PassportNumber'];
        $res["CabinType"] = $cabinType[0]["Name"];

        return $res;
    }


    public function getReport(){
        $workers = [];
        $passengers = [];
        $averageTime = 0;
        $flightsCount = 0;
        $days = [];
        $confirmed = 0;
        $unconfirmed = 0;
        $tickets = [];
        $date = date("Y-m-d");
        $flights = Schedule::getScheduleByStartDate($date);
        foreach ($flights as $flight){
            $flightsCount++;
            $averageTime += Route::getRouteById($flight["RouteID"])['FlightTime'];

            if ($flight['Confirmed'] == 1){
                $confirmed++;
            } else {
                $unconfirmed++;
            }


            if(key_exists($flight['Date'], $days)){
                $days[$flight['Date']]++;
            } else {
                $days[$flight['Date']] = 1;
            }

            if (count(Ticket::getFlightTickets($flight['ID'])) > 0){
                $tickets[] = Ticket::getFlightTickets($flight['ID']);
            }
        }
        foreach ($tickets as $ticket){
            foreach ($ticket as $t){
                $user = \App\Models\User::getuserById($t['UserID']);
                $office = Office::getOfficeById($user[0]['OfficeID']);
                if(key_exists($office[0]['Title'], $workers)){
                    $workers[$office[0]['Title']] +=1;
                } else {
                    $workers[$office[0]['Title']] = 1;
                }


                if(key_exists($t["PassportNumber"], $passengers)){
                    $passengers[$t["PassportNumber"]]+=1;
                } else {
                    $passengers[$t["PassportNumber"]] = 1;

                }

            }
        }

        $averageTime = $averageTime/$flightsCount;
        $max = [];
        asort($passengers);
        var_dump(array_key_last($passengers));
        for($i=0; $i<3;$i++) {
            $man = Ticket::getTicketByPassport(array_key_last($passengers))[0]['Firstname']." ".Ticket::getTicketByPassport(array_key_last($passengers))[0]['Lastname'];
            $max[$man] = array_pop($passengers);
        }
        return $workers ;
        //определить топ 3 самых прибыльных офисов (опоеделяется по пользователю, который офрмил билет), только вылетевшие рейсы и подтвержденные билеты
        //орпеделить количество пустых мест за 3 недели на каждую неделю
        //средняя цена билетов за 3 дня на каждый день





    }

}
