<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\item;

class ItemController extends Controller
{
    //
    /**
     * Get all items.
     *
     * This endpoint allows user to see all items we have.
     *
     * <aside class="notice">when the user want to see all item we have he can see it</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @response scenario=success
     * [
     * {
     *    "item_name": "Cheese Veggie Sandwich",
     *   "rating": 4,
     *   "price": "169.00",
     *   "offer": "93.00",
     *   "offer_end_date": "2022-01-01 05:30:03"
     * },
     * {
     *   "item_name": "Bacon Dog",
     *   "rating": 4,
     *   "price": "169.00",
     *   "offer": "66.00",
     *  "offer_end_date": "2022-01-01 05:30:03"
     * },
     * {
     *   "item_name": "Cheeseburger",
     *   "rating": 5,
     *   "price": "103.00",
     *   "offer": "90.00",
     *   "offer_end_date": "2022-01-01 05:30:03"
     * },
     * {
     *   "item_name": "Little Cheeseburger",
     *   "rating": 0,
     *   "price": "187.00",
     *   "offer": "60.00",
     *   "offer_end_date": "2022-01-01 05:30:03"
     *  }  
     *]
     *
     */
    public function GetItem(Request $request)
    {
        $item = item::select('item_name','rating','price','offer','offer_end_date')->get()->all();
        return response()->json($item);
    }
}
