<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 7:02 PM
 */

namespace App\APIs;


use App\Libs\ColdValidator;
use App\Models\Device;

class DeviceAPI extends NeedAuthAPI
{
    public function postAdd() {
        list($deviceToken, $deviceName) = ColdValidator::instance()->inputs(array(
            'device_token', 'device_name'
        ));

        $device = Device::where('device_token', $deviceToken)->first();

        if(empty($device)) {
            return Device::newDevice($this->currentSession->user_id, $deviceName, $deviceToken);
        }

        if($device->user_id != $this->currentSession->user_id) {
            throw new \Exception('This device is being tracked by another ID', 302);
        } else {
            throw new \Exception('This device is being tracked by you', 303);
        }
    }

    public function postRemove() {
        list($deviceToken) = ColdValidator::instance()->inputs(array(
            'device_token'
        ));

        $device = Device::where('device_token', $deviceToken)
            ->where('user_id', $this->currentSession->user_id)
            ->where('is_disabled', 0)
            ->first();
        if(empty($device)) {
            throw new \Exception('Device not found', 301);
        }

        $device->delete();
        return $device;
    }

    public function postDisable() {
        list($deviceToken) = ColdValidator::instance()->inputs(array(
            'device_token'
        ));

        $device = Device::where('device_token', $deviceToken)->where('user_id', $this->currentSession->user_id)->first();
        if(empty($device)) {
            throw new \Exception('Device not found', 301);
        }

        $device->disable();
        return $device;
    }

    public function postList() {
        $userId = $this->currentSession->user_id;

        return array('data'=>Device::where('user_id', $userId)->get());
    }
}