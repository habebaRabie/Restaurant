<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\cart;

use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;



class PromoCodeController extends Controller
{
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //in form in checkout.blade we will set the name of the form 
        //as promo_code
        $coupon = PromoCode::where('code', $request->code)->first();
        $mytime = Carbon::now(); // today
        if(!$coupon) {

            session()->forget('PromoCode');
            return response()->json('Invalid Promo code. Please try again.');
          
        }
        elseif($coupon->code==$request->code && $coupon->end_date < $mytime){

            session()->forget('PromoCode');
            return response()->json('Invalid Promo code. Please try again.');
          
        }
        $total=cart::select('select total_price from cart');
        session()->put('PromoCode',[
            'code'=>$coupon->code,
            'discount'=>$coupon->discount($total)
        ]);
        return response()->json( 'success_message,PromoCode has been applied!');
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->forget('PromoCode');

        return response()->json('success_message , PromoCode has been removed.');
    }

}
