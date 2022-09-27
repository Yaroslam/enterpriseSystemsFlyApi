<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CabinType;
use Illuminate\Http\Request;

class CabinTypesController extends Controller
{
    public function getCabinTypes(){
        $cabins = CabinType::getAllCabins();
        $cabinsName  = [];
        foreach ($cabins as $cabin){
            $cabinsName[] = $cabin["Name"];
        }
        return $cabinsName;
    }
}
