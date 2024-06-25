<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Category;
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
            $user->phone=$request->get('phone');
            $user->role=$request->get('role');
            $user->companyName=$request->get('companyName');
            $user->address=$request->get('address');
            $user->city=$request->get('city');
            $user->postalCode=$request->get('postalCode');
            $user->fullName=$request->get('fullName');
            $user->iban=$request->get('iban');
            $user->clubId=$request->get('clubId');
            $user->employId=$request->get('employId');
            $image=Input::file("file");
            if(!empty($image)){
                $newFilename=$image->getClientOriginalName();
                $destinationPath='files';
                $image->move($destinationPath,$newFilename);
                $picPath='files/' . $newFilename;
                $user->file=encrypt($picPath);
            }
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
            

            
            return response()->json(['status'=>200,'token'=>$token,'data'=>$user]);

        } catch (\Exception $e) {
            // Log the error
            Log::error('User Login failed: ' . $e->getMessage());
            // Return a JSON response with an error message
            return response()->json([
                'message' => 'User login failed'.$e->getMessage(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function gmail(Request $request){
        if(empty($request->json('email'))){
            return response()->json(['status'=>401,'message'=>'email is required']);
        }
        $exists=User::where('email',$request->json('email'))->exists();
        if($exists==false){
            $user=new User;
            $user->name=$request->json('name');
            $user->email=$request->json('email');
            $user->role='gmail';
            
            $user->save();
            return response()->json(['status'=>200,'data'=>$user,'exists'=>'no']);
        }
        $user=User::where('email',$request->json('email'))->first();
        return response()->json(['status'=>200,'data'=>$user,'exists'=>'yes']);
    }

}