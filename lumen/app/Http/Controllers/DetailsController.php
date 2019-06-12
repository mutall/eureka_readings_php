<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailsController extends HomeController {

    public function client() {
        /**
         * Here we get the minimum client details that is
         * code, full name, zone, supply meter
         */
        $connections = DB::table('client_connection')
                ->select('client', 'client_connection');

        //get the date and value based on the max readings
        $maxed_reading = DB::table('reading')
                ->select('client', 'maxdate.max_date as date', 'reading.value as value')
                ->join('client_connection', 'client_connection.client_connection', '=', 'reading.client_connection')
                ->joinSub($this->max_reading(), 'maxdate', function ($join) {
            $join->on('maxdate.client_connection', '=', 'reading.client_connection');
            $join->on('maxdate.max_date', '=', 'reading.date');
        });

        $client = DB::table('client')
                ->select('client.code', 'full_name', 'supply_meter.description as supply', 'zone.description as zone', 'maxed_reading.date', 'maxed_reading.value')
                ->leftJoin('supply_meter', 'client.supply_meter', '=', 'supply_meter.supply_meter')
                ->leftJoin('zone', 'zone.zone', '=', 'client.zone')
                ->joinSub($connections, 'connection', function ($join) {
                    $join->on('connection.client', '=', 'client.client');
                })
                ->joinSub($maxed_reading, 'maxed_reading', function ($join) {
                    $join->on('maxed_reading.client', '=', 'client.client');
                })
                ->get();
        return response()->json($client);
    }

    public function zone(Request $request) {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $zone_code = $request->input('zone_code');

        //zone info messages
        $results = DB::table('client')
                ->select('client.code', 'mobile.number', DB::raw("CONCAT('Eureka Waters wishes to inform you that we will be visiting ',
            zone.description,
            ' area on', ' $start' ,' to ', '$end ', 'for water meter readings' ) as message"))
                ->join('zone', 'zone.zone', '=', 'client.zone')
                ->join('mobile_validation', 'client.client', '=', 'mobile_validation.client')
                ->join('mobile', 'mobile.mobile', '=', 'mobile_validation.mobile')
                ->where('zone.code', '=', "$zone_code")
                ->get();

        return response()->json($results);
    }

    public function user(Request $request) {
        $data = json_decode($request->input("json"));
        if ($data->type == 'all') {
            return response()->json(DB::table('user')->get());
        }
        return response()->json(["Error" => 'Unauthorized request'], 403);
    }
    
    //This function is used to get a user reading details as he/she types in an
//    input box of some sort. 
//    will be used in the android app and also in the web view(give it some thought)
    public function reading(Request $request) {
        $hint = $request->input('code');
        $return = DB::table('reading')
                        ->select('client.full_name', 'supply_meter.description as supply', 'zone.description as zone', 'date', 'value', 'user.name')
                        ->join('user', 'user.user', '=', 'reading.user')
                        ->join('client_connection', 'client_connection.client_connection', '=', 'reading.client_connection')
                        ->join('client', 'client.client', '=', 'client_connection.client')
                        ->join('zone', 'zone.zone', '=', 'client.zone')
                        ->join('supply_meter', 'supply_meter.supply_meter', '=', 'client.supply_meter')
                        ->joinSub($this->max_reading(), 'max', function($join) {
                            $join->on('max.client_connection', '=', 'reading.client_connection');
                            $join->on('max.max_date', "=", 'reading.date');
                        })
                        ->where('client.code', 'like', "$hint%")->first();


        return response()->json($return);
    }
    
    
}
