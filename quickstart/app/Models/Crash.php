<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class Crash extends Model
{
    protected  $table = 'crashes';

    public static function getCrashesNames()
    {
        $names = [];
        $crashes = Crash::all();
        foreach($crashes as $crash)
        {
            array_push($names, $crash['crash']);
        }
        return $names;
    }

    public static function getCrashByName($crash){
        return self::where('crash', $crash)->get()->toArray();
    }




}
