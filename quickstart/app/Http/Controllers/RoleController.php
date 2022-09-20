<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $table = "roles";

    public function getAllRoles(){
        return getRolesNames(Role::getAllRoles());
    }
}
