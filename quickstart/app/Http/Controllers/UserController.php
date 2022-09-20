<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Models\Crash;
use App\Models\inSystem;
use App\Models\MonthSession;
use App\Models\Office;
use App\Models\User;
use App\Models\Role;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $todayDate = time();

        $response = [];
        $codeStatus = 200;
        $user = User::getUserByEmail($request->input('email'));
        if($user->Active === 0){
            $response['error'] = 'user blocked';
            $codeStatus = 423;
        } elseif (!chekUserPassword($user, $request->input('password'))) {
            $response['error'] = 'invalid password';
            $codeStatus = 404;
        } elseif (count(inSystem::getUser($user->ID)) > 0) {
            $codeStatus = 403;
            $response['date'] = inSystem::getUser($user->ID)[0]['loginTime'];
            $response['crashReasons'] = Crash::getCrashesNames();
        } else {
            $monthSession = MonthSession::getSessionTime($user->ID);
            if (count($monthSession) === 0) {
                MonthSession::startSession($user->ID, $todayDate);
                $sessionTime = 0;
            } elseif (IsSessionExpired($todayDate, $monthSession[0]['dropDate'])) {
                $sessionTime = 0;
                MonthSession::updateUserSessionTime($user->ID, $sessionTime);
                MonthSession::updateUserSessionPeriod($user->ID, $todayDate + 30 * 24 * 60 * 60);
                MonthSession::updateUserSessionStart($user->ID, $todayDate);
            } else {
                $sessionTime = $monthSession[0]['spendTime'];
            }
            $sessions = UserSession::getUserSessions($user->ID);

            $response['role'] = Role::getRoleNameById($user->RoleID);
            $response['userName'] = $user->FirstName;
            $response['numberOfCrashes'] = countCrashes($sessions);
            $response['curSessionTime'] = $sessionTime;
            $response['sessions'] = $sessions;
            session(['email' => $user->Email, 'sessionStart' => $todayDate, 'spendTime' => $sessionTime]);
            inSystem::addUser($user->ID, date("Y-m-d H:i:s", $todayDate));
        }
        return Response($response, $codeStatus);
    }

    public function handleCrash(request $request){
        $user = User::getUserByEmail($request->input('email'));
        $crashReason = Crash::getCrashByName($request->input('crashReason'))[0]["ID"];
        $session = inSystem::getUser($user->ID);
        UserSession::addSession($user->ID, $session[0]['loginTime'], date("Y-m-d H:i:s", time()), false, $reason=$crashReason);
        inSystem::deleteUser($user->ID);
        return Response(["error" => "no"], 200);
    }

    public function logout(request $request) {
        $user = User::getUserByEmail(session('email'));
        $logoutTime = time();
        $sessionTime = $logoutTime - session('sessionStart');
        $monthSessionTime = session('spendTime') + $sessionTime;
        UserSession::addSession($user->ID, date("Y-m-d H:i:s", session('sessionStart')), date("Y-m-d H:i:s", $logoutTime), True);
        MonthSession::updateUserSessionTime($user->ID, $monthSessionTime);
        inSystem::deleteUser($user->ID);
        session()->flush();
    }

    public function changeBlockUser(Request $request){
        $user = User::where("Email", $request->input("email"))->firstOrFail();
        User::changeBlockUser($user->ID, $user->Active);
    }

    public function getUsersByOffice(Request $request){
        $officeName = $request['offices'];
        if($officeName == "all"){
            $response = userDataForAdmin(User::getAllUsers());

        } else {
            $officeId = Office::getOfficeByName($officeName)['ID'];
            $response = userDataForAdmin(User::getUsersByOffice($officeId));
        }
        return $response;
    }

    public function addUser(AddUserRequest $request){
        $errors = $request->validated();
        $response = refactorAddUserData([
            "Email" => $request->input('Email'),
            "Password" => $request->input('Password'),
            "FirstName" => $request->input('FirstName'),
            "LastName" => $request->input('LastName'),
            "OfficeID" => $request->input('OfficeID'),
            "Birthdate" => $request->input('Birthdate'),
        ]);
        User::addUser($response);
        return Response($errors, 200);
    }

    public function changeUserRole(Request $request){
        User::changeUserRole($request->input('role'), $request->input('email'));
        return Request([],200);
    }
}
