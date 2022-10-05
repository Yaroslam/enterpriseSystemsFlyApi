<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Servisec\Classes\Graph;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function getAirportsCodes(){
        $res = [];
        $airports = Airport::getAllAirports();
        foreach ($airports as $airport){
            $res[] = $airport["IATACode"];
        }
        return $res;
    }

}
