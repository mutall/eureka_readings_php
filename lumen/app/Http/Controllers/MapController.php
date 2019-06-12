<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MapController extends HomeController{
    
    private function averageCoordinates(){
        return DB::table('reading')
                ->select('client_connection', DB::raw('AVG(longitude) as longitude'), DB::raw('AVG(latitude) as latitude'))
                ->whereNotNull('longitude')
                ->groupBy('client_connection');
    }
    public function onlineCoordinates(){
        $unread_clients = DB::table('client_connection')
                            ->select('code', 'client_connection.client_connection')
                            ->join('client', 'client.client', '=', 'client_connection.client')
                            ->leftJoinSub($this->getDistinctConnectionsReadings($this->year, $this->month), 'query', function($join) {
                                $join->on('query.client_connection', '=', 'client_connection.client_connection');
                            })
                            ->where('query.reading', null)
                            ;
                            
        return DB::table('client_connection')
                            ->select('client.code', 'longitude', 'latitude')
                            ->join('client', 'client.client', '=', 'client_connection.client')
                            ->joinSub($this->averageCoordinates(), 'query', function($join){
                                $join->on('query.client_connection', '=', 'client_connection.client_connection');
                            })
                            ->joinSub($unread_clients, 'unread', function($join){
                                $join->on('unread.client_connection', '=', 'client_connection.client_connection');
                            })
                            ->get();
    }
    
    public function coordinates() {
        return DB::table('client_connection')
                            ->select('code', 'longitude', 'latitude')
                            ->join('client', 'client.client', '=', 'client_connection.client')
                            ->joinSub($this->averageCoordinates(), 'query', function($join){
                                $join->on('query.client_connection', '=', 'client_connection.client_connection');
                            })->get();
        
    }
    
    public function getErroneousCoordinates() {
        $data = DB::table("reading")
                ->select("reading", "code", "longitude", "latitude")
                ->join("client_connection", "client_connection.client_connection", "=", "reading.client_connection")
                ->join("client", "client.client", "=", "client_connection.client")
                ->whereNotNull('longitude')
                ->get();

        $dest = [];

        foreach ($data as $value) {
            $this->save($dest, $value);
        }
        return response()->json($dest);
//        return view('calculate', ['data' => json_encode($dest)]);
    }

    public function save(&$dest, $value) {
        $tuple = new \stdClass();
        $tuple->primary = $value->reading;
        $tuple->code = $value->code;
        $tuple->longitude = $value->longitude;
        $tuple->latitude = $value->latitude;

        if (!array_key_exists($value->code, $dest)) {
            $dest[$value->code] = [];
        }

        array_push($dest[$value->code], $tuple);
    }
    
    //This function serves to update all coordinates near mutall and set them to null
    public function remove(Request $request) {
        $decoded = $request->input('json');
        for ($index = 0; $index < count($decoded); $index++) {
            DB::table('reading')
                    ->where('reading', $decoded[$index])
                    ->update(['longitude' => null, 'latitude' => null, 'altitude' => null, 'accuracy' => null]);
        }
        
        return json_encode($decoded);
    }

}
