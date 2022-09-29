<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Summary;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function loadSummaryFromFile(Request $request){
        $request->file('file')->store('files');
        $csvFile = file($request->file('file'));
        echo "1";
        foreach ($csvFile as $line) {
            var_dump(str_getcsv($line));
            if(str_getcsv($line)[0] === "Departure"){
                continue;
            }
            $data = str_getcsv($line);
            var_dump($data);
//            Summary::loadFromFile($data[0], $data[1], $data[2], $data[3], $data[4],  $data[5],
//                $data[6], $data[7], $data[8]);
        }
    }
// TODO
//  4)запись ответов опрошенных
//  5)вывод информации





}
