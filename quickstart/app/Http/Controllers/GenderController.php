<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function getAllGenders(){
        $genders = Gender::getGenders();
        $res = [];
        foreach ($genders as $gender) {
            $res[] = $gender['name'];
        }
        return $res;
    }
}
