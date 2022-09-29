<?php

use App\Models\AgeGroup;

function CalculateAgeGroup($age){
    $group = null;
    if($age >= 18 && $age <= 24){
        $group = AgeGroup::getAgeGroupByScope("18-24");
    } else if ($age >= 25 && $age <= 39 ) {
        $group = AgeGroup::getAgeGroupByScope("25-39");
    } else if ($age >= 40 && $age <=59) {
        $group = AgeGroup::getAgeGroupByScope("40-59");
    } else if ($age >= 60){
        $group = AgeGroup::getAgeGroupByScope("60+");
    }
    return $group;
}
