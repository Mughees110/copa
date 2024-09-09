<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class RegulatorController extends Controller
{
    public function index(Request $request)
    {
        $regulators=Regulator::where('clubId',$request->json('clubId'))->paginate(10);
        return response()->json(['status'=>200,'data'=>$regulators]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            
            
            DB::beginTransaction();
            
            $regulator=new regulator;
            $regulator->price=$request->json('price');
            $regulator->points=$request->json('points');
            $regulator->clubId=$request->json('clubId');
            $regulator->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$regulator,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('regulator store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'regulator store failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $regulator=Regulator::find($id);
            if(!$regulator){
                return response()->json(['status'=>401,'message'=>'regulator not exists']);
            }
            DB::beginTransaction();
            if($request->json('price')){
                $regulator->price=$request->json('price');
            }
            if($request->json('points')){
                $regulator->points=$request->json('points');
            }
            if($request->json('clubId')){
                $regulator->clubId=$request->json('clubId');
            }
            
            $regulator->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$regulator,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('regulator update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'regulator update failed'.$e->getMessage(),
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
            $regulator=Regulator::find($id);
            if(!$regulator){
                return response()->json(['status'=>401,'message'=>'regulator not exists']);
            }
              
            $regulator->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('regulator delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'regulator delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
