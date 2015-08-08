<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 3:17 PM
 */

namespace App\APIs;

use App\Libs\ColdValidator as ColdValidator;
use App\Models\LoginSession;
use App\Models\User;

class AuthAPI extends BaseAPI
{
    public function postLogin() {
        list($email, $password)= ColdValidator::instance()->inputs(array(
            'email', 'password'
        ));
        $user = ColdValidator::instance()->login($email, $password);

        return LoginSession::newSession($user->id);
    }

    public function getLogin() {
    }

    public function postSignup() {
        list($email, $password) = ColdValidator::instance()->inputs(array(
            'email', 'password'
        ));
        ColdValidator::instance()->email($email);

        return LoginSession::newSession(User::newUser($email, $password)->id);
    }


    public function postValidate() {
        list($sessionKey) = ColdValidator::instance()->inputs(array('session_key'));

        return array('status' => LoginSession::where('session_key', $sessionKey)->count() > 0);
    }

    public function postHello() {
        return array('foo' => 'bar');
    }
}