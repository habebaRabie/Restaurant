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
     * @bodyParam promocode.code string required The promocode's code
     * @bodyParam promocode.type string required The promocode's type
     * @bodyParam promocode.value integer required The promocode's value
     * @bodyParam promocode.persent_off decimal required The promocode's persent_off
     * @bodyParam promocode.no_users integer required The promocode's no_users
     * @bodyParam promocode.count_uses integer required The promocode's count_uses
     * @bodyParam promocode.active tinyInteger required The promocode's active
     * @bodyParam promocode.end_date dateTime required The promocode's end_date
     *  @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'end_date' => 'required',
            'no_users' => 'required',
            'active' => 'required',
        ]);
    
        $promocode = new PromoCode;
        $promocode->code = $request->code;
        $promocode->type = $request->type;
        $promocode->value = $request->value;
        $promocode->persent_off = $request->persent_off;
        $promocode->end_date = $request->end_date;
        $promocode->no_users = $request->no_users;
        $promocode->count_uses = $request->count_uses;
        $promocode->active = $request->active;
        $promocode->save();
        return response()->json(' promocode created successfully.');
    }

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $coupon = PromoCode::where('code',$request->code)->first();
       $codetime =Carbon::now();//today
    if (!$coupon) {

        return response()->json('Invalid coupon code. Please try again.');
              }
    elseif($coupon->code == $request ->code && $coupon->end_date <$codetime){
        return response()->json('Invalid coupon code. Please try again.');
        
        }
      
    if($coupon->active==1 && $coupon->count_uses <= $coupon->no_users){
        $total=cart::select('select total_price from cart');
        session()->put('PromoCode',[
    
            'name'=>$coupon->code ,
           'discount'=>$coupon->discount($total)
        ]);
        $coupon->count_uses++;
        $coupon->save();
        return response()->json('success_message,Coupon has been applied!');
    }
    
    return response()->json('Invalid coupon code. Please try again.');
    }
   
    


    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  $id integer required The ID of the promocode
     * @return \Illuminate\Http\Response
     * @bodyParam promocode.code string required The promocode's code
     * @bodyParam promocode.type string required The promocode's type
     * @bodyParam promocode.value integer required The promocode's value
     * @bodyParam promocode.precent_off integer required The promocode's precent_off
     * @bodyParam promocode.no_users integer required The promocode's no_users
     * @bodyParam promocode.count_uses integer required The promocode's count_uses
     * @bodyParam promocode.active boolean required The promocode's active
     * @bodyParam promocode.end_date dateTime required The promocode's end_date
      */
    public function update(Request $request, $id)

    {
        $request->validate([
            'code' => 'required',
            'end_date' => 'required',
            'no_users' => 'required',
            'active' => 'required',
        ]);
    
        $promocode = PromoCode::findOrFail($id);
        $promocode->code = $request->code;
        $promocode->type = $request->type;
        $promocode->max_value = $request->maxValue;
        $promocode->persent = $request->discountPersent;
        $promocode->end_date = $request->endDate;
        $promocode->no_users = $request->no_users ;
        $promocode->count_uses = $request->count_uses;
        $promocode->active = $request->active;
        $promocode->save();
        return response()->json('success promocode details are updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *@param id is the id of the promocode we want to delete from wish list
     * @bodyparam  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promocode =PromoCode::where('id',$id)->first();
        $promocode->delete();
        return response()->json("Successfully deleted!!");

    }

  

}

