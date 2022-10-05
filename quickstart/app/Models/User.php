<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;


class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'Active',
        'RoleID'
    ];
    public $timestamps = false;

    public static function getUserByEmail(string $email)
    {
        return self::where("Email", $email)->firstOrFail();
    }

    public static function getUserByEmailArrayFormat($email){
        return self::where("Email", $email)->get()->toArray();
    }

    public function getUserRoleName()
    {
        return Role::getRoleNameById($this->ID);
    }

    public static function getStaticUserRoleName($id)
    {
        return Role::getRoleNameById($id);
    }

    public static function changeBlockUser($id, $active){
        self::where("ID", $id)->update(['Active' => (int)!$active]);
    }

    public static function changeUserRole($roleName, $userEmail){
        $roleId = Role::getRoleByName($roleName)[0]['ID'];
        self::where("Email", $userEmail)->update(['RoleID' => $roleId]);
    }

    public static function getAllUsers(){
        return self::all()->toArray();
    }

    public static function getUsersByOffice($officeId){
        return self::where('OfficeID', $officeId)->get()->toArray();
    }

    public static function addUser($userData){
        self::insert([
            "RoleID" => 2,
            "Email" => $userData['Email'],
            "Password" => $userData['Password'],
            "FirstName" => $userData['FirstName'],
            "LastName" => $userData['LastName'],
            "OfficeID" => $userData['OfficeID'],
            "Birthdate" => $userData['Birthdate'],
            "Active" => 1,
        ]);
    }

    public static function getuserById($id){
        return self::where("ID", $id)->get()->toArray();
    }

}
