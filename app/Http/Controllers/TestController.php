<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    public function test(){
    	DB::statement('ALTER TABLE clubs ADD videos LONGTEXT;');
    	dd('yes');
    }
}
