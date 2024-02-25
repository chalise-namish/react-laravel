<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\SupplierController;

//Login Routes 

Route::post('/login',[AuthController::class,'Login']);

//Register Routes 

Route::post('/register',[AuthController::class,'Register']);

//Current User Routes 

Route::get('/user',[UserController::class,'User'])->middleware('auth:api');


Route::controller(EmployeeController::class)->group(function(){

    Route::get('/allemployees','AllEmployee');
    Route::post('/addemployee','store');
    Route::get('/editemployee/{id}','edit');
    Route::put('/updatemployee/{id}','update');
    Route::delete('/deletemployee/{id}','destroy');

});

Route::controller(CustomerController::class)->group(function(){
    Route::get('/allcustomers','AllCustomers');
    Route::post('/addcustomer','store');
    Route::get('/editcustomer/{id}','edit');
    Route::put('/updatecustomer/{id}','update');
    Route::delete('/deletecustomer/{id}','destroy');
});

Route::controller(SupplierController::class)->group(function(){
    Route::get('/allsuppliers','AllSuppliers');
    Route::post('/addsupplier','store');
    Route::get('/detailsupplier/{id}','view');
    Route::get('/editsupplier/{id}','edit');
    Route::put('/updatesupplier/{id}','update');
    Route::delete('/deletesupplier/{id}','destroy');
});



