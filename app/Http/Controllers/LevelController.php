<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class LevelController extends Controller
{
    public function index(Request $request)
    {
        $levels=Level::where('clubId',$request->json('clubId'))->paginate(10);
        return response()->json(['status'=>200,'data'=>$levels]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            
            
            DB::beginTransaction();
            
            $level=new Level;
            $level->price=$request->json('price');
            $level->points=$request->json('points');
            $level->spendings=$request->json('spendings');
            $level->paas=$request->json('paas');
            $level->clubId=$request->json('clubId');
            $level->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$level,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('level store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'level store failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $level=Level::find($id);
            if(!$level){
                return response()->json(['status'=>401,'message'=>'level not exists']);
            }
            DB::beginTransaction();
            if($request->json('price')){
                $level->price=$request->json('price');
            }
            if($request->json('points')){
                $level->points=$request->json('points');
            }
            if($request->json('spendings')){
                $level->spendings=$request->json('spendings');
            }
            if($request->json('paas')){
                $level->paas=$request->json('paas');
            }
            if($request->json('clubId')){
                $level->clubId=$request->json('clubId');
            }
            
            $level->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$level,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('Level update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Level update failed'.$e->getMessage(),
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
            $level=Level::find($id);
            if(!$level){
                return response()->json(['status'=>401,'message'=>'level not exists']);
            }
              
            $level->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Level delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Level delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
