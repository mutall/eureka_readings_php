<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MonitorController extends Controller {
    
    private $date;
    private $year;
    private $month;
    
    
    
    public function __construct() {
        $this->date = new \DateTime('now');
        $this->month = $this->date->format('m');
        $this->year = $this->date->format('Y');
        
    }
    
    public function index() {
        //User stats
        $User_stats = DB::table('reading')
                        ->select(DB::raw('count(reading) as count'), 'name')
                        ->join('user', 'user.user', '=', 'reading.user')
                        ->whereYear('date', $this->year)
                        ->whereMonth('date', $this->month)
                        ->groupBy('name')->get();

        //table showings count per day for all users
        $source = DB::table('reading')
                ->select(DB::raw('count(reading) as count'), DB::raw('DATE(date) as grouped'), 'name')
                ->join('user', 'user.user', '=', 'reading.user')
                ->whereYear('date', $this->year)
                ->whereMonth('date', $this->month)
                ->groupBy('name')
                ->groupBy('grouped')
                ->get();

        $dest = [];

        foreach ($source as $record) {
            $this->save($dest, $record);
        }
        return view('home', ['read' => $this->readings_collected(),
            'clients' => $this->connection_count(),
            'unread' => $this->unread_count(),
            'stats' => $User_stats,
            'daily' => $dest,
            'date' => $this->date]);
    }

    public function save(&$dest, $record) {
        $tuple = new \stdClass();
        $tuple->date = $record->grouped;
        $tuple->count = $record->count;

        if (!array_key_exists($record->name, $dest)) {
            //create a new destination record and add the element as a tuple
            $dest[$record->name] = [];
        }
        array_push($dest[$record->name], $tuple);
    }

    
}