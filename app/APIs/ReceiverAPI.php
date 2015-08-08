<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/5/15
 * Time: 5:31 PM
 */

namespace App\APIs;

use App\Libs\ColdValidator;
use App\Models\CallLog;
use App\Models\SmsLog;
use App\Models\LocationLog;

class ReceiverAPI extends NeedAuthAPI
{
    public function __construct() {
        parent::__construct();

        list($deviceToken) = ColdValidator::instance()->inputs(array('device_token'));
        $this->device = $this->getDevice($deviceToken);
    }

    public function postCallLog() {
        list($phoneNumber, $contactName, $callType, $callTime)
            = ColdValidator::instance()->inputs(array(
            'phone_number', 'contact_name', 'call_duration', 'call_type', 'call_time'
        ));

        list($callDuration) = ColdValidator::instance()->numericOrDefault(array(
            'call_duration' => 0
        ));

        return CallLog::newLog($this->device->id, $phoneNumber, $contactName, $callDuration, $callType, $callTime);
    }

    public function postSmsLog() {
        list($phoneNumber, $text, $smsType, $smsTime)
            = ColdValidator::instance()->inputs(array(
            'phone_number', 'text', 'sms_type', 'sms_time'
        ));

        return SmsLog::newLog($this->device->id, $phoneNumber, $text, $smsType, $smsTime);
    }

    public function postLocationLog() {
        list($longitude, $latitude, $locationTime)
            = ColdValidator::instance()->inputs(array(
            'longitude', 'latitude', 'location_time'
        ));

        return LocationLog::newLog($this->device->id, $longitude, $latitude, $locationTime);
    }

}