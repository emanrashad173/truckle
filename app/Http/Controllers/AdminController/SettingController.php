<?php

namespace App\Http\Controllers\AdminController;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Redirect;
use Image;
use Auth;
use App\Permission;

class SettingController extends Controller
{
    //
    public function index()
    {



            $settings =settings();
            return view('admin.settings.index',compact('settings'));

//
    }
    public function store(Request $request)
    {
//        dd($request->deposit_type);
        $this->validate($request, [
            "bank_name"  => "required|string|max:255",
            'account_number'=> 'required|numeric',
            "commission"  => "required|numeric",
            'order_time'=> 'required|numeric',
            'phone_number'=> 'required|numeric',
            'order_count'=> 'required|numeric',
        ]);

        Setting::where('id',1)->first()->update($request->all());

        return Redirect::back()->with('success', 'تم حفظ البيانات بنجاح');


    }

}
