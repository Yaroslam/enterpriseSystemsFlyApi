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
    public function index(): void
    {
        $users = new User();
        $u = $users->GetById(12);
        foreach($u as $us){
            var_dump($us->ID);
        }
    }


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
            inSystem::deleteUser($user->ID);
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
        $crashReason = $request->input('crashReason');
        $session = inSystem::getUser($user->ID);



    }



    public function logout(request $request) {




    }
}
