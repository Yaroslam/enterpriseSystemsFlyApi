<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answers';

    public static function getAllAnswers(){
        return self::all()->toArray();
    }

    public static function getAnswerByCode($code){
        return self::where('code', $code)->get()->toArray();
    }

}
