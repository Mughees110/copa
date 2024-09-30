<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class PointController extends Controller
{
    public function store(Request $request)
    {
        
        try {
            
            
            DB::beginTransaction();
            
            $point=new Point;
            $point->userId=$request->json('userId');
            $point->points=$request->json('points');
            $point->paas=$request->json('paas');
            $point->clubId=$request->json('clubId');
            $point->itemIds=$request->json('itemIds');
            $point->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$point,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('point store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'point store failed'.$e->getMessage(),
            ], 422);
        }
    }
    public function overallSum(Request $request){
    	$sum=0;
    	$points=Point::where('userId',$request->json('userId'))->get();
    	foreach ($points as $key => $value) {
    		$sum=$sum+$value->points;
    	}
    	return response()->json(['status'=>200,'data'=>$sum]);
    }
    public function pointsAgainstCp(Request $request){
    	$sum=0;
    	$points=Point::where('paas',$request->json('paas'))->where('clubId',$request->json('clubId'))->where('userId',$request->json('userId'))->get();
    	foreach ($points as $key => $value) {
    		$sum=$sum+$value->points;
    	}
    	return response()->json(['status'=>200,'data'=>$sum]);
    }
    public function getTopUsers(Request $request){
    	$res=Point::where('clubId',$request->json('clubId'))->where('paas',$request->json('paas'))->select('userId', DB::raw('SUM(points) as total_points'))
            ->groupBy('userId')
            ->orderByDesc('total_points')
            ->limit(20)
            ->get();
        foreach ($res as $key => $value) {
        	$value->setAttribute('user',User::find($value->userId));
        }
        return response()->json(['status'=>200,'data'=>$res]);

    }
}
