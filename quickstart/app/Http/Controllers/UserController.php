<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Crash;
use App\Models\inSystem;
use App\Models\MonthSession;
use App\Models\User;
use App\Models\Role;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
//  TODO
//      1) получить список всех офисов
//      2) получить пользователей по id офсиса и получить офис по его названию
//      3) добавлнеие нового пользователя
//      4) смена роли пользователя
//      5) расчитать возраст пользователя

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
}
