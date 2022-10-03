<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amentite;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmentitesController extends Controller
{
    public function getAmetitesForTicket(Request $request){
        //добавить проверку на дату
        $res = [];
        $free = [];
        $amentitires = [];
        $ticket = Ticket::where("ID", $request['id'])->get()->toArray();
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




}
