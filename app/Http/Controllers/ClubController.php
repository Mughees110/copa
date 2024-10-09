<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\User;
use App\Models\Like;
use App\Models\Level;
use App\Models\Calender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->json('userId');
        $dist = (float) $request->json('distance', 50);

        if (empty($userId)) {
            return response()->json(['status' => false, 'message' => 'userId is required']);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User does not exist']);
        }

        // Retrieve points and apply distance filter
        $latitudeTo = (float) $user->latitude;
        $longitudeTo = (float) $user->longitude;
        $clubs=Club::selectRaw('*, (
            3958.756 * 1.60934 * 
            2 * ASIN(SQRT(
                POW(SIN((radians(latitude) - radians(?)) / 2), 2) +
                COS(radians(?)) * COS(radians(latitude)) * POW(SIN((radians(longitude) - radians(?)) / 2), 2)
            ))
        ) AS distance', [$latitudeTo, $latitudeTo, $longitudeTo])
        ->having('distance', '<=', $dist)->get();

        foreach ($clubs as $key => $value) {
            $value->setAttribute('calenders',Calender::where('clubId',$value->id)->get());
        }
        return response()->json(['status'=>200,'data'=>$clubs]);
    }
    public function index2(Request $request)
    {
        $clubs=Club::paginate(10);
        return response()->json(['status'=>200,'data'=>$clubs]);
    }
    public function index3(Request $request)
    {
        $clubs=Club::all();
        foreach ($clubs as $key => $value) {
            $value->setAttribute('calenders',Calender::where('clubId',$value->id)->get());
        }
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
                $newFilename=time().$image->getClientOriginalName();
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
            $files3=Input::file("videos");
            $paths3=array();
            if(!empty($files3)){
                foreach ($files3 as $key3 => $video3) {
                    if(!empty($video3)){
                        $newFilename3=time().$video3->getClientOriginalName();
                        $destinationPath3='files';
                        $video3->move($destinationPath3,$newFilename3);
                        $picPath3='files/' . $newFilename3;
                        $paths3[$key3]=$picPath3;
                    }
                }
                $club->videos=$paths3;
            }
            $club->userId=$request->get('userId');
            $club->offDays=$request->get('offDays');
            $club->themeDayText=$request->get('themeDayText');

            $files2=Input::file("themeImages");
            $paths2=array();
            if(!empty($files2)){
                foreach ($files2 as $key2 => $video2) {
                    if(!empty($video2)){
                        $newFilename=time().$video2->getClientOriginalName();
                        $destinationPath='files';
                        $video->move($destinationPath,$newFilename);
                        $picPath2='files/' . $newFilename;
                        $paths2[$key2]=$picPath2;
                    }
                }
                $club->themeDayImages=$paths2;
            }
            $club->themeDayDate=$request->get('themeDayDate');
            $club->themeDayToggle=$request->get('themeDayToggle');
            $club->latitude=$request->get('latitude');
            $club->longitude=$request->get('longitude');
            $club->openTiming=$request->get('openTiming');
            $club->storiesEnabled=$request->get('storiesEnabled');
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
                $newFilename=time().$image->getClientOriginalName();
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
                
            }
            if (is_array($request->get('existingImages'))) {
                $paths = array_merge($request->get('existingImages'), $paths);

            }
            $club->images=$paths;
            if($request->get('offDays')){
                $club->offDays=$request->get('offDays');
            }
            $files3=Input::file("videos");
            $paths3=array();
            if(!empty($files3)){
                foreach ($files3 as $key3 => $video3) {
                    if(!empty($video3)){
                        $newFilename3=time().$video3->getClientOriginalName();
                        $destinationPath3='files';
                        $video3->move($destinationPath3,$newFilename3);
                        $picPath3='files/' . $newFilename3;
                        $paths3[$key3]=$picPath3;
                    }
                }
                
            }
            if (is_array($request->get('existingVideos'))) {
                $paths3 = array_merge($request->get('existingVideos'), $paths3);

            }
            $club->videos=$paths3;
            if($request->get('themeDayText')){
                $club->themeDayText=$request->get('themeDayText');
            }
            $files2=Input::file("themeImages");
            $paths2=array();
            if(!empty($files2)){
                foreach ($files2 as $key2 => $video2) {
                    if(!empty($video2)){
                        $newFilename=time().$video2->getClientOriginalName();
                        $destinationPath='files';
                        $video->move($destinationPath,$newFilename);
                        $picPath2='files/' . $newFilename;
                        $paths2[$key2]=$picPath2;
                    }
                }
                $club->themeDayImages=$paths2;
            }
            if($request->get('themeDayDate')){
                $club->themeDayDate=$request->get('themeDayDate');
            }
            if($request->get('themeDayToggle')){
                $club->themeDayToggle=$request->get('themeDayToggle');
            }
            if($request->get('latitude')){
                $club->latitude=$request->get('latitude');
            }
            if($request->get('longitude')){
                $club->longitude=$request->get('longitude');
            }
            if($request->get('openTiming')){
                $club->openTiming=$request->get('openTiming');
            }
            if($request->get('storiesEnabled')){
                $club->storiesEnabled=$request->get('storiesEnabled');
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
    public function getUsers(Request $request){
        $users=User::where('clubId',$request->json('clubId'))->where('role','employee')->get();
        return response()->json(['status'=>200,'data'=>$users]);
    }
    public function favourite(Request $request){
        $exists=Like::where('userId',$request->json('userId'))->where('clubId',$request->json('clubId'))->exists();
        if($exists==true){
            $find=Like::where('userId',$request->json('userId'))->where('clubId',$request->json('clubId'))->first();
            $find->delete();
            return response()->json(['status'=>200,'message'=>'Removed from favourites list']);
        }
        if($exists==false){
            $like=new Like;
            $like->userId=$request->json('userId');
            $like->clubId=$request->json('clubId');
            $like->save();
            return response()->json(['status'=>200,'message'=>'Added to favourites list']);
        }
    }
    public function getFavourites(Request $request){
        $likes=Like::where('userId',$request->json('userId'))->get();
        foreach ($likes as $key => $value) {
            $value->setAttribute('clubId',Club::find($value->clubId));
        }
        return response()->json(['status'=>200,'data'=>$likes]);
    }
    public function clubWithLevels(Request $request){
        $clubIds=array();
        $clus=Level::where('seasonId',$request->json('seasonId'))->select('clubId')->groupBy('clubId')->get();
        foreach ($clus as $key => $value) {
            array_push($clubIds,$value->clubId);
        }
        $clubs=Club::whereIn('id',$clubIds)->get();
        foreach ($clubs as $key => $valuec) {
            $levels=Level::where('clubId',$valuec->id)->where('seasonId',$request->json('seasonId'))->get();
            $valuec->setAttribute('levels',$levels);
        }
        return response()->json(['status'=>200,'data'=>$clubs]);
    }
    public function clubsSearch(Request $request){
        $clubs=Club::where('title','like','%'.$request->json('search').'%')->orWhere('description','like','%'.$request->json('search').'%')->get();
        return response()->json(['status'=>200,'data'=>$clubs]);
    }
    public function addToCalender(Request $request){
        $calender=new Calender;
        $calender->clubId=$request->json('clubId');
        $calender->date=$request->json('date');
        $calender->text=$request->json('text');
        $image=Input::file("image");
        if(!empty($image)){
            $newFilename=time().$image->getClientOriginalName();
            $destinationPath='files';
            $image->move($destinationPath,$newFilename);
            $picPath='files/' . $newFilename;
            $calender->image=$picPath;
        }
        $calender->save();
        return response()->json(['status'=>200,'message'=>'saved successfully']);

    }
    public function editInCalender(Request $request){
        $calender=Calender::find($request->json('calenderId'));
        if(!$calender){
                return response()->json(['status'=>401,'message'=>'calender item not exists']);
            }
        $calender->clubId=$request->json('clubId');
        $calender->date=$request->json('date');
        $calender->text=$request->json('text');
        $image=Input::file("image");
        if(!empty($image)){
            $newFilename=time().$image->getClientOriginalName();
            $destinationPath='files';
            $image->move($destinationPath,$newFilename);
            $picPath='files/' . $newFilename;
            $calender->image=$picPath;
        }
        $calender->save();
        return response()->json(['status'=>200,'message'=>'saved successfully']);

    }
    public function getCalender(Request $request){
        $calenders=Calender::where('clubId',$request->json('clubId'))->get();
        return response()->json(['status'=>200,'data'=>$calenders]);
    }
}
