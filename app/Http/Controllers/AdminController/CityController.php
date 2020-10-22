<?php

namespace App\Http\Controllers\AdminController;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use App\User;
use App\Order;
use DB;
use Auth;

class Citycontroller extends Controller
{
    public function index()
    {
        $cities = City::orderBY('created_at','desc')->get();
        return view('admin.cities.index',compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.cities.create' , compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "ar_name"        => "required|string|max:20",
            "en_name"        => "required|string|max:20",
            "hi_name"        => "required|string|max:20",
            "country_id"     => "required|exists:countries,id",
        ]);
        City::create([
            'country_id' => $request->country_id,
            'ar_name'    => $request->ar_name,
            'en_name'    => $request->en_name,
            'hi_name'    => $request->hi_name,

        ]);
        flash('تم أنشاء  المدينة  بنجاح')->success();
        return redirect()->route('City');
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
        $city = City::findOrfail($id);
        $countries = Country::all();
        return view('admin.cities.edit',compact('city' , 'countries'));
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
        $cities = City::findOrfail($id);
        $this->validate($request, [
            "ar_name"        => "required|string|max:20",
            "en_name"        => "required|string|max:20",
            "hi_name"        => "required|string|max:20",
            "country_id"     => "required|exists:countries,id",
        ]);
        $cities->update([
            'country_id' => $request->country_id == null ? $cities->country_id : $request->country_id,
            'ar_name'    => $request->ar_name == null ? $cities->ar_name : $request->ar_name,
            'en_name'    => $request->en_name == null ? $cities->en_name : $request->en_name,
            'hi_name'    => $request->hi_name == null ? $cities->hi_name : $request->hi_name,

        ]);
        flash('تم  تعديل اسم المدينه  بنجاح')->success();
        return redirect()->route('City');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cities = City::find($id);
        $users = Order::whereCity_id($id)->get();
        if ($users->count() > 0)
        {
            flash('لا يمكن  الحذف')->error();
            return redirect()->route('City');
        }else{
            $city->delete();
            flash('تم الحذف  بنجاح')->success();
            return redirect()->route('City');
        }
    }
}
