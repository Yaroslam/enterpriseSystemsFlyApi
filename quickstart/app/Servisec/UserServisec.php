<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;


function chekUserPassword(User $user, string $password)
{
    return $user['Password'] == $password;
}

function checkUserActivity(User $user)
{
    return $user['Activity'] == 0;
}

function countCrashes($sessions){
    $crashes = 0;
    foreach($sessions as $session){
        if($session['reason'] != "None"){
            $crashes+=1;
        }
    }
    return $crashes;
}

