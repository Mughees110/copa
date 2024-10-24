<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
class CartController extends Controller
{
    public function addToCart(Request $request){
    	$cart=new Cart;
    	$cart->itemId=$request->json('itemId');
    	$cart->userId=$request->json('userId');
    	$cart->quantity=$request->json('quantity');
    	$cart->status=$request->json('status');
    	$cart->save();
    	return response()->json(['status'=>200,'message'=>'Added successfully']);
    }
    public function getMyCart(Request $request){
    	$carts=Cart::where('userId',$request->json('userId'))->get();
    	foreach ($carts as $key => $value) {
    		$value->setAttribute('item',Item::find($value->itemId));
    	}
    	return response()->json(['status'=>200,'carts'=>$carts]);
    }
    public function editCart(Request $request){
    	$cart=Cart::find($request->json('cartId'));
    	if(!$cart){
    		return response()->json(['status'=>401,'message'=>'cart not found']);
    	}
    	if(!empty($request->json('status'))){
    		$cart->status=$request->json('status');
    	}
    	if(!empty($request->json('quantity'))){
    		$cart->quantity=$request->json('quantity');
    	}
    	$cart->save();
    	return response()->json(['status'=>200,'cart'=>$cart,'message'=>'Edited successfully']);
    }
    public function deleteCart(Request $request){
    	$cart=Cart::find($request->json('cartId'));
    	if(!$cart){
    		return response()->json(['status'=>401,'message'=>'cart not found']);
    	}
    	$cart->delete();
    	return response()->json(['status'=>200,'message'=>'Deleted successfully']);
    }
}
