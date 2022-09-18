<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;


class MonthSession extends Model
{
    protected $table = "month_session_time";


    public static function getSessionTime($userId){
        return self::where('UserId', $userId)->get()->toArray();
    }

    public static function startSession($userId, $startDate){
        self::insert(['UserId' => $userId, "sessionStart" => $startDate, 'spendTime' => 0, 'dropDate' => $startDate+30*24*60*60]);
    }

    public static function updateUserSessionPeriod($userId, $newDropDate){
        self::where('UserId', $userId)->update(["dropDate" => $newDropDate]);
    }

    public static function updateUserSessionTime($userId, $addTime){
        self::where('UserId', $userId)->update(["dropDate" => $addTime]);
    }

    public static function updateUserSessionStart($userId, $sessionStart){
        self::where('UserId', $userId)->update(["sessionStart" => $sessionStart]);
    }
}
