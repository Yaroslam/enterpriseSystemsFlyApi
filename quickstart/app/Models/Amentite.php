<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amentite extends Model
{
    use HasFactory;
    protected $table = 'amenities';


    public static function getAll(){
        return self::all()->toArray();

    }
}
