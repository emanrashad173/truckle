<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Country;
use App\City;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::orderBy('created_at' , 'desc')->get();
        return view('admin.countries.index' , compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
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
            'ar_name' => 'required' , 
            'en_name' => 'required',
            'hi_name' => 'required',
        ]);
        // create a new country
        Country::create([
            'ar_name'  => $request->ar_name,
            'en_name'  => $request->en_name,
            'hi_name'  => $request->hi_name,
        ]);
        flash('تم أنشاء الدولة  بنجاح')->success();
        return redirect()->route('Country');
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
        $country = Country::findOrFail($id);
        return view('admin.countries.edit' , compact('country'));
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
            'ar_name' => 'required' , 
            'en_name' => 'required',
            'hi_name' => 'required',


        ]);
        // Update country value
        $country = Country::findOrFail($id);
        $country->update([
            'ar_name'  => $request->ar_name == null ? $country->ar_name : $request->ar_name,
            'en_name'  => $request->en_name == null ? $country->en_name : $request->en_name,
            'hi_name'  => $request->hi_name == null ? $country->hi_name : $request->hi_name,

        ]);
        flash('تم تعديل بيانات الدولة  بنجاح')->success();
        return redirect()->route('Country');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        flash('تم مسح بيانات الدولة  بنجاح')->success();
        return redirect()->route('Country');
        
    }
}
