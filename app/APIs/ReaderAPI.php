<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/6/15
 * Time: 2:01 AM
 */

namespace App\APIs;


use App\Libs\ColdValidator;
use App\Models\CallLog;
use App\Models\SmsLog;
use App\Models\LocationLog;


class ReaderAPI extends NeedAuthAPI
{
    public function __construct() {
        parent::__construct();

        list($deviceToken) = ColdValidator::instance()->inputs(array('device_token'));
        $this->device = $this->getDevice($deviceToken);
    }

    public function postCallLog() {
        list($page) = ColdValidator::instance()->page();

        return array('data'=>CallLog::readLog($this->device->id, $page));
    }

    public function postSmsLog() {
        list($page) = ColdValidator::instance()->page();

        return array('data'=>SmsLog::readLog($this->device->id, $page));
    }

    public function postLocationLog() {
        list($page) = ColdValidator::instance()->page();

        return array('data'=>LocationLog::readLog($this->device->id, $page));
    }

    public function postLastCallLog() {
        $log = CallLog::where('device_id', $this->device->id)->orderBy('call_time', 'DESC')->first();
        if(empty($log)) {
            return array();
        }
        return $log;
    }
}