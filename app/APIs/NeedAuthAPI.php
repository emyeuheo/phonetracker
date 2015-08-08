<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 5:16 PM
 */

namespace App\APIs;


use App\Libs\ColdValidator;
use App\Models\LoginSession;
use App\Models\Device;

class NeedAuthAPI extends BaseAPI
{
    const INVALID_SESS_KEY = 101;
    private static $errorMsg = array(
        self::INVALID_SESS_KEY => 'Invalid session key'
    );

    public $isPublic = true;

    protected $currentSession;

    public function __construct() {
        list($sessionKey) = ColdValidator::instance()->inputs(array(
            'session_key'
        ));

        $session = LoginSession::getByKey($sessionKey);

        if(empty($session)) {
            throw new \Exception(self::$errorMsg[self::INVALID_SESS_KEY], self::INVALID_SESS_KEY);
        }

        $this->currentSession = $session;
    }

    protected function getDevice($deviceToken) {
        $device = Device::where('device_token', $deviceToken)->where('user_id', $this->currentSession->user_id)->first();

        if(empty($device) || $device->isDisabled()) {
            throw new \Exception('Device is not registered or disabled', 201);
        }

        return $device;
    }

    protected function getDeviceById($deviceId) {
        $device = Device::where('id', $deviceId)->where('user_id', $this->currentSession->user_id)->first();

        if(empty($device) || $device->isDisabled()) {
            throw new \Exception('Device is not registered or disabled', 201);
        }

        return $device;
    }

}