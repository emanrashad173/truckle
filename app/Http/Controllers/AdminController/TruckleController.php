<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Truckle;
use App\Http\Controllers\Controller;

class TruckleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $truckles = Truckle::orderBy('created_at' , 'desc')->get();
        return view('admin.truckles.index' , compact('truckles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.truckles.create');
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
            'ar_name'  => 'required|string',
            'en_name'  => 'required|string',
            'hi_name'  => 'required|string',

        ]);
        // create a new truckle
        Truckle::create($request->all());
        flash('تم أنشاء  الشاحنة  بنجاح')->success();
        return redirect()->route('Truckle');
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
        $truckle = Truckle::findOrFail($id);
        return view('admin.truckles.edit' , compact('truckle'));
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
            'ar_name'  => 'required|string',
            'en_name'  => 'required|string',
            'hi_name'  => 'required|string',

        ]);
        // update  a  truckle
        $truckle = Truckle::findOrFail($id);

        $truckle->update([
            'ar_name'  => $request->ar_name == null ? $truckle->ar_name : $request->ar_name,
            'en_name'  => $request->en_name == null ? $truckle->en_name : $request->en_name,
            'hi_name'  => $request->hi_name == null ? $truckle->hi_name : $request->hi_name,

        ]);
        flash('تم تعديل بيانات  الشاحنة  بنجاح')->success();
        return redirect()->route('Truckle');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $truckle = Truckle::findOrFail($id);
        $truckle->delete();
        flash('تم حذف بيانات  الشاحنة  بنجاح')->success();
        return redirect()->route('Truckle');
    }
}
