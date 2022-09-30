<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function getAnswers(){
        $res = [];
        $answers = Answer::getAllAnswers();
        foreach ($answers as $answer){
            $res[] = $answers['text'];
        }
        return $res;
    }
}
