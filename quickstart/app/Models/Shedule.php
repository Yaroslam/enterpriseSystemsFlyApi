<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shedule extends Model
{
    use HasFactory;
    protected $table = "schedules";

    public static function getSchedule($from, $to, $outbound, $flight){
        $schedule = self::all();
        if($from){
            $schedule = $schedule->where();
        }
    }
}
