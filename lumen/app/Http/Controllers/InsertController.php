<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsertController extends Controller
{

    public function reading(Request $request)
    {
        
        $reading = json_decode($request->input('json'));
        $name = $request->input("user");
        $this->write_json_to_file($reading, $name);
        $this->upload($reading);
        
    }
    
    //function for inserting a single reading
    //we dont need to backup that single reading
    public function single(Request $request) {
        $reading = json_decode($request->input('json'));
        $this->upload($reading);
    }
    //upload json data 
    
    private function upload($data){
        foreach ($data as $key):

            $code = $key->code;
            $date = $key->date;
            $value = $key->value;
            $longitude = $key->longitude;
            $latitude = $key->latitude;
            $altitude = $key->altitude;
            $accuracy = $key->accuracy;
            $user = $key->user;

            //we first get the connection given the code
            $connection = DB::table("client_connection")
                ->select('client_connection')
                ->join('client', 'client.client', '=', 'client_connection.client')
                ->where('client.code', $code)->first();
            
            //do the actual insert
            try {
                DB::table('reading')->insert([
                    'client_connection' => $connection->client_connection,
                    'date' => $date,
                    'value' => $value,
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'altitude' => $altitude,
                    'accuracy' => $accuracy,
                    'user' => $user]);
            } catch (Exception $e) {
                
                continue;
            }
        endforeach;
        return "success";
    }
    
    //save to a file
    private function write_json_to_file($data, $name="json"){
        //append a timestamp on the file to distinguish them
        $date = new \DateTime('now');
        $str = $date->format('Y-m-d_H:i:s');
        
        //create the file and set it to writable
        $json_file = fopen(getcwd()."/backup/".$name."_".$str.".json", "w"); 
        
        //write to file
        fwrite($json_file, json_encode($data));
        
        //close the file
        fclose($json_file);
    }
    
    
    public function json(Request $request) {
        //get filename from requeest
        $filename = $request->input('filename');
        //get the directory where the file exists
        $directory = getcwd()."/backup/";
        //get contents from file
        $json = file_get_contents($directory.$filename);
        //decode the contents
        $decoded_data = json_decode($json);
        //upload the data
        return $this->upload($decoded_data);
    }
 }
