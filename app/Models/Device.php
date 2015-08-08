<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 7:03 PM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    public $timestamps = false;

    public static function newDevice($userId, $deviceName, $deviceToken) {
        $device = new Device();
        $device->user_id = $userId;
        $device->device_name = $deviceName;
        $device->device_token = $deviceToken;
        $device->created_at = Carbon::now()->toDateTimeString();
        $device->save();

        return $device;
    }

    public function disable() {
        if(!isset($this->id) || empty($this->id)) {
            return false;
        }
        $this->is_disabled = 1;
        $this->save();
        return $this;
    }

    public function isDisabled() {
        return isset($this->is_disabled) && $this->is_disabled == 1;
    }

    public function enable() {
        if(!isset($this->id) || empty($this->id)) {
            return false;
        }
        $this->is_disabled = 0;
        $this->save();
        return $this;
    }
}