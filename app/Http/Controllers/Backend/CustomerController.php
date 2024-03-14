<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Customer;
use Validator;
use Intervention\Image\Facades\Image;


class CustomerController extends Controller
{
    public function AllCustomers(){

        $customer = Customer::latest()->get();
        return response()->json( ['customers' => $customer]);
    
    }//End Method

    public function uploads(Request $request)
    {

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();  
            
            Image::make($image)->resize(300,300)->save('upload/customers/' . $name_gen, 90); 
            
            // return response()->json(['message' => 'File uploaded successfully', 'file_name' => $name_gen], 200);
            return response()->json(['message' => 'File uploaded successfully', 'image' => $name_gen], 200);
           
        } else {
            // If no file is provided in the request
            // return response()->json(['error' => 'No file provided'], 422);
            return null;
        }

    }//End Method

    public function store(Request $request)
    {

        $validator = Validator:: make($request->all(),[
        
            'code'=> 'required|max:200',
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:10|min:10',
            'address' => 'required|max:200',
            
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validate_err'=>$validator->errors(),
            ],422);
        }
        $image_name = $request->hasFile('image') ? $this->uploads($request) : null;
dd($image_name);
        // $image_name = $this->upload($request);
        
            // $image = $request->file('image');
            // $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();    
            // Image::make($image)->resize(300,300)->save(('upload/customers/' . $name_gen), 90); 
            // $save_url ='upload/customers/'.$name_gen;

            // Check if the request contains an uploaded file
    // if ($request->hasFile('image')) {
    //     $image = $request->file('image');
    //     $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //     $image->save('upload/customers/', $name_gen,90);
    //     // $image_path = 'upload/customers/' . $name_gen;
    // }
        if($image_name !==null){

        
            $customer = Customer::create([
                'code' => $request->code,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $image_name,
                // 'documents' => $request->documents,
               
            ]);   
        
            return response()->json($customer,200);
        } else {

            return response()->json(['error'=> 'No file Provided'],422);
        }
    }//End Method

//     public function edit($id)
//     {
//         $customer= Customer::findorFail($id);
//         return response()->json($customer, 200);
//     }//End Method


    public function update(Request $request, $id)
{

    $validator = Validator::make($request->all(), [
        'code'=> 'required|max:200',
        'name' => 'required|max:200',
        'email' => 'required|unique:customers|max:200',
        'phone' => 'required|max:10|min:10',
        'address' => 'required|max:200',
        
]);

if ($validator->fails()) {
    return response()->json([
        'validate_err' => $validator->errors(),
    ], 422);
}
 
    // Update the employee in the database
    $customer = Customer::findOrFail($id);
     // Update the employee fields
     $customer->code = $request->code;
     $customer->name = $request->name;
     $customer->email = $request->email;
     $customer->phone = $request->phone;
     $customer->address =$request->address;
     
     
   if($request->hasFile('image')) {
      $image = $request->file('image');
      $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
      Image::make($image)->resize(300,300)->save('upload/customers/'.$name_gen);
      $customer->image ='upload/customers/'.$name_gen;
  }
    $customer->save();

    return response()->json($customer, 200);
}//End Method

    public function destroy($id)
{
    $customer = Customer::find($id);

    $customer->delete();

    return response()->json($customer, 200);
}//End Method
}
