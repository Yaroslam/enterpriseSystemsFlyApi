<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    protected  $table = 'roles';
    public $timestamps = false;

    public static function getRoleNameById($id){
        return self::where("ID", $id)->get()->toArray()[0]['Title'];
    }

    public static function getRoleByName($name){
        return self::where("Title", $name)->get()->toArray();
    }

    public static function getAllRoles(){
        return self::all()->toArray();
    }







}
