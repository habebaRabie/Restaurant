<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\cart;
use App\Models\item;

use Illuminate\Support\Facades\DB;
/**
 * @group  Order Management
 *
 * APIs for placing orders and modifying them
 */

class OrdersController extends Controller
{
    /**
     * Place order
     *
     * recieve a cart and place an order with cart info
     *
     * @bodyParam cartID int required The id of a valid cart to use for the order
     * @bodyParam deliveryType int required A number between 0-3 to specify the type of delivery from the list ['To Home', 'To Car', 'In Restaurant', 'Take Away']. Example: 2
     *
     * @response scenario=success {
     *   "status": true,
     *   "errNum": 200,
     *   "msg": "Order placed",
     *   "order details": {
     *       "user_id": 3,
     *       "price": 0,
     *       "type_of_delivery": "In Restaurant",
     *       "updated_at": "2021-09-02T13:05:53.000000Z",
     *       "created_at": "2021-09-02T13:05:53.000000Z",
     *       "id": 11
     *   }
     *}
     */
    public function addOrderItem(Request $request)
    {
       
        $item = new item;
        $item = item::FindorFail($request["item_id"]);

        $result = DB::table('orderitem')->insert([
            "order_id"  =>  $request["order_id"],
            "quantity" => $request["quantity"],
            "price" => $item["price"] * $request["quantity"],
            "item_id" => $request["item_id"]
        ]);
       
            
    }


    function UpdateOrder($id , Request $request){

        $result = Order::FindorFail($id)
              ->update(
                $request->all()
              );
        
        if ($result == 1){
            return "Order is Updated succesfully";
        }
        else{
            return "Order is not updated";
        }
    
    }
    public function AddNewOrder(Request $request)
    {
        

        
        $delivery_type = $request->deliveryType;
        $typesOfDelivery = ['To Home', 'To Car', 'In Restaurant', 'Take Away'];
        if ($delivery_type > 3) {
            return response()->json([
                'status' => false,
                'errNum' => 404,
                'msg' => 'Type not found'
            ]);
        } else {
            $order = new Order;
          
            $order->user_id=auth()->user()->id;

            $order-> price = $request ->input('price');
            $order->type_of_delivery = "In Restaurant";
            
            $order->save();
            $this->OrderId = $order->id;
           

            
            return $order;
            
            
        }
    }
    function DeleteOrder($id){
        
        $result = Order::FindorFail($id)->delete();
    }
    public function placeOrder(Request $request)
    {
        $cartID = $request->cartID;
        $cart = cart::find($cartID);
        if ($cart == NULL) {
            return response()->json([
                'status' => false,
                'errNum' => 404,
                'msg' => 'Cart not found'
            ]);
        }
        $delivery_type = $request->deliveryType;
        $typesOfDelivery = ['To Home', 'To Car', 'In Restaurant', 'Take Away'];
        if ($delivery_type > 3) {
            return response()->json([
                'status' => false,
                'errNum' => 404,
                'msg' => 'Type not found'
            ]);
        } else {
            $order = new Order;
            $order->user_id = $cart->user_id;
            $order->price = $cart->total_price;
            $order->type_of_delivery = $typesOfDelivery[$delivery_type];
            $order->save();
            $cart->status = true;
            $cart->save();
            return response()->json([
                'status' => true,
                'errNum' => 200,
                'msg' => 'Order placed',
                'order details' => $order
            ]);
        }
    }
   

    /**
     * History of the order.
     *
     * This endpoint allows user to see all his order history.
     *
     * Enable user to see all his old orders as if his want to take the same order again
     *
     * <aside class="notice">when the user want to see his histroy he can see it</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam user_id int required to know the user that we will get his data
     * @response scenario=success
     * [
     *  {
     *      "price": "54.00",
     *      "type_of_delivery": "To Home",
     *      "rating": null,
     *      "Feedback": null
     *  },
     *  {
     *    "price": "15.00",
     *    "type_of_delivery": "To Home",
     *    "rating": null,
     *    "Feedback": null
     *  },
     *  {
     *    "price": "23.00",
     *    "type_of_delivery": "In Restaurant",
     *    "rating": null,
     *    "Feedback": null
     *  }
     *]
     *
     */
    public function History(Request $request)
    {
        $user_id = $request->user_id;
        $order_id = order::select('id')->where('user_id', $user_id)->get()->all();
        //echo $order_id;
        $history = [];
        foreach ($order_id as $order)
        {
            $key=$order['id'];
            
           $history[] = DB::table('orderitem')->where('order_id',$key)->get()->all();
        }
        
  return response()->json($history);
    }
    /**
     *  Add Feedback to the order.
     *
     * This endpoint allows user to add feedback to his order.
     *
     * the user can put a feedback about the order that he paid
     *
     * <aside class="notice">The user add a feedback to an order </aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam order_id int required to know the order that will take feedback
     * @bodyParam feedback string required that is the feedback that the user want to get to the order
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     * }
     *
     */
    public function add_feedback(Request $request)
    {
        $order_id = $request->id;
        $feedback = $request->Feedback;
        $about_feedback = order::where("id",  $order_id)->where('Feedback', NULL)->get('Feedback')->first();
        //dd($about_feedback);

        if ($about_feedback != null) {
            $Order = order::find($order_id);
            $Order->Feedback = $feedback;
            $Order->save();
            return response()->json([
                'status' => true,
                'response' => 200,
            ]);
        } else {
            return response()->json("Order already has feedback");
        }
    }





    /**
     * Add Rating to the order.
     *
     * This endpoint allows user to add rate to his order.
     *
     * the user can put a rate about the order that he paid
     *
     * <aside class="notice">The user add a rate to an order </aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam order_id int required to know the order that will take rate
     * @bodyParam rate float required that is the feedback that the user want to get to the order
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     * }
     *
     */
    // public function add_Rating(Request $request)
    // {
    //     $order_id = $request->id;
    //     $rate = $request->rating;
    //     $about_rating = order::where("id",  $order_id)->where('rating', NULL)->get('rating')->first();

    //     if ($about_rating != null) {
    //         $Order = order::find($order_id);
    //         $Order->rating = $rate;
    //         $Order->save();
    //         return response()->json([
    //             'status' => true,
    //             'response' => 200,
    //         ]);
    //     } else {
    //         return response()->json("Order has already been rated");
    //     }
    // }

    public function add_Rating(Request $request)
    {
        $order_id = $request->id;
        $rate = $request->rating;
        $feedback = $request->Feedback;
        $about_rating = order::where("id",  $order_id)->where('rating', NULL)->where('Feedback', NULL)->get('rating')->first();

        if ($about_rating != null) {
            $Order = order::find($order_id);
            $Order->rating = $rate;
            $Order->Feedback = $feedback;
            $Order->save();
            return response()->json([
                'status' => true,
                'response' => 200,
            ]);
        } else {
            return response()->json("Order has already been rated and have feedback");
        }
    }
}
