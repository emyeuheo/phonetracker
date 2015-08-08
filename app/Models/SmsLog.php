<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/6/15
 * Time: 1:26 AM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;


class SmsLog extends Model
{
    protected $table = 'sms_log';
    public $timestamps = false;

    public static function newLog($deviceId, $phoneNumber, $text, $smsType, $smsTime) {
        $log = new SmsLog();
        $log->device_id = $deviceId;
        $log->phone_number = $phoneNumber;
        $log->text= $text;
        $log->sms_type = $smsType;
        $log->sms_time = $smsTime;
        $log->created_at = Carbon::now()->toDateTimeString();

        $log->save();
        return $log;
    }

    public static function readLog($deviceId, $page) {
        Paginator::currentPageResolver(function() use ($page){
            return $page;
        });

        return SmsLog::where('device_id', $deviceId)->simplePaginate(config('custom.item_per_page'))->all();
    }
}