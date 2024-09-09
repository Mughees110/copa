<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    public function test(){
    	DB::statement('ALTER TABLE users ADD latitude VARCHAR(255);');
    	DB::statement('ALTER TABLE users ADD longitude VARCHAR(255);');
    	DB::statement('ALTER TABLE users ADD coins VARCHAR(255);');
    	dd('yes');
    }
}
