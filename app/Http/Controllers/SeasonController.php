<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class SeasonController extends Controller
{
    public function index(Request $request)
    {
        $seasons=Season::where('clubId',$request->json('clubId'))->paginate(10);
        return response()->json(['status'=>200,'data'=>$seasons]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            
            
            DB::beginTransaction();
            
            $season=new Season;
            $season->date=$request->json('date');
            $season->numberOfDays=$request->json('numberOfDays');
            
            $season->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$season,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('season store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'season store failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $season=season::find($id);
            if(!$season){
                return response()->json(['status'=>401,'message'=>'season not exists']);
            }
            DB::beginTransaction();
            if($request->json('date')){
                $season->date=$request->json('date');
            }
            if($request->json('numberOfDays')){
                $season->numberOfDays=$request->json('numberOfDays');
            }
            
            $season->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$season,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('season update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'season update failed'.$e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $season=Season::find($id);
            if(!$season){
                return response()->json(['status'=>401,'message'=>'season not exists']);
            }
              
            $season->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('season delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'season delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
