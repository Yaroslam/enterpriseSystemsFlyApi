<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstateController extends Controller
{
    public function getAllEstate(Request $request){
        $res = [];
        if($request['type'] == 'all'){
            if($request['address'] != ''){

            } else {

            }
        } else if ($request['type'] == 'house') {
            if($request['address'] != ''){

            } else {

            }
        } else if ($request['type'] == 'apartment') {
            if($request['address'] != ''){

            } else {

            }
        }  else if ($request['type'] == 'land') {
            if($request['address'] != ''){

            } else {

            }
        }
    }

    public function createEstate(Request $request){

    }

    public function deleteEstate(Request $request){

    }

    public function editEstate(Request $request){

    }


    public function findEstate(Request $request){

    }

    public function getEstateDistrict(Request $request){

    }

}
