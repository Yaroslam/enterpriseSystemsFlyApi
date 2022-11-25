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

    public static function getByName($name){
        return self::where('Service', $name)->get()->toArray();
    }

    public static function getById($id){
        return self::where("ID", $id)->get()->toArray();
    }

}
