<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Supplier;
use Validator;

class SupplierController extends Controller
{
    public function AllSuppliers(){

        $supplier = Supplier::latest()->get();
        return response()->json($supplier);
    
    }//End Method

    public function store(Request $request)
    {
        $validator = Validator:: make($request->all(),[
        
            'code'=> 'required|max:200',
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:10|min:10',
            'address' => 'required|max:200',
            'product' => 'required|max:200',
            
        ]);
        if($validator->fails())
        {
            return response()->json([
                'validate_err'=>$validator->errors(),
            ],422);
        }
        else {
            $supplier = Supplier::create($request->all());
        return response()->json($supplier, 201);
        }
      
    }//End Method

    public function view($id)
    {
        $supplier= Supplier::findorFail($id);
        return response()->json($supplier, 200);
    }//End Method

    public function edit($id)
    {
        $supplier= Supplier::findorFail($id);
        return response()->json($supplier, 200);
    }//End Method

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'product' => $request->input('product'),
            'type' => $request->input('type'),
            'documents' => $request->input('documents'),
            'image' => $request->input('image')
    
        ]);
    
        return response()->json($supplier, 200);
    }//End Method

    public function destroy($id)
{
    $supplier = Supplier::find($id);

    $supplier->delete();

    return response()->json($supplier, 200);
}//End Method
}
