<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 5:11 PM
 */

namespace App\Models;


use App\Libs\ColdUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    const KEY_LENGTH = 25;

    protected $table = 'login_session';
    public $timestamps = false;

    public static function newSession($userId) {
        $session = new LoginSession();
        $session->session_key = ColdUtils::randomString(self::KEY_LENGTH);
        $session->user_id = $userId;
        $session->created_at = Carbon::now()->toDateTimeString();
        $session->save();

        return $session;
    }

    public static function getByKey($sessionKey) {
        return LoginSession::where('session_key', $sessionKey)->first();
    }
}