<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Category;
use Validator;


class CategoryController extends Controller
{
    public function AllCategories(){

        $category = Category::latest()->get();
        return response()->json(['categoryList' => $category]);

    }//End Method

    public function store(Request $request)
    {
        $validator = Validator:: make($request->all(),[
        
            'category_name' => 'required|max:200',
            'description' => 'required|max:200',
            
        ]);
        if($validator->fails())
        {
            return response()->json([
                'validate_err'=>$validator->errors(),
            ],422);
        }
        else {
            $category = Category::create($request->all());
            return response()->json($category, 201);
    }
       
    }//End Method


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|max:200',
            'description' => 'required|max:200'                
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'validate_err' => $validator->errors(),
        ], 422);
    }
     
        // Update the employee in the database
        $category = Category::findOrFail($id);
         // Update the employee fields
         $category->category_name = $request->category_name;
         $category->description = $request->description;  
         $category->save();
    
        return response()->json($category, 200);
    }//End Method

    public function destroy($id)
    {
        $category = Category::find($id);
    
        $category->delete();
    
        return response()->json($category, 200);
    }//End Method
}
