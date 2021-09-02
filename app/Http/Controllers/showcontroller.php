<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Table;


use function GuzzleHttp\Promise\each;

class showcontroller extends Controller
{
    //

    /**
     * getting all orders in database
     * 
     * @response 200 scenario=
     * {
     * 
     * 
     *  "id": 1,
     *  "user_id": 1,
       
       * "price": "20.20",
        *"type_of_delivery": "Take Away",
        *"created_at": null,
       * "updated_at": null,
       * "rating": "5.00",
       * "Feedback": "good"
  *  },
  * {
       * "id": 2,
       * "user_id": 1,
       * "price": "20.20",
       * "type_of_delivery": "Take Away",
       * "created_at": null,
       * "updated_at": null,
       * "rating": "5.00",
       * "Feedback": "good"
       * 
       * *}, 
       
     */
    function show()
    {
       return $data=Order::all();
         
    }

/**
 * get all the orders of a specific user 
 * 
 * @bodyParam  search int the id of the specific user
 */

    public function search(Request $request)
    {
        $search=$request->search;
        return $data=orders::where('user_id','Like','%'.$search.'%')->get();
        

    }
    /**
     * get the total number of orders
     */
    function operations()
    {
        return DB::table('orders')->count();
    }

    public function piechart()
    {
        $result=DB::select(DB::raw('SELECT count(item_id)as item_id,item.item_name FROM orderitem join item on orderitem.item_id=item.id group by item.item_name'));
        $data="";
        foreach($result as $val){
            $data.="['".$val->item_name."',   ".$val->item_id."  ],";
        }
        $chartData=$data;
        return view('pie',compact('chartData'));
    }
    /**
     * 
     * get the 4 most selling item
     */

    public function sort()
    {
        
       return DB::select(DB::raw('SELECT items.item_name FROM orderitem join items on orderitem.item_id=items.id group by items.item_name order BY count(item_id) DESC LIMIT 4'));
        
    }
    /**
     * 
     * create a new table with the state 
     * 
     * 
     * @bodyParam table state string required The state of the table to which the table will be added

     */
    public function addtable(Request $req)
    {
        $table= new Table;
        $table->state=$req->state;
        $table->save();
        return response()->json([
            'status' => true,
            'errNum' => 200,
            'msg' => 'table added Successfuly',]);

    }
    /**
     * 
     * shows all the tables with their state
     */
    function list()
    {
         return $data=Table::all();
       
        
    }
/**
 * select the wanted table to change it's state
 * 
 * 
 * @urlParam id integer required The ID of the selected table
 * 

 */
    function showdata($id)
    {
        return $data=Table::find($id);
        


    }
    /**
     * update the table state
     * 
     * @bodyParam id of the table
     * 
     * @bodyParam state the new wanted state to update
     */
    function edit(Request $req)
    {
        $data=Table::find($req->id);
        $data->state=$req->state;
        $data->save();
        return response()->json('success_message,done!');

    }

}
