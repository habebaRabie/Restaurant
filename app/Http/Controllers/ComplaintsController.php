<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\complaint;
use Illuminate\Support\Facades\DB;

/**
 * @group Complaints Management
 *
 * APIs for adding or removing specific complaint, in addition to showing all the complaints found in the system
 */
class ComplaintsController extends Controller
{
    /**
     * Add complaint
     *
     * This endpoint allows you to add complaint to specific item in the order.
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam item_id int required to place the complaint to that specfic item
     * @bodyParam user_id int required to make sure that we know who wrote the complaint
     * @bodyParam complaint string required the complaint statement
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     *  "data": The whole complaint after adding it
     * }
     */
    public function add(Request $request)
    {
        $itemID = $request->item_id;
        $userID = $request->user_id;
        $complaint = $request->complaint;

        $NewComplaint = new complaint;
        $NewComplaint->item_id = $itemID;
        $NewComplaint->user_id = $userID;
        $NewComplaint->complaint = $complaint;
        $NewComplaint->save();

        return response()->json([
            'status' => true,
            'response' => 200,
            'data' => $NewComplaint
        ]);
    }

    /**
     * Remove complaint
     *
     * This endpoint allows you to remove complaint from specific item in the order.
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam item_id int required to place the complaint to that specfic item
     * @bodyParam user_id int required to make sure that we know who wrote the complaint
     * @bodyParam complaint string required The complaint statement
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     * }
     */

    public function remove(Request $request)
    {
        $itemID = $request->item_id;
        $userID = $request->user_id;
        $complaint = $request->complaint;

        DB::delete('delete from complaints where item_id=? AND user_id=? AND complaint=?', [$itemID, $userID, $complaint]);

        return response()->json([
            'status' => true,
            'response' => 200,
        ]);
    }
    /**
     * Show all the complaints found in the system.
     *
     * This endpoint allows you to see all the complaints found in the system.
     *
     * @param   \Illuminate\Http\Request $request
     * @response scenario=success {
     *  complaints
     * }
     */
    public function show(Request $request)
    {
        $complaint = complaint::get();
        return response()->json($complaint);
    }
}
