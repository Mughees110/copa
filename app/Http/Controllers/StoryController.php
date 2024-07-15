<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
class StoryController extends Controller
{
   	public function index($id)
    {
        $stories=Story::where('clubId',$id)->paginate(10);
        return response()->json(['status'=>200,'data'=>$stories]);
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
            
            
            DB::beginTransaction();
            
            $story=new Story;
            $story->startingTime=$request->get('startingTime');
            $story->clubId=$request->get('clubId');
            $story->userId=$request->get('userId');
            $story->status=$request->get('status');
            
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
                $story->images=$paths;
            }

            $files2=Input::file("videos");
            $paths2=array();
            if(!empty($files2)){
                foreach ($files2 as $key2 => $video2) {
                    if(!empty($video2)){
                        $newFilename=time().$video2->getClientOriginalName();
                        $destinationPath='files';
                        $video2->move($destinationPath,$newFilename);
                        $picPath2='files/' . $newFilename;
                        $paths2[$key2]=$picPath2;
                    }
                }
                $story->videos=$paths2;
            }
            $story->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$story,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('Story store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Story store failed'.$e->getMessage(),
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
            $story=Story::find($id);
            if(!$story){
                return response()->json(['status'=>401,'message'=>'club not exists']);
            }
            DB::beginTransaction();
            if($request->get('startingTime')){
                $story->startingTime=$request->get('startingTime');
            }
            if($request->get('clubId')){
                $story->clubId=$request->get('clubId');
            }
            if($request->get('userId')){
                $story->userId=$request->get('userId');
            }
            if($request->get('status')){
                $story->status=$request->get('status');
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
                $story->images=$paths;
            }

            $files2=Input::file("videos");
            $paths2=array();
            if(!empty($files2)){
                foreach ($files2 as $key2 => $video2) {
                    if(!empty($video2)){
                        $newFilename=time().$video2->getClientOriginalName();
                        $destinationPath='files';
                        $video2->move($destinationPath,$newFilename);
                        $picPath2='files/' . $newFilename;
                        $paths2[$key2]=$picPath2;
                    }
                }
                $story->videos=$paths2;
            }
            $story->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$story,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('Story update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'Story update failed'.$e->getMessage(),
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
            $story=Story::find($id);
            if(!$story){
                return response()->json(['status'=>401,'message'=>'story not exists']);
            }
              
            $story->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Story delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Story delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
