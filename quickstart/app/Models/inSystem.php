<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;


class inSystem extends Model
{
    protected $table = "in_system";
    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'loginTime',
    ];

    public static function deleteUser($id){
        self::where('UserId', $id)->delete();
    }

    public static function addUser($id, $loginTime, $sessionTime){
        self::insert(['UserId' => $id, "loginTime" => $loginTime, "spendTIme" => $sessionTime]);
    }

    public static function getUser($id){
        return self::where('UserId', $id)->get()->toArray();
    }

    public static function getUserTime($id){
        return self::where("UserId", $id)->get()->toArray()[0]['spendTIme'];
    }
}
