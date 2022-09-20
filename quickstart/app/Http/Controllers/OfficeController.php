<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function getOffices(Request $request){
        return pureOffices(Office::getAllOffices());
    }
}
