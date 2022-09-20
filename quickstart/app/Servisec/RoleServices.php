<?php

function getRolesNames($roles){
    $res = [];
    foreach ($roles as $role){
        array_push($res, $role["Title"]);
    }
    return $res;
}

