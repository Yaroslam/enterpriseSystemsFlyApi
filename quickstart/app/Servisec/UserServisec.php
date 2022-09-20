<?php

use App\Models\Office;
use App\Models\Role;
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

function calculate_age($birthday) {
    $birthday_timestamp = strtotime($birthday);
    $age = date('Y') - date('Y', $birthday_timestamp);
    if (date('md', $birthday_timestamp) > date('md')) {
        $age--;
    }
    return $age;
}


function userDataForAdmin($users){
    $res = [];
    foreach ($users as $user){
        $singleUser = [
            "name" => $user["FirstName"],
            "lastName" => $user["LastName"],
            "age" => calculate_age($user["Birthdate"]),
            "role" => User::getStaticUserRoleName($user["RoleID"]),
            "email" => $user['Email'],
            "office" => Office::getOfficeById($user["OfficeID"])[0]['Title'],
            "active" => $user['Active']
        ];
        array_push($res, $singleUser);
    }
    return $res;
}

function refactorAddUserData($userData){
    $userData["OfficeID"] = Office::getOfficeByName($userData["OfficeID"])[0]["ID"];
    return $userData;
}

