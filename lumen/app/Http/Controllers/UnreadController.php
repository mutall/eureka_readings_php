<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UnreadController extends HomeController{

    public function getUnreadClients($year, $month) {
        $sql = DB::table('client_connection')
                ->select('code', 'full_name')
                ->join('client', 'client.client', '=', 'client_connection.client')
                ->leftJoinSub($this->getDistinctConnectionsReadings($year, $month), 'query', function($join) {
                    $join->on('query.client_connection', '=', 'client_connection.client_connection');
                })
                ->where('query.reading', null)
                ->get();

        
        return view('unread', ['data' => $sql, 'count' => $this->unread_count()]);
    }

    public function sendUnreadClients($year, $month){
        $query = DB::table("reading")
                ->select('client_connection', 'reading')
                ->where(DB::raw("YEAR(date)"), $year)
                ->where(DB::raw("MONTH(date)"), $month)
                ->groupBy('client_connection')
                ->groupBy('reading');
        
        $message = ". Our water reading team has not been able to access your meter "
                . "for this month. Please assist us by sending the current meter reading. "
                . "Thank you. Eureka Water Limited ";
        
        $sql = DB::table('client_connection')
                ->select(DB::raw('number as mobile'), DB::raw("Concat('Dear ', full_name, ', ' ,code, '$message' ) as sms"))
                ->join('client', 'client.client', '=', 'client_connection.client')
                ->join('mobile_validation', 'client.client' ,'=', 'mobile_validation.client')
                ->join('mobile', 'mobile.mobile' ,'=', 'mobile_validation.mobile')
                ->leftJoinSub($query, 'query', function($join) {
                    $join->on('query.client_connection', '=', 'client_connection.client_connection');
                })
                ->where('query.reading', null)
                ->get();
                
        return $sql;
    }
    

}
