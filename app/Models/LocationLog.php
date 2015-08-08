<?php
/**
 * Created by PhpStorm.
 * User: macintosh
 * Date: 8/6/15
 * Time: 1:27 AM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;


class LocationLog extends Model
{
    protected $table = 'location_log';
    public $timestamps = false;

    public static function newLog($deviceId, $longitude, $latitude, $locationTime) {
        $log = new LocationLog();
        $log->device_id = $deviceId;
        $log->longitude = $longitude;
        $log->latitude = $latitude;
        $log->location_time = $locationTime;
        $log->created_at = Carbon::now()->toDateTimeString();

        $log->save();
        return $log;
    }

    public static function readLog($deviceId, $page) {
        Paginator::currentPageResolver(function() use ($page){
            return $page;
        });

        return LocationLog::where('device_id', $deviceId)->simplePaginate(config('custom.item_per_page'))->all();
    }
}