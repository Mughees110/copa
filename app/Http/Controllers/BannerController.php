<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners=Banner::where('clubId',$request->json('clubId'))->paginate(10);
        return response()->json(['status'=>200,'data'=>$banners]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            
            
            DB::beginTransaction();
            
            $banner=new Banner;
            $banner->text=$request->get('text');
            $video=Input::find('video');
            if(!empty($video)){
                $newFilename=time().$video->getClientOriginalName();
                $destinationPath='files';
                $video->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $banner->video=$picPath;
            }
            $image=Input::find('image');
            if(!empty($image)){
                $newFilename=time().$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath2='files/' . $newFilename;
                $banner->image=$picPath2;
            }
            $banner->clubId=$request->get('clubId');
            $banner->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$banner,'message'=>'Stored successfully']);

        } catch (\Exception $e) {
            Log::error('banner store failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'banner store failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            if(empty($id)){
                return response()->json(['status'=>401,'message'=>'id required']);
            }
            $banner=Banner::find($id);
            if(!$banner){
                return response()->json(['status'=>401,'message'=>'banner not exists']);
            }
            DB::beginTransaction();
            
            if($request->get('text')){
                $banner->text=$request->get('text');
            }
            if($request->get('clubId')){
                $banner->clubId=$request->get('clubId');
            }
            $video=Input::find('video');
            if(!empty($video)){
                $newFilename=time().$video->getClientOriginalName();
                $destinationPath='files';
                $video->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $banner->video=$picPath;
            }
            $image=Input::find('image');
            if(!empty($image)){
                $newFilename=time().$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath2='files/' . $newFilename;
                $banner->image=$picPath2;
            }
            $banner->save();
            DB::commit();

            return response()->json(['status'=>200,'data'=>$banner,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('banner update failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'banner update failed'.$e->getMessage(),
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
            $banner=Banner::find($id);
            if(!$banner){
                return response()->json(['status'=>401,'message'=>'banner not exists']);
            }
              
            $banner->delete();
          
            return response()->json(['status'=>200,'message'=>'Deleted successfully']);

        } catch (\Exception $e) {
            Log::error('banner delete failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'banner delete failed'.$e->getMessage(),
            ], 422);
        }
    }
}
