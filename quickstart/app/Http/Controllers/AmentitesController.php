<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amentite;
use App\Models\AmentiteTicketModel;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmentitesController extends Controller
{
    public function getAmetitesForTicket(Request $request){
        $res = [];
        $free = [];
        $amentitires = [];
        $ticket = Ticket::where("ID", $request['id'])->get()->toArray();
        $flight = Schedule::getScheduleById($ticket[0]['ScheduleID']);
        $today = strtotime(date("Y-m-d"));

        if($today + 24*60*60 > strtotime($flight[0]['Date'])){
            return Response(['amenity edit lock'], 403);
        }
        if($today > strtotime($flight[0]['Date'])){
            return Response(['amenity edit lock'], 403);
        }



        $allAmentites = Amentite::getAll();
        $ticketAmentites = DB::table('amenitiestickets')->where("TicketID", $ticket[0]['ID'])->get()->toArray();
        $freeAmentitesForCabin = DB::table('amenitiescabintype')->where('CabinTypeID', $ticket[0]['CabinTypeID'])->get()->toArray();
        foreach ($freeAmentitesForCabin as $amentite){
            $amen = Amentite::where('ID', $amentite->AmenityID)->get()->toArray();
            $free[] = [
                'price' => $amen[0]['Price'],
                'name' => $amen[0]['Service'],
            ];
        }

        foreach ($allAmentites as $amentite){
            $buy = false;
            if(count(DB::table('amenitiestickets')->where("TicketID", $ticket[0]['ID'])->where('AmenityID', $amentite['ID'])
                ->get()->toArray()) > 0){
                $buy = true;
            }

            if(count(DB::table('amenitiescabintype')->where('CabinTypeID', $ticket[0]['CabinTypeID'])->where('AmenityID', $amentite['ID'])->get()->toArray()) > 0){
                $buy = true;
            }

            $amentitires[] = [
                'buy' => $buy,
                'name'=>  $amentite['Price'],
                'price' => $amentite['Service']
            ];
        }

        $res['amentite'] = $amentitires;
        $res['free'] = $free;
        return $res;

    }

    public function editAmentitesToTicket(Request $request){
        $ticket = Ticket::where("ID", $request['id'])->get()->toArray();
        $amentites = $request['amentites'];
        $ticketAmentites = DB::table('amenitiestickets')->where("TicketID", $ticket[0]['ID'])->get();
        $freeAmentitesForCabin = DB::table('amenitiescabintype')->where('CabinTypeID', $ticket[0]['CabinTypeID'])->get();
        foreach ($amentites as $amentite){
            $namedAmentite = Amentite::getByName($amentite['name']);
            if(count($freeAmentitesForCabin->where("AmenityID", $namedAmentite[0]['ID'])) > 0){
                continue;
            }  else {
                if($amentite['buy'] == false){
                    AmentiteTicketModel::deleteAmentity($namedAmentite[0]['ID']);
                } else {
                    if (count($ticketAmentites->where("AmenityID", $namedAmentite[0]['ID'])) == 0) {
                        AmentiteTicketModel::addAmetity($namedAmentite[0]['ID'], $ticket[0]['ID'], $namedAmentite[0]['Price']);
                    }
                }
            }
        }
    }


}
