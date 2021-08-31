<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\cart;

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
     */
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
}
