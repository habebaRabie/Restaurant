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
     *
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
            'msg' => 'Items Added Successfuly',
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
                'msg' => 'Item removed Successfuly',
                'cart details' => $cart,
            ]);
        }
    }
}
