<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Customer;
use Validator;

class CustomerController extends Controller
{
    public function AllCustomers(){

        $customer = Customer::latest()->get();
        return response()->json($customer);
    
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
        else {
            $customer = Customer::create($request->all());
            return response()->json($customer, 201);
        }
       
    }//End Method

    public function edit($id)
    {
        $customer= Customer::findorFail($id);
        return response()->json($customer, 200);
    }//End Method


    public function update(Request $request, $id)
{
    // Update the employee in the database
    $customer = Customer::findOrFail($id);
    $customer->update([
        'code' => $request->input('code'),
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
        'address' => $request->input('address'),
        'documents' => $request->input('documents'),
        'image' => $request->input('image')

    ]);

    return response()->json($customer, 200);
}//End Method

    public function destroy($id)
{
    $customer = Customer::find($id);

    $customer->delete();

    return response()->json($customer, 200);
}//End Method
}
