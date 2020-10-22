<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function new()
    {
        $orders = Order::where('state','confirmed')->orderBy('created_at' , 'desc')->get();
        return view('admin.orders.new' , compact('orders'));
    }
    public function active()
    {
        $orders = Order::where('state','active')->orderBy('created_at' , 'desc')->get();
        return view('admin.orders.active' , compact('orders'));
    }
    public function completed()
    {
        $orders = Order::where('state','completed')->orderBy('created_at' , 'desc')->get();
        return view('admin.orders.completed' , compact('orders'));
    }
    public function rejected()
    {
        $orders = Order::where('state','rejected')->orderBy('created_at' , 'desc')->get();
        return view('admin.orders.rejected' , compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order= Order::find($id);
        return view('admin.orders.show' , compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        flash('تم حذف بيانات  الطلب  بنجاح')->success();
        return back();
    }
}
