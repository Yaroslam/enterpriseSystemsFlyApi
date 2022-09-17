<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Crash;
use App\Models\inSystem;
use App\Models\User;
use App\Models\Role;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(){
        $users = new User();
        $u = $users->GetById(12);
        foreach($u as $us){
            var_dump($us->ID);
        }
    }


    public function login(Request $request)
    {
        $response = [];
        $codeStatus = 200;
        $user = User::getUserByEmail($request->input('email'));

//        TODO:
//          1) юзер заблочен -> статус код и ошибка
//          2) юзер ввел неверный парол -> статус код и ошибка +
//          3) юзер все еще в сессии -> статус код, {role: string, date: string, login_time:string, object} object = [string, string …] -> {crash_reason:string}
//          4) все нормально -> статус код, {role:string, user_name: string, name:string, number_of_craches: int, object} object = [{date: string, login_time:string, logout_time:string, session_time:string, reason:string}, {}, {}, {}]



        if(!chekUserPassword($user, $request->input('password')))
        {
            $response['error'] = 'invalid password';
            $codeStatus = 404;
        } else {
            if(count(inSystem::getUser($user->ID)) > 0){
                $codeStatus = 403;
                $response['role'] = Role::getRoleNameById($user->RoleID);
//                $response['date'] =
//                $response['loginTime'] =
                $response['crashReasons'] = Crash::getCrashesNames();
            } else {
//                TODO:
//                  1) получить все сессии пользоватея +
//                  2) выделить из них сесии с неудачным выходом +
//                  3) посчитать количество неудчаных сессий +
//                  4) сформировать респонс массив
//                  5) добавть пользователя в таблицу активности +
//                  6) начать сесиию пользователя
//                  7) добвить в сесиию пользователя емаил, время начала
            $sessions = UserSession::getUserSessions($user->ID);
            $crashes = countCrashes($sessions);
            $response['role'] = Role::getRoleNameById($user->RoleID);
//                inSystem::addUser($user->ID);
            }
        }
        return Response($response, $codeStatus);
    }
}
