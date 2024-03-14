<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Employee;
use Validator;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function AllEmployee(){

        $employee = Employee::latest()->get();
        return response()->json($employee);
    
    }//End Method

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(),[
        
            'code'=> 'required|max:200',
            'name' => 'required|max:200',
            'email' => 'required|unique:employees|max:200',
            'phone' => 'required|max:10|min:10',
            'position' => 'required|max:200',
            'salary' => 'required|max:200',
            'join_date' => 'required|max:200'
            
        ]);
  
    $image = $request->file('image');
    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    Image::make($image)->resize(300,300)->save('upload/employee/'.$name_gen);
    $save_url ='upload/employee/'.$name_gen;

    $documents = $request->file('documents');
    $name_gen =hexdec(uniqid()).'.'.$documents->getClientOriginalExtension();
    Image::make($documents)->resize(300,300)->save('upload/documents/'.$name_gen);
    $save_url1 = 'upload/documents/'.$name_gen;

        if($validator->fails())
        {
            return response()->json([
                'validate_err'=>$validator->errors(),
            ],422);
        }
        else {
        $employee = Employee::create([
            'code'=>$request->code,
            'name' =>$request->name,
            'email' =>$request->email,
            'phone' =>$request->phone,
            'position' =>$request->position,
            'salary' =>$request->salary,
            'join_date' =>$request->join_date,
            'documents' =>$save_url1,
            'image' =>$save_url,
            'created_at' =>Carbon::now()
    
        ]);

    }
        return response()->json($employee, 200);
        
    }//End Method

    public function edit($id)
    {
        $employee= Employee::findorFail($id);
        return response()->json($employee, 200);
    }//End Method

    public function update(Request $request, $id)
    {
  
    $validator = Validator::make($request->all(), [
            'code'=> 'required|max:200',
            'name' => 'required|max:200',
            'email' => 'required|max:200',
            'phone' => 'required|max:10|min:10',
            'position' => 'required|max:200',
            'salary' => 'required|max:200',
            'join_date' => 'required|max:200'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'validate_err' => $validator->errors(),
        ], 422);
    }
     

    $employee = Employee::findOrFail($id);

       // Update the employee fields
       $employee->code = $request->code;
       $employee->name = $request->name;
       $employee->email = $request->email;
       $employee->phone = $request->phone;
       $employee->position = $request->position;
       $employee->salary = $request->salary;
       $employee->join_date = $request->join_date;
       
     if($request->hasFile('image')) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/employee/'.$name_gen);
        $employee->image ='upload/employee/'.$name_gen;
    }

    if($request->hasFile('documents')) {
        $documents = $request->file('documents');
        $name_gen =hexdec(uniqid()).'.'.$documents->getClientOriginalExtension();
        Image::make($documents)->resize(300,300)->save('upload/documents/'.$name_gen);
        $employee->documents = 'upload/documents/'.$name_gen;
    }     
    $employee->save();
  
     return response()->json($employee, 200);

   }//End Method

     public function destroy($id)
    {
    $employee = Employee::find($id);

    $employee->delete();

    return response()->json($employee, 200);
    }//End Method

}
