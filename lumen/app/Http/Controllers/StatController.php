<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StatController extends HomeController {

    public function stats(Request $request) {

        /**
         * The request will contain a username and various queries will be based 
         * on that username.
         * The username will be sent from the android device so we are sure
         * it will exist. no need for try..catch blocks
         * We get the username from the request 
         */
        $username = $request->input("username");

        /**
         * get user from the username
         */
        $user = $this->getUserByUsername($username)->user;

        //create an empty array where we will store all the results we want 
        $result = Array();

        //This result is what will drive the stat page in the android application
        //the current data we need is defined below
        $result['monthly'] = $this->getMonthCount($user);
        $result['total_count'] = $this->totalReadingCount($user);
        $result['unread'] = $this->unread_count();
        $result['gps_count'] = $this->gpsCount($user);
        $result['zone'] = $this->flatten_zone_summaries();
        $result['daily'] = $this->dailyCount($user);

        return $result;
    }

    public function statView($username) {
        $user = $this->getUserByUsername($username);
        $user_id = $user->user; 
        return view('detail/reader', [
                                        'user'=>$user,
                                        'monthly'=> $this->getMonthCount($user_id), 
                                        'total'=> $this->totalReadingCount($user_id), 
                                        'gps_count'=> $this->gpsCount($user_id),
                                        'daily'=> $this->dailyCount($user_id)]);
    
        
    }

    public function zoneStat(){
        return $this->flatten_zone_summaries();
    }
}
