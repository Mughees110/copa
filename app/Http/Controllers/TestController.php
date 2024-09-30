<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    public function test(){
    	DB::statement('ALTER TABLE stories ADD type VARCHAR(255);');
    	dd('yes');
    }
}
