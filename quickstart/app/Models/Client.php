<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';
    protected $fillable = [
        'name',
        'email',
        'surname',
        'secondName',
        'telephoneNumber'
    ];
    public $timestamps = false;



    public static function addClient($name, $surname, $secondName, $phoneNumber, $email){
        self::insert([
            'name' => $name,
            'surname' => $surname,
            'secondName' => $secondName,
            'email' => $email,
            'telephoneNumber' => $phoneNumber
        ]);
    }

    public static function deleteClient($id){
        self::where("id", $id)->delete();
    }

    public static function updateClient($id ,$name, $surname, $secondName, $phoneNumber, $email){
        self::where("id", $id)->update(
            [
                'name' => $name,
                'surname' => $surname,
                'secondName' => $secondName,
                'email' => $email,
                'telephoneNumber' => $phoneNumber
            ]
        );
    }

    public static function getAllClients(){
        return self::all()->toArray();
    }

    public static function findClient($name, $surname, $secondName){
        $res = [];
        $realtors = self::all()->toArray();
        foreach ($realtors as $realtor){
            if (levenshtein($name, $realtor['name']) <= 3 && levenshtein($surname, $realtor['surname']) <= 3 && levenshtein($secondName, $realtor['secondName']) <= 3){
                $res[] = $realtor;
            }
        }
        return $res;
    }


}
