<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    public function test(){
    	DB::statement('ALTER TABLE users ADD countryIsoCode VARCHAR(255);');
    	dd('yes');
    }
}
