<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    function UpdateAdmin(Request $request , $id){

        $request->validate([

            'superadmin'=>'boolean',
        ]);

        DB::table('admins')
        ->where('id' , $id)
        ->update([
            'superadmin' => $request["superadmin"]
        ]
        );        
    }
    function RemoveAdmins($id){

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
    function UpdateCategory($id , Request $request){

        $request->validate([
            'category_name'=>"required|unique:category,category_name,$id|filled|max:40"
        ]);
        $result = DB::table('category')
              ->where('id', $id)
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
    function DeleteCategory($id){
        
        $result = DB::table('category')->where("id" , "=" , $id)->delete();

        if($result == 1)
        {
            return "The Category with id:$id was deleted succesfully";
        }
        else{
            return "The Category deletion failed";
        }
    }

    function AddItem(Request $request){

        $request->validate([
            'item_name' => 'required|filled|unique:items,item_name|max:40',
            'category_id'=>'required|filled|exists:category,id',
            'price'=>'required|filled|gt:0',
            'offer'=>'filled|gt:-1|numeric',
            'offer_end_date'=>'filled|after_or_equal:today'
        ]);
        $result = DB::table('items')->insert(
                $request->except('rating' , 'id')
            );
        
        if ($result == 1){
            return "item was created succesfully";
        }
        else{
            return "item is not created";
        }
    }
   
    function UpdateItem($id , Request $request){
        $request->validate([
            'item_name' => "required|filled|unique:items,item_name,${id}|max:40",
            'category_id'=>'required|filled|exists:category,id',
            'price'=>'required|filled|gt:0',
            'offer'=>'filled|gt:-1|numeric',
            'offer_end_date'=>'filled|after_or_equal:now'
            
    ]);
        

        $result = DB::table('items')
              ->where('id', $id)
              ->update(
                
                $request->except('id' , 'rating')

            );
        
        if ($result == 1){
            return "item is Updated successfully";
        }
        else{
            return "item is not updated";
        }
    
    }
    function DeleteItem($id){
        
        $result = DB::table('items')->where("id" , "=" , $id)->delete();

        if($result == 1)
        {
            return "The item with id:$id was deleted successfully";
        }
        else{
            return "The item deletion failed";
        }
    }
}
