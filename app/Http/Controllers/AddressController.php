<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\address;
use Illuminate\Support\Facades\DB;

/**
 * @group address Management
 *
 * APIs for adding new address or updating old one for customers
 */

class AddressController extends Controller
{
    /**
     * Add address
     *
     * This endpoint allows you to add address to the order.
     *
     * The user can add favourite address to facilitate the usage for him
     * and also can add multiple addresses in his account
     *
     * <aside class="notice">when the user add new address it takes place of the old one</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam user_id int required to assign the address to him
     * @bodyParam location string required The new address that the user wants to add
     * @bodyParam favourite_location string required The new favourite address if the user wanted to add one
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     *  "data": The user details id,user_id,favourite_location,location after assigning his new location and favourite location
     * }
     */
    public function addAddresses(Request $request)
    {
        $userID = $request->user_id;
        $location = $request->location;
        $favouriteLocation = $request->favourite_location;

        $userAddress = new address;
        $userAddress->user_id = $userID;
        $userAddress->location = $location;
        $userAddress->favourite_location = $favouriteLocation;
        $userAddress->save();

        return response()->json([
            'status' => true,
            'response' => 200,
            'data' => $userAddress
        ]);
    }



    /**
     * Add favourite address
     *
     * This endpoint allows you to add favourite address to the order.
     *
     * The user can add favourite address to facilitate the usage for him
     *
     * <aside class="notice">when the user add new favourite address it takes place of the old one</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam user_id int required to assign the address to him
     * @bodyParam favourite_location string required The new favourite address that the user wants to add
     * @response scenario=success {
     *  "status": true,
     *  "response": 200
     *  "data": The user details id,user_id,favourite_location,location after updating his favourite address
     * }
     */

    public function addFavouriteAddress(Request $request)
    {
        $userID = $request->user_id;
        $favouriteLocation = $request->favourite_location;
        $userAddress = address::where("user_id", $userID) ->first();

        if($userAddress !=null){
            $userAddress->user_id = $userID;
            $userAddress->favourite_location = $favouriteLocation;
            $userAddress->save();
        }
        else{
            $userAddress = new address;
            $userAddress->user_id = $userID;
            $userAddress->favourite_location = $favouriteLocation;
            $userAddress->save();
        }

        return response()->json([
            'status' => true,
            'response' => 200,
            'data' => $userAddress
        ]);
    }


    /**
     * Add another address
     *
     * This endpoint allows you to add another address to the order differ from the old one he ordered by.
     *
     * The user can add another address to facilitate the usage for him
     *
     * <aside class="notice">when the user add new another address it takes place of the old one</aside>
     *
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam user_id int required to assign the address to him
     * @bodyParam location string required The new address that the user wants to add
     * @response scenario=success {
     *  "status": true,
     *  "response": 200,
     *  "data": The user details id,user_id,favourite_location,location after updating his address
     * }
     */

    public function addAnotherAddress(Request $request)
    {
        $userID = $request->user_id;
        $location = $request->location;
        $userAddress = address::where("user_id", $userID) ->first();

        if($userAddress !=null){
            $userAddress->user_id = $userID;
            $userAddress->location = $location;
            $userAddress->save();
        }
        else{
            $userAddress = new address;
            $userAddress->user_id = $userID;
            $userAddress->location = $location;
            $userAddress->save();
        }

        return response()->json([
            'status' => true,
            'response' => 200,
            'data' => $userAddress
        ]);
        
    }
}
