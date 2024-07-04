<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
class ItemController extends Controller
{
    public function index($id)
    {
        $items=Item::where('clubId',$request->json('clubId'))->paginate(10);
        return response()->json(['status'=>200,'data'=>$clubs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            if(empty($request->get('name'))){
                return response()->json(['status'=>401,'message'=>'name required']);
            }
            
            DB::beginTransaction();
            
            $item=new Item;
            $item->name=$request->get('name');
            $item->type=$request->get('type');
            $item->price=$request->get('price');
            $item->clubId=$request->get('clubId');
            $image=Input::file("image");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $item->image=$picPath;
            }
            $item->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$item,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('Item store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Item store failed'.$e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $item=Item::find($id);
            if(!$item){
                return response()->json(['status'=>401,'message'=>'item not exists']);
            }
            DB::beginTransaction();
            if($request->get('name')){
                $item->name=$request->get('name');
            }
            if($request->get('type')){
                $item->type=$request->get('type');
            }
            if($request->get('price')){
                $item->price=$request->get('price');
            }
            if($request->get('clubId')){
                $item->clubId=$request->get('clubId');
            }
            $image=Input::file("image");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $item->image=encrypt($picPath);
            }
            
            $club->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$item,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('Item update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Item update failed'.$e->getMessage(),
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
            $item=Item::find($id);
            if(!$item){
                return response()->json(['status'=>401,'message'=>'item not exists']);
            }
              
            $item->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Item delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Item delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
