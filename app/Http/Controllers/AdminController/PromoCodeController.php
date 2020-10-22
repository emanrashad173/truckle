<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\PromoCode;
use App\Order;
use App\Http\Controllers\Controller;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promo_codes = PromoCode::orderBy('created_at' , 'desc')->get();
        return view('admin.promo_codes.index' , compact('promo_codes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promo_codes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name'        => 'required|string',
            'percentage'  => 'required|numeric',

        ]);
        // create a new promo_code
        PromoCode::create([
            'name' => $request->name,
            'percentage' => $request->percentage,
        ]);
        flash('تم الاضافة بنجاح')->success();
        return redirect()->route('PromoCode');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promo_code = PromoCode::findOrFail($id);
        return view('admin.promo_codes.edit' , compact('promo_code'));
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
        $this->validate($request , [
          'name'        => 'required|string',
          'percentage'  => 'required|numeric',

        ]);
        // update  a  promo_code
        $promo_code = PromoCode::findOrFail($id);

        $promo_code->update([
            'name'        => $request->name == null ? $promo_code->name : $request->name,
            'percentage'  => $request->percentage == null ? $promo_code->percentage : $request->percentage,

        ]);
        flash('تم التعديل بنجاح')->success();
        return redirect()->route('PromoCode');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        $promo_code = PromoCode::findOrFail($id);
        $users = Order::whereCode($promo_code->name)->get();
        if ($users->count() > 0)
        {
            flash('لا يمكن  الحذف')->error();
            return redirect()->route('PromoCode');
        }else{
            $city->delete();
            flash('تم الحذف  بنجاح')->success();
            return redirect()->route('PromoCode');
        }
    }
}
