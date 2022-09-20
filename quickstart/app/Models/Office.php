<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class Office extends Model
{
    protected $table = "offices";

    public static function getAllOffices(){
        return self::all('Title')->toArray();
    }

    public static function getOfficeByName($name){
        return self::where("Title", $name)->get()->toArray();
    }

    public static function getOfficeById($id){
        return self::where("ID", $id)->get()->toArray();
    }

}
