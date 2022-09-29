<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AgeGroup;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    public function getAllAgeGroup(){
        $ageGroups = AgeGroup::getGroups();
        $res = [];
        foreach ($ageGroups as $age){
            $res[] = $age["scope"];
        }
        return $res;
    }



}
