<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller {

    protected $year;
    protected $month;

    public function __construct() {
        //check if the month or year are available in $_GET supergloblas
        if (isset($_GET['month']) && isset($_GET['year'])) {
            $this->year = $_GET['year'];
            $this->month = $_GET['month'];
        } else {
            //Current month 
            $date = new \DateTime('now');
            $this->month = $this->date->format('m');
            $this->year = $this->date->format('Y');
        }
    }

    protected function max_reading() {
        return DB::table('reading')
                        ->select('client_connection', DB::raw('MAX(date) as max_date'))
                        ->groupBy('client_connection');
    }

    /**
     * return a query string which contains a reading on the maximum date for a paticular 
     * connection 
     * NOTE this is a query string so methods such as get(), count() etc havent been
     * invoked on it
     * hence inner joins can be called on them
     */
    protected function max_reading_querystring() {
        return DB::table('reading')
                        ->select('reading.client_connection as connection')
                        ->joinSub($this->max_reading(), 'max', function($join) {
                            $join->on('max.client_connection', '=', 'reading.client_connection');
                            $join->on('max.max_date', "=", 'reading.date');
                        })
                        ->whereYear('date', $this->year)
                        ->whereMonth('date', $this->month);
    }

    //this will invoke a count on the querystring
    protected function readings_collected() {
        return $this->max_reading_querystring()->count();
    }

    //count of all client connections  
    protected function connection_count() {
        return DB::table('client_connection')->count();
    }

    //count of all unread clients for that month
    protected function unread_count() {
        return $this->connection_count() - $this->readings_collected();
    }

    //returns an object of the all clients in a particular zone
    protected function client_by_zones() {
        return DB::table('client')
                        ->select('zone.code', DB::raw('count(client.client) as count'))
                        ->join('client_connection', 'client.client', '=', 'client_connection.client')
                        ->join('zone', 'zone.zone', '=', 'client.zone')
                        ->groupBy('zone.code')
                        ->get();
    }

    //this returns an count of readings from a particular zone
    protected function reading_by_zones() {
        return DB::table('zone')
                        ->select('zone.code', 'description', DB::raw("count(max_reading.connection) as read_count"))
                        ->join('client', 'zone.zone', '=', 'client.zone')
                        ->join('client_connection', 'client.client', '=', 'client_connection.client')
                        ->joinSub($this->max_reading_querystring(), 'max_reading', function ($join) {
                            $join->on('max_reading.connection', '=', 'client_connection.client_connection');
                        })
                        ->groupBy('zone.code')
                        ->groupBy('description')
                        ->get();
    }

    //get the zone readings against their totals
    protected function flatten_zone_summaries() {
        $arr = $this->reading_by_zones();

        foreach ($this->client_by_zones() as $value) {
            foreach ($arr as $val) {
                if ($val->code == $value->code) {
                    $val->total = $value->count;
                }
                continue;
            }
        }
        return $arr;
    }

    /**
     * The below functions are associated with the user. i.e the data collector 
     * guy
     * We move them high the hierachy because they will be used in two instances.
     * To supplement the web view. and for the android page
     */
    //get user given the username
    protected function getUserByUsername($username) {
        return DB::table("user")
                        ->where("name", $username)
                        ->first();
    }

    //count of readings a user has taken that month
    protected function getMonthCount($user) {
        return DB::table('reading')
                        ->whereYear('date', $this->year)
                        ->whereMonth('date', $this->month)
                        ->where("user", $user)
                        ->count();
    }

    //total readings the user has ever collected. Since the beginning of time(lol)
    protected function totalReadingCount($user) {
        return DB::table('reading')
                        ->where('user', $user)
                        ->count();
    }

    //The count of readings for each day. for a user
    protected function dailyCount($user) {
        return DB::table('reading')
                        ->select(DB::raw('DATE(date) as date_read'), DB::raw('count(reading) as count'))
                        ->where('user', $user)
                        ->groupBy('date_read')
                        ->get();
    }

    //the gps count the user has taken
    protected function gpsCount($user) {
        return DB::table('reading')
                        ->whereNotNull('longitude')
                        ->whereNotNull('latitude')
                        ->where('user', $user)
                        ->count();
    }

    
    //the following functions are for driving the unread controller class
    //First thing. given year and month we return all connection and readings taken
    protected function getDistinctConnectionsReadings() {
        return DB::table("reading")
                        ->select('client_connection', 'reading')
                        ->where(DB::raw("YEAR(date)"), $this->year)
                        ->where(DB::raw("MONTH(date)"), $this->month)
                        ->groupBy('client_connection')
                        ->groupBy('reading');
    }
    
}
