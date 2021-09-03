<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;


/**
 * @group  Order Management
 *
 * APIs for placing orders and modifying them
 */

class CommentController extends Controller
{
    /**
     * Add additional comment
     *
     * This endpoint allows you to add additional comment to the order.
     *
     * The user can add additional comment to the order
     * Ex: The customer wants the food spicy.
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam id int required, the id to get the order that we want to add the additional comment to it
     * @bodyParam additional_comment string required, the additional comment statment that the user has entered
     * @response scenario=success {
     *  "status": true,
     *  "response": 200,
     *  "data": The whole order with the additional comment added to it
     * }
     */
    public function addComment(Request $request)
    {
        $ID = $request->id;
        $additionalComment = $request->additional_comment;

        $Order = order::find($ID);
        $Order->additional_comment = $additionalComment;
        $Order->save();

        return response()->json([
            'status' => true,
            'response' => 200,
            'data' => $Order
        ]);
    }
}
