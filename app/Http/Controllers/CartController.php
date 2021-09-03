<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cart;
use App\Models\item;
use Illuminate\Support\Facades\DB;

/**
 * @group  Cart Management
 *
 * APIs for Creating and using carts
 */

class CartController extends Controller
{
    /**
     * Create cart
     *
     * Create a new cart to place items in
     *
     * @bodyParam User_id int required The ID of the user who's account is associated with this cart
     * @response scenario=success {
     *   "status": true,
     *   "errNum": 200,
     *   "msg": "cart created",
     *   "cart details": {
     *       "user_id": "3",
     *       "total_price": 0,
     *        "status": false,
     *        "updated_at": "2021-09-02T13:04:33.000000Z",
     *        "created_at": "2021-09-02T13:04:33.000000Z",
     *        "id": 9
     *    }
     *}
     */
    public function createCart(Request $request)
    {
        $user_id = $request->user_id;
        $cart = new cart;
        $cart->user_id = $user_id;
        $cart->total_price = 0;
        $cart->status = false;
        $cart->save();
        return response()->json([
            'status' => true,
            'errNum' => 200,
            'msg' => 'cart created',
            'cart details' => $cart
        ]);
    }


    /**
     * Add to cart
     *
     * add a new item to the cart
     *
     * @bodyParam cart_id int required The id of the cart to which the item will be added
     *
     * @bodyParam item_id int required The id of the item that needs to be added
     *
     * @response scenario=success {
     *  "status": true,
     *  "errNum": 200,
     *  "msg": "Item Added Successfully",
     *  "cart details": {
     *      "id": 5,
     *      "user_id": 3,
     *      "total_price": 66,
     *      "status": "false",
     *      "created_at": "2021-08-19T09:51:01.000000Z",
     *      "updated_at": "2021-09-02T12:30:43.000000Z"
     *  }
     *}
     */
    public function addToCart(Request $request)
    {
        $id = $request->item_id;
        $cartID = $request->cart_id;
        $cart = cart::find($cartID);
        $item = item::find($id);
        if (DB::select('select * from cartitems where cart_id = ? AND item_id = ?', [$cartID, $item->id])) {
            DB::update('update cartitems set quantity = quantity+1 where cart_id = ? AND item_id = ?', [$cartID, $item->id]);
            $cart->total_price += $item->price;
            $cart->save();
        } else {
            DB::insert('insert into cartitems (cart_id, item_id, quantity) values (?, ?, ?)', [$cart->id, $item->id, 1]);
            $cart->total_price += $item->price;
            $cart->save();
        }
        return response()->json([
            'status' => true,
            'errNum' => 200,
            'msg' => 'Item Added Successfully',
            'cart details' => $cart,
        ]);
    }


    /**
     * Remove from cart
     *
     * Remove an item from the cart
     *
     * @bodyParam cart_id int required The id of the cart that will be modified
     *
     * @bodyParam item_id int required The id of the item that needs to be removed
     *
     *  @response scenario=success {
     *    "status": true,
     *    "errNum": 200,
     *    "msg": "Item Removed Successfully",
     *    "cart details": {
     *        "id": 5,
     *        "user_id": 3,
     *        "total_price": 54,
     *        "status": "false",
     *        "created_at": "2021-08-19T09:51:01.000000Z",
     *        "updated_at": "2021-09-02T12:51:23.000000Z"
     *    }
     *}
     *
     */
    public function removeFromCart(Request $request)
    {
        $itemID = $request->item_id;
        $cartID = $request->cart_id;
        $item = Item::find($itemID);
        $cart = cart::find($cartID);
        if (DB::select('select * from cartitems where cart_id = ? AND item_id = ?', [$cartID, $itemID])) {
            if (DB::select('select quantity from cartitems where cart_id = ? AND item_id = ? AND quantity > 0', [$cartID, $itemID])) {
                DB::update('update cartitems set quantity = quantity-1 where cart_id = ? AND item_id = ?', [$cartID, $item->id]);
            } else {
                DB::delete('delete from cartitems where cart_id = ? AND item_id = ?', [$cartID, $itemID]);
                return response()->json([
                    'status' => false,
                    'errNum' => 404,
                    'msg' => 'item does not exist',
                    'cart details' => $cart,
                ]);
            }
            $cart->total_price -= $item->price;
            $cart->save();
            return response()->json([
                'status' => true,
                'errNum' => 200,
                'msg' => 'Item Removed Successfully',
                'cart details' => $cart,
            ]);
        }
    }


    /**
     * List cart items
     *
     * lists all items in the cart and their quantity
     *
     * @bodyParam cart_id int required The id of the cart that will be modified
     *
     *
     *  @response scenario=success {
     *  "status": true,
     *  "response": 200,
     *  "msg": "Items retrieved",
     *  "cart items": [
     *   {
     *      "quantity": 2,
     *     "item": [
     *        {
     *            "id": 1,
     *            "item_name": "pizza",
     *            "category_id": 1,
     *            "rating": 0,
     *            "price": "15.00",
     *            "offer": null,
     *            "offer_end_date": null,
     *            "created_at": null,
     *            "updated_at": null
     *           }
     *       ]
     *   }
     * }
     *
     */
    public function listCartItems(Request $request)
    {
        $cartID = $request->cart_id;
        $itemsID = DB::select('select * from cartitems where cart_id = ?', [$cartID]);
        if ($itemsID != NULL) {
            $items = [];
            foreach ($itemsID as $item) {
                $lItem = item::where('id', '=', $item->item_id)->get();
                $lquant = $item->quantity;
                array_push($items, ['item' => $lItem, 'quantity' => $lquant]);
            }
            return response()->json([
                'status' => true,
                'errNum' => 200,
                'msg' => 'Items retrieved',
                'cart items' => $items,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errNum' => 200,
                'msg' => 'no items in cart',
            ]);
        }
    }
}
