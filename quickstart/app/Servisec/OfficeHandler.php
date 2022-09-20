<?php

function pureOffices($offices){
    $pureOffice = [];
    foreach ($offices as $office){
        array_push($pureOffice, $office['Title']);
    }
    return $pureOffice;
}
