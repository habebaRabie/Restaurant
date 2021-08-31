<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Admin;
use App\Models\item;
use App\Models\category;
class AdminController extends Controller
{
/**
 * getting all admins in the database 
 * @response 200 scenario="the user is a superadmin" {
*   {
*   "id": "1" , 
*   "email":"steve@gmail.com" , 
*   "username":"steve" , 
*   "superadmin":"1"
*   }
*   {
*   "id": "2" , 
*   "email":"sara@gmail.com" , 
*   "username":"sara" , 
*   "superadmin":"0"
*   }
 * }
 * @response 401 scenario="the user isn't a superadmin" {"message":"unauthorized"}
*/
    function GetAdmins(){
        $admin = Auth::user();

        $admin->superadmin;

        if($admin->superadmin == 1)
        {
            return Admin::all();
        }
        else{
            return "you are not a superadmin";
        }
        
    }
/**
* getting a certain admin in the database by the id
* @urlParam id integer required The ID of the admin
* @response 200 scenario="the user is a superadmin"
*   {
*   "id": "1" , 
*   "email":"steve@gmail.com" , 
*   "username":"steve" , 
*   "superadmin":"1"
*   }
* @response 401 scenario="the user isn't a superadmin" {"message":"unauthorized"}
* @response 404 scenario="the admin by the given id is not found"
* 
*/
    function GetAdminsById($id){
        $admin = Auth::user();

        $admin->superadmin;

        if($admin->superadmin == 1)
        {
            return  Admin::FindorFail($id);
        }
        else{
            return "you are not a superadmin";
        }
    }
/**
* updating a certain admin with id from the database 
* @bodyParam superadmin boolean
* @urlParam id integer required The ID of the admin
* @response 200 scenario="the user is a superadmin"
* @response 401 scenario="the user isn't a superadmin" {"message":"unauthorized"}
* @response 404 scenario="the admin by the given id was not found"
* @response 422 scenario="The request was well formed but was unable to be followed due to a validation error"

* 
*/
    
    function UpdateAdmin(Request $request , $id){

        $admin = Auth::user();

        $admin->superadmin;

        if($admin->superadmin == 1)
        {
            $request->validate([

                'superadmin'=>'boolean',
            ]);

           return Admin::FindorFail($id)->
           update(
            $request->except('email' , 'username' , 'password')
           );
            
        }
        else{
            return "You are not a superadmin";
        }
    }
/**
* removing a certain admin with id from the database
* @urlParam id integer required The ID of the admin
* @response 200 scenario="the user is a superadmin"
* @response 401 scenario="the user isn't a superadmin" {"message":"unauthorized"}
* @response 404 scenario="the admin with the given id was not found"
* 
*/        
    
    function RemoveAdmins($id){
        $admin = Auth::user();

        $admin->superadmin;

        if($admin->superadmin == 1)
        {
            $result = DB::table('admins')->
            where('id' , '=' , $id)
            ->delete();
            if($result == 1)
            {
                return "Admin is removed successfully";
            }
            else{
                return  "Admin deletion failed";
            }
        }
        else{
            return "You are not a superadmin";
        }
         
     }

/**
* adding category to the database
* @bodyParam category_name required string and must be unique
* @response 200 scenario="the user is an admin and the inputs are satisfied with the validation rules"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 422 scenario="The request was well formed but was unable to be followed due to a validation error"
* 
*/

    function AddCategory(Request $request){
        
        $request->validate([
            'category_name'=>'required|filled|unique:category,category_name|max:40'
        ]);

        $result = DB::table('category')->insert(
            $request->except('id')
            );
        
        if ($result == 1){
            return "Category was created succesfully";
        }
        else{
            return "Category was not created";
        }
    }
/**
* updating a certain category with id in the database 
* @bodyParam category_name string and must be unique 
* @urlParam id integer required The ID of the category
* @response 200 scenario="the user is an admin and the inputs are satisfied with the validation rules"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 404 scenario="the category with the given id was not found"
* @response 422 scenario="The request was well formed but was unable to be followed due to a validation error"
*/
    
    function UpdateCategory($id , Request $request){

        $request->validate([
            'category_name'=>"unique:category,category_name,$id|filled|max:40"
        ]);
        $result = category::FindorFail($id)
              ->update(
                $request->except('id')
              );
        
        if ($result == 1){
            return "Category is Updated succesfully";
        }
        else{
            return "Category is not updated";
        }
    
    }

/**
* removing a certain category with id from the database
* @urlParam id integer required The ID of the category
* @response 200 scenario="the user is an admin"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 404 scenario="the category with the given id is not found"
* 
*/  
    function DeleteCategory($id){
        
        $result = category::FindorFail($id)->delete();

        if($result == 1)
        {
            return "The Category with id:$id was deleted succesfully";
        }
        else{
            return "The Category deletion failed";
        }
    }
/**
* adding item to the database 
* @bodyParam item_name string required and must be unique with 40 chrachters at maximum
* @bodyParam category_id int required and it must exist
* @bodyParam price decimal required
* @bodyParam offer decimal required if there was an offer_end_date
* @bodyParam offer_end_date datetime required if there was an offer
* @bodyParam file_path file 
* @response 200 scenario="the user is an admin and the inputs are satisfied with the validation rules"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 422 scenario="The request was well formed but was unable to be followed due to a validation error"
* 
*/

    function AddItem(Request $request){

        $request->validate([
            'item_name' => 'required|filled|unique:items,item_name|max:40',
            'category_id'=>'required|filled|exists:category,id',
            'price'=>'required|filled|gt:0',
            'file_path'=> 'filled|file',
            'offer'=>['filled','gt:-1','numeric',Rule::requiredIf(!empty($request->input('offer_end_date')))],
            'offer_end_date'=> [Rule::requiredIf(!empty($request->input('offer'))),'filled']
        ]);
        $item = new item();

        $item -> item_name = $request ->input('item_name');
        $item -> category_id = $request ->input('category_id');
        $item -> price = $request ->input('price');
        if($request -> hasfile('file_path'))
        {
            $item = item::FindorFail($id);
            $item->file_path = $request ->file('file_path')->store("items_image");
        }
        $item -> offer = $request ->input('offer');
        $item -> offer_end_date = $request ->input('offer_end_date');
        $result = $item -> save();

        if ($result == 1){
            return "item is created successfully";
        }
        else{
            return "item isn't created";
        }

    }


/**
* updating a certain item with id in the database
* @urlParam id integer required The ID of the item
* @response 200 scenario="the user is an admin and the inputs are satisfied with the validation rules"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 404 scenario="the item with the given id was not found"
* @response 422 scenario="The request was well formed but was unable to be followed due to a validation error"
*/
   
    function UpdateItem($id , Request $request){
        $request->validate([
            'item_name' => "filled|unique:items,item_name,${id}|max:40",
            'category_id'=>'filled|exists:category,id',
            'price'=>'filled|gt:0',
            'offer'=>['filled','gt:-1','numeric',Rule::requiredIf(!empty($request->input('offer_end_date')))],
            'offer_end_date'=>['filled',Rule::requiredIf(!empty($request->input('offer')))]
        ]);
        

       

        if($request -> hasfile('file_path'))
        {
            $item = item::FindorFail($id);
            $item->file_path = $request ->file('file_path')->store("items_image");
            $item->save();
        }
        $result = item::FindorFail($id)
              ->update(
                $request->except('file_path')
            );
        
        
        if ($result == 1){
            return "item is Updated successfully";
        }
        else{
            return "item is not updated";
        }
    
    }
/**
* removing a certain item with id from the database
* @urlParam id integer required The ID of the item
* @response 200 scenario="the user is an admin"
* @response 401 scenario="the user isn't an admin" {"message":"unauthorized"}
* @response 404 scenario="the item with the given id is not found"
* 
*/  
    function DeleteItem($id){
        
        $result = Item::FindorFail($id)->delete();

        if($result == 1)
        {
            return "The item with id:$id was deleted successfully";
        }
        else{
            return "The item deletion failed";
        }
    }
}
