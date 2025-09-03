<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function orders()
{
        $orders = Order::orderBy('created_at','ASC')->paginate(12);
        return view('admin.orders',compact('orders'));
}
    
}
