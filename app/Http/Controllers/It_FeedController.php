<?php

namespace App\Http\Controllers;

use App\Models\itemfeedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * @group Item Management
 *
 * APIs for placing item feedback and rating
 */

class It_FeedController extends Controller
{
    /**
     * Add item Feedback Rating to the order.
     *
     * This endpoint allows user to add feedback and rating to a special item.
     *
     * The user can add feedback and rating to any item he want.
     * The user can add more than feedback and rating to different item
     *
     * <aside class="notice">the user add feedback and rating to many different item</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam U_id int required use to know the user
     * @bodyParam feedback string required that is the feedback that the user want to get to the item
     * @bodyParam rating float required that is the rate that the user want to get to the item
     * @bodyParam It_id int required use to know the item
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     * }
     */
    public function Add_item_F_R(Request $request)
    {
        $review = new itemfeedback;

        $U_id = $request->user_id;
        $feedback = $request->feedback;
        $rating = $request->rating;
        $It_id = $request->item_id;

        $review->user_id = $U_id;
        $review->feedback = $feedback;
        $review->rating = $rating;
        $review->item_id = $It_id;
        $review->save();
        return response()->json([
            'status' => true,
            'response' => 200,
        ]);
    }
}
