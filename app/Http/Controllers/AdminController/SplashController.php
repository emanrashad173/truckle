<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Splash;
class SplashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $splashes = Splash::orderBy('created_at' , 'desc')->get();
        return view('admin.splashes.index' , compact('splashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.splashes.create');
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
            'title'    => 'required|string',
            'details'  => 'required',
            'photo'    => 'required|mimes:jpeg,bmp,png,jpg|max:5000',    
        ]);
        // create new splash
        Splash::create([
            'title' => $request->title,
            'details' => $request->details,
            'photo'   => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/intros'),
        ]);
        flash('تم أنشاء الأنترو بنجاح')->success();
        return redirect()->route('Splash');

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
        $splash = Splash::findOrFail($id);
        return view('admin.splashes.edit' , compact('splash'));
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
            'title'    => 'required|string',
            'details'  => 'required',
            'photo'    => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',    
        ]);
        $splash = Splash::findOrFail($id);
        $splash->update([
            'title'   => $request->title == null ? $splash->title : $request->title,
            'details' => $request->details == null ? $splash->details : $request->details,
            'photo'   => $request->file('photo') == null ? $splash->photo : UploadImage($request->file('photo'), 'photo', '/uploads/intros'),
        ]);
        flash('تم تعديل الأنترو بنجاح')->success();
        return redirect()->route('Splash');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $splash = Splash::findOrFail($id);
        $splash->delete();
        flash('تم حذف الأنترو بنجاح')->success();
        return redirect()->route('Splash');
    }
}
