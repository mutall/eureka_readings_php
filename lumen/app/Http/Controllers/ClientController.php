<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller{
    private $code;
    private $array;
    
    public function __construct(Request $request) {
        $this->array = [];
        if(isset($_SESSION['code'])){
            $this->code = $_SESSION['code'];
        }else{
            $this->code = $request->input('code');
        }
    }
    
    public function getClientDetails(){
       $this->array['details'] = $this->details();
       $this->array['payments'] = $this->getPaymentDetails();
       $this->array['contacts'] = $this->getContactDetails();
        
        // return $this->details();
       return response()->json($this->array);
    }
    
    //get all details from the client table
    private function details(){
        return DB::table('client')->where('code', $this->code)->get();
    }
    

    //get all payment details from the payments table
    private function getPaymentDetails(){
        return DB::table('payment')
                ->select(DB::raw('payment.*'))
                ->join('client', 'client.client', '=', 'payment.client')
                ->where('client.code', $this->code)
                ->get();
    }
    
    //get all contac details from the mobile table
    private function getContactDetails(){
        return DB::table('mobile')
                ->select('number')
                ->join('mobile_validation', 'mobile.mobile', '=', 'mobile_validation.mobile')
                ->join('client', 'client.client', '=', 'mobile_validation.client')
                ->where('client.code', $this->code)
                ->get();
    }
}
