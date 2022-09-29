<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AgeGroup;
use App\Models\Airport;
use App\Models\CabinType;
use App\Models\Gender;
use App\Models\Summary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SummaryController extends Controller
{
    public function loadSummaryFromFile(Request $request){

        $validator = Validator::make($request->all(), [ 'file' => 'mimes:csv']);
        if ($validator->fails()) {
            return  Response($validator->errors(), 400);
        }

        $request->file('file')->store('files');
        $csvFile = file($request->file('file'));
        echo "1";
        foreach ($csvFile as $line) {
            if(str_getcsv($line)[0] === "Departure"){
                continue;
            }
            $data = str_getcsv($line);
            Summary::loadFromFile($data[0], $data[1], $data[2], $data[3], $data[4],  $data[5],
                $data[6], $data[7], $data[8]);
        }
        return Response([],200);
    }

    public function getDefaultSummary(){
        $genders = Gender::getGenders();
        $ages = AgeGroup::getGroups();
        $cabins = CabinType::getAllCabins();
        $airports = Airport::getAllAirports();


    }
// TODO
//  5)вывод информации





}
