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

//    public function all(){
//
//        $graph = ['AC'=> ['B', 'C'],
//            'B'=> ['C', 'D'],
//            'C'=> ['D'],
//            'D'=> ['C', "F"],
//            'E'=> ['F'],
//            'F'=> ['C']];
//        $g = new Graph(count($graph));
//        foreach (array_keys($graph) as $k){ //$k = $i
//            foreach ($graph[$k] as $e){
//                $g->addEdgr($k, $e);
//            }
//        }
//        $g->printAllPathsInGrapg("AC", "F");
//        var_dump($g->pathes);
//        var_dump(123);
//    }



}
