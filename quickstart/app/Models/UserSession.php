<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class UserSession extends Model
{
    protected $table = 'users_sessions';
    public $timestamps = false;

    protected $fillable = ['sessionLogin', 'sessionLogout', 'sessionTime', 'Userid'];

    public static function getUserSessions($id){
        $result = [];
        $userSessions = self::where([['UserId', $id]])->get()->toArray();

        foreach ($userSessions as $session){
            $unsucces = DB::table('unsuccess_sessions')->where('sessionId', $session["ID"])->get()->toArray();
            if (count($unsucces) > 0){
                $reason = DB::table('crashes')->where("ID", $unsucces[0]->crashId)->get()->toArray()[0]->crash;
            } else {
                $reason = "None";
            }
            $curSession = [
                "loginTime" => $session["sessionLogin"],
                "logoutTime" => $session["sessionLogout"],
                "sessionTime" => $session["sessionTime"],
                "reason" => $reason
            ];
            array_push($result, $curSession);
        }
        return $result;
    }

    public static function addSession($userId, $loginTime, $logoutTime, $success, $reason=-1) {
        if($success){
            self::insert(['UserId'       => $userId,
                          'sessionLogin' => $loginTime,
                          'sessionLogout' => $logoutTime,
                          'sessionTime' => strtotime($logoutTime) - strtotime($loginTime),
                          'success' => $success]);
        } else {
            $id = self::insertGetId(['UserId'       => $userId,
                          'sessionLogin' => $loginTime,
                          'sessionLogout' => $logoutTime,
                          'sessionTime' => -1,
                          'success' => $success]);
            DB::table('unsuccess_sessions')->insert(['sessionId' => $id, 'crashId' => $reason]);
        }
    }

}
