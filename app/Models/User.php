<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;
    protected $hidden = array('password');

    public static function newUser($email, $password) {
        $user = new User;
        $user->email = $email;
        $user->password = md5($password);
        $user->created_at = Carbon::now()->toDateTimeString();

        $user->save();

        return $user;
    }

    public static function isExistedEmail($email) {
        return User::where('email', $email)->count() > 0;
    }

    public static function getByEmailPassword($email, $password) {
        return User::where('email', $email)->where('password', md5($password))->first();
    }

}