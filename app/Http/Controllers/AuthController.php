<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Input;
use Mail;
class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        try {
            /*if(empty($request->json('name'))||empty($request->json('email'))||empty($request->json('password'))||empty($request->json('phone'))||empty($request->json('role'))){
                return response()->json(['status'=>401,'message'=>',name , email, role, phone and password are required']);
            }*/
            
            $exists=User::where('email',$request->get('email'))->exists();
            if($exists==true){
                return response()->json(['status'=>401,'message'=>'Email already exists']);
            }
            DB::beginTransaction();
            
            $user=new User;
            $user->name=$request->get('name');
            $user->surname=$request->get('surname');
            $user->username=$request->get('username');
            $user->country=$request->get('country');
            $user->dateOfBirth=$request->get('dateOfBirth');
            $user->email=$request->get('email');
            $user->password=Hash::make($request->get('password'));
            $user->passwordD=$request->get('password');
            $user->phone=$request->get('phone');
            $user->role=$request->get('role');
            $user->companyName=$request->get('companyName');
            $user->address=$request->get('address');
            $user->city=$request->get('city');
            $user->postalCode=$request->get('postalCode');
            $user->fullName=$request->get('fullName');
            $user->iban=$request->get('iban');
            $user->clubId=$request->get('clubId');
            $user->countryIsoCode=$request->get('countryIsoCode');
            if($request->get('role')=="employee"){
                $user->employId=rand(1111,9999);
            }
            
            $image=Input::file("file");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $user->file=$picPath;
            }
            $user->photo=$request->get('photo');
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            DB::commit();

            return response()->json(['status'=>200,'token'=>$token,'data'=>$user,'message'=>'Registered successfully']);

        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'User registration failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            if(empty($request->json('email'))||empty($request->json('password'))){
                return response()->json(['status'=>401,'message'=>'Email and password are required']);
            }
            
            $user = User::where('email', $request->json('email'))->first();

            if (!$user || !Hash::check($request->json('password'), $user->password)) {
                return response()->json([
                    'message' => 'The credentials are invalid',
                ], 422);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            
            $club=null;
            $existC=Club::where('userId',$user->id)->exists();
            if($existC==true){
                $club=Club::where('userId',$user->id)->first();
            }
            return response()->json([
    'status' => 200,
    'token' => $token,
    'data' => $user,
    'club' => $club
])->header('Access-Control-Allow-Origin', '*')
  ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
  ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');


        } catch (\Exception $e) {
            // Log the error
            Log::error('User Login failed: ' . $e->getMessage());
            // Return a JSON response with an error message
            return response()->json([
                'message' => 'User login failed'.$e->getMessage(),
            ], 422);
        }
    }
    public function login2(Request $request){
        if(empty($request->json('email'))){
            return response()->json(['status'=>401,'message'=>'email is empty']);
        }
        $exists=User::where('email',$request->json('email'))->exists();
        if($exists==true){
            $user=User::where('email',$request->json('email'))->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            $club=null;
            $existC=Club::where('userId',$user->id)->exists();
            if($existC==true){
                $club=Club::where('userId',$user->id)->first();
            }
            return response()->json(['status'=>200,'token'=>$token,'data'=>$user,'club'=>$club]);
        }
        $user=new User;
        $user->email=$request->json('email');
        $user->name=$request->json('name');
        $user->role="google-facebook";
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['status'=>200,'token'=>$token,'data'=>$user]);
    }
    public function userExists(Request $request){
        $exists=User::where('email',$request->json('email'))->exists();
        return response()->json(['status'=>200,'data'=>$exists]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function update(Request $request)
    {
        try {
            /*if(empty($request->json('name'))||empty($request->json('email'))||empty($request->json('password'))||empty($request->json('phone'))||empty($request->json('role'))){
                return response()->json(['status'=>401,'message'=>',name , email, role, phone and password are required']);
            }*/
            
            $user=User::find($request->get('userId'));
            if(!$user){
                return response()->json(['status'=>401,'message'=>'user not found']);
            }
            DB::beginTransaction();
            if(!empty($request->get('name'))){
                $user->name=$request->get('name');
            }
            if(!empty($request->get('surname'))){
                $user->surname=$request->get('surname');
            }
            if(!empty($request->get('username'))){
                $user->username=$request->get('username');
            }
            if(!empty($request->get('country'))){
                $user->country=$request->get('country');
            }
            if(!empty($request->get('dateOfBirth'))){
                $user->dateOfBirth=$request->get('dateOfBirth');
            }
            if(!empty($request->get('phone'))){
                $user->phone=$request->get('phone');
            }
            if(!empty($request->get('companyName'))){
                $user->companyName=$request->get('companyName');
            }
            if(!empty($request->get('address'))){
                $user->address=$request->get('address');
            }
            if(!empty($request->get('city'))){
                $user->city=$request->get('city');
            }
            if(!empty($request->get('postalCode'))){
                $user->postalCode=$request->get('postalCode');
            }
            if(!empty($request->get('fullName'))){
                $user->fullName=$request->get('fullName');
            }
            if(!empty($request->get('iban'))){
                $user->iban=$request->get('iban');
            }
            if(!empty($request->get('clubId'))){
                $user->clubId=$request->get('clubId');
            }
            if(!empty($request->get('employId'))){
                $user->employId=$request->get('employId');
            }
            if(!empty($request->get('countryIsoCode'))){
                $user->countryIsoCode=$request->get('countryIsoCode');
            }

            if(!empty($request->get('latitude'))){
                $user->latitude=$request->get('latitude');
            }
            if(!empty($request->get('longitude'))){
                $user->longitude=$request->get('longitude');
            }
            
            if(!empty($request->get('password'))){
                $user->password=Hash::make($request->get('password'));
                $user->passwordD=$request->get('password');
            }
            $image=Input::file("file");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $user->file=$picPath;
            }
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            DB::commit();

            return response()->json(['status'=>200,'token'=>$token,'data'=>$user,'message'=>'Updated successfully']);

        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());

            DB::rollBack();
            
            return response()->json([
                'message' => 'User registration failed'.$e->getMessage(),
            ], 422);
        }
    }
    public function employLogin(Request $request){
        if(empty($request->json('employId'))||empty($request->json('clubId'))){
            return response()->json(['status'=>401,'message'=>'employId or clubId is missing']);
        }
        $user=User::where('employId',$request->json('employId'))->where('clubId',$request->json('clubId'))->first();
        if (!$user || !Hash::check($request->json('password'), $user->password)) {
                return response()->json([
                    'message' => 'The credentials are invalid',
                ], 422);
            }

        $token = $user->createToken('auth_token')->plainTextToken;
        if($user->clubId){
            $user->setAttribute('club',Club::find($user->clubId));
        }
        return response()->json(['status'=>200,'token'=>$token,'data'=>$user]);
    }
    public function incrementCoins(Request $request){
        if(empty($request->json('userId'))||empty($request->json('coins'))){
            return response()->json(['status'=>401,'message'=>'userId or coins is missing']);
        }
        $user=User::find($request->json('userId'));
        if(!$user){
            return response()->json(['status'=>401,'message'=>'user not exists']);
        }
        $user->coins=$user->coins+$request->json('coins');
        $user->save();
        return response()->json(['status'=>200,'message'=>'incremented successfully','data'=>$user]);
    }
    public function decrementCoins(Request $request){
        if(empty($request->json('userId'))||empty($request->json('coins'))){
            return response()->json(['status'=>401,'message'=>'userId or coins is missing']);
        }
        $user=User::find($request->json('userId'));
        if(!$user){
            return response()->json(['status'=>401,'message'=>'user not exists']);
        }
        $user->coins=$user->coins-$request->json('coins');
        $user->save();
        return response()->json(['status'=>200,'message'=>'decremented successfully','data'=>$user]);
    }
    public function getUserCoins(Request $request){
        $user=User::find($request->json('userId'));
        return response()->json(['status'=>200,'data'=>$user->coins]);
    }
    

}