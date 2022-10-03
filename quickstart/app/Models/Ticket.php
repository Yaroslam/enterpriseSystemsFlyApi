<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = "tickets";
    protected $fillable = [
        "UserID",
        "ScheduleID",
        "CabinTypeID",
        "FirstName",
        "LastName",
        "Email",
        "Phone",
        "PassportNumber",
        "PassportCountryID",
        "BookingReference",
        "Confirmed"
    ];

    public static function getFlightTickets($flightId){
        return self::where("ScheduleID", $flightId)->get()->toArray();
    }

    public static function getTicketByReference($reference){
        return self::where("BookingReference", $reference)->get()->toArray();
    }

    public static function createTicket($email, $flight, $cabinType, $person, $reference){
        self::insert([
            "UserID" => User::getUserByEmailArrayFormat($email)[0]["ID"],
            "ScheduleID" => Schedule::getScheduleByDateAndFlightNumber($flight["flightNumber"], $flight["date"])[0]["ID"],
            "CabinTypeID" => CabinType::getCabinByName($cabinType)["ID"],
            "FirstName" => $person["firstName"],
            "LastName" => $person["lastName"],
            "Phone" => $person['phone'],
            "PassportNumber" => $person["passport"],
            "PassportCountryID" => Country::getCountryByName($person['country'])[0]["ID"],
            "BookingReference" => $reference,
            "Confirmed" => 1
        ]);
        return Schedule::getScheduleByDateAndFlightNumber($flight["flightNumber"], $flight["date"])[0]["EconomyPrice"];
    }

    public static function getTicketsBy30Days(){
        $date =  date("Y-m-d",strtotime(date("Y-m-d")) - 30*24*60*60);
        return self::where("");
    }
}
