<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubs=Club::paginate(10);
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
            if(empty($request->get('title'))){
                return response()->json(['status'=>401,'message'=>'title required']);
            }
            
            DB::beginTransaction();
            
            $club=new Club;
            $club->title=$request->get('title');
            $club->description=$request->get('description');
            $club->toggle=$request->get('toggle');
            $club->link=$request->get('link');
            $image=Input::file("image");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $club->image=$picPath;
            }
            $files=Input::file("images");
            $paths=array();
            if(!empty($files)){
                foreach ($files as $key => $video) {
                    if(!empty($video)){
                        $newFilename=time().$video->getClientOriginalName();
                        $destinationPath='files';
                        $video->move($destinationPath,$newFilename);
                        $picPath='files/' . $newFilename;
                        $paths[$key]=$picPath;
                    }
                }
                $club->images=$paths;
            }
            
            $club->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$club,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('Club store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Club store failed'.$e->getMessage(),
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
            $club=Club::find($id);
            if(!$club){
                return response()->json(['status'=>401,'message'=>'club not exists']);
            }
            DB::beginTransaction();
            if($request->get('title')){
                $club->title=$request->get('title');
            }
            if($request->get('description')){
                $club->description=$request->get('description');
            }
            if($request->get('toggle')){
                $club->toggle=$request->get('toggle');
            }
            if($request->get('link')){
                $club->link=$request->get('link');
            }
            $image=Input::file("image");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $club->image=encrypt($picPath);
            }
            $files=Input::file("images");
            $paths=array();
            if(!empty($files)){
                foreach ($files as $key => $video) {
                    if(!empty($video)){
                        $newFilename=time().$video->getClientOriginalName();
                        $destinationPath='files';
                        $video->move($destinationPath,$newFilename);
                        $picPath='files/' . $newFilename;
                        $paths[$key]=$picPath;
                    }
                }
                $club->images=$paths;
            }
            
            $club->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$club,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('Club update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Club update failed'.$e->getMessage(),
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
            $club=Club::find($id);
            if(!$club){
                return response()->json(['status'=>401,'message'=>'club not exists']);
            }
              
            $club->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Club delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Club delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
