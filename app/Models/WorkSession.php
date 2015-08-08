<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 4:57 PM
 */

namespace App\Models;


use App\Libs\ColdUtils;
use Illuminate\Database\Eloquent\Model;

class WorkSession extends Model
{
    protected $table = 'work_session';
    public $timestamps = false;

    const KEY_LENGTH = 25;

    public static function newSession($deviceId) {
        $session = new WorkSession();
        $session->session_key = ColdUtils::randomString(self::KEY_LENGTH);
        $session->device_id = $deviceId;
        $session->save();

        return $session;
    }
}