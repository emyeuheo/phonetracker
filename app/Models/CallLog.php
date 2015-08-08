<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/6/15
 * Time: 1:25 AM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class CallLog extends Model
{
    protected $table = 'call_log';
    public $timestamps = false;

    public static function newLog($deviceId, $phoneNumber, $contactName, $callDuration, $callType, $callTime) {
        $log = new CallLog();
        $log->device_id = $deviceId;
        $log->phone_number = $phoneNumber;
        $log->contact_name = $contactName;
        $log->call_duration = $callDuration;
        $log->call_type = $callType;
        $log->call_time = $callTime;
        $log->created_at = Carbon::now()->toDateTimeString();

        $log->save();

        return $log;
    }

    public static function readLog($deviceId, $page) {
        Paginator::currentPageResolver(function() use ($page){
            return $page;
        });

        return CallLog::where('device_id', $deviceId)->simplePaginate(config('custom.item_per_page'))->all();
    }

}