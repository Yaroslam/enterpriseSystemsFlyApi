<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmentiteTicketModel extends Model
{
    use HasFactory;
    protected $table = "amenitiestickets";

    public static function addAmetity($amintityID, $ticketID, $price){
        self::insert(['AmenityID' => $amintityID, "TicketID" => $ticketID, "Price" => $price]);
    }

    public static function deleteAmentity($amintityID){
        self::where('AmenityID', $amintityID)->delete();
    }

    public static function findAmentiteForTicket($ticketId){
        $amnetites = [];
        $a = self::where('TicketID', $ticketId)->get()->toArray();
        foreach ($a as $amen){
            $amnetites[] = Amentite::getById($amen['AmenityID'])[0]['Service'];
        }
        return $amnetites;
    }




}
