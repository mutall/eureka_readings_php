<?php

namespace App\Http\Controllers;

class FileController extends Controller {

    public function upload(){
        $arr = array_diff(scandir(getcwd()."/backup"), [".", ".."]);
        return view('upload', ['data'=>$arr]);
        
    }
    
}