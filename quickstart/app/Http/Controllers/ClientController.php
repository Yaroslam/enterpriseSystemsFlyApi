<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Carbon\Cli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function addClient($request){
        if ($request['email'] == "" && $request['phoneNumber'] == ""){
            return Response(['error' => "enter phoneNumber or email"]);
        }

        $validator = Validator::make($request->all(), [
            "email" => 'email|unique:clients'
        ]);

        if ($validator->fails()) {
            return  Response($validator->errors(), 400);
        }

        Client::addClient($request['name'], $request['surname'], $request['secondName'], $request['phoneNumber'], $request['email']);
        return Response([], 200);
    }

    public function deleteClient($request){
        Client::deleteClient($request['id']);
        return Response([], 200);
    }

    public function editClient($request){
        Client::updateClient($request['id'], $request['name'], $request['surname'], $request['secondName'], $request['phoneNumber'], $request['email']);
        return Response([], 200);
    }

    public function getAllClients(){
        return Client::getAllClients();
    }

}
