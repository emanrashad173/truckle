<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\User;
use App\Setting;
use App\UserDevice;
use App\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->where('type',1)->count();
        $admins = DB::table('admins')->count();
        $countries = DB::table('countries')->count();
        $cities = DB::table('cities')->count();
        $categories = DB::table('categories')->count();
        $drivers = DB::table('users')->where('type',2)->count();
        $neworders = DB::table('orders')->where('state','confirmed')->count();
        $activedorders = DB::table('orders')->where('state','active')->count();
        $completedorders = DB::table('orders')->where('state','completed')->count();
        return view('admin.home' , compact('users','admins' , 'drivers' ,'cities', 'categories' ,  'countries','neworders','activedorders','completedorders'));
    }
    
    public function public_notifications()
    {
        return view('admin.public_notifications');
    }
   public function store_public_notifications(Request $request)
    { 
        
        $this->validate($request , [
        "ar_title" => "required",
        "ar_message" => "required",
    ]);
    // Create New Notification
    $users = User::all()->pluck('id');
    foreach ($users as $user)
    {
        // Notification type 1 to public
        saveNotification( $user, $request->ar_title ,null , null, '1', $request->ar_message ,null , null , null);
    }
    $devicesTokens = UserDevice::all()->pluck('device_token')->toArray();
    if ($devicesTokens) {
        sendMultiNotification($request->ar_title, $request->ar_message ,$devicesTokens);
    }
    flash('تم ارسال الاشعار لجميع مستخدمي التطبيق')->success();
    return redirect()->route('public_notifications');


    }
    public function selected_notifications()
    {
        return view('admin.public_selected_notify');
    }
   public function store_selected_notifications(Request $request)
    {
        $this->validate($request, [
            'user_id*'    => 'required',
            'ar_title'    => 'required',
            'ar_body'     => 'required',
        ]);
        foreach ($request->user_id as $one) {
            $user = User::find($one);
            $devicesTokens = UserDevice::where('user_id', $user->id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification($request->ar_title, $request->ar_body, $devicesTokens);
            }
            saveNotification( $user->id, $request->ar_title ,null , null, '1', $request->ar_body ,null , null , null);
        }
        flash('تم ارسال الاشعار للمستخدمين بنجاح');
        return back();
    }

    public function client_notifications()
    {
        return view('admin.public_client_notifications');
    }

    public function store_client_notifications(Request $request)
    {
        $this->validate($request, [
            'ar_title'    => 'required',
            'ar_message'     => 'required',
        ]);
        $users = User::where('type',1)->pluck('id');
    foreach ($users as $user)
    {
        // Notification type 1 to public
        $devicesTokens = UserDevice::where('user_id', $user)
        ->get()
        ->pluck('device_token')
        ->toArray();
        if ($devicesTokens) {
            sendMultiNotification($request->ar_title, $request->ar_message ,$devicesTokens);
        }
        saveNotification( $user, $request->ar_title ,null ,null ,  '1', $request->ar_message ,null , null , null);
    }
        flash('تم ارسال الاشعار لعملاء التطبيق')->success();
        return redirect()->route('public_client_notifications');
    }
    
    public function driver_notifications()
    {
        return view('admin.public_driver_notifications');
    }
   public function store_driver_notifications(Request $request)
    {
        $this->validate($request, [
            'ar_title'    => 'required',
            'ar_message'     => 'required',
        ]);
        $users = User::where('type',2)->pluck('id');
    foreach ($users as $user)
    {
        $devicesTokens = UserDevice::where('user_id', $user)
        ->get()
        ->pluck('device_token')
        ->toArray();
        if ($devicesTokens) {
            sendMultiNotification($request->ar_title, $request->ar_message ,$devicesTokens);
        }
        saveNotification( $user, $request->ar_title ,null ,null ,  '1', $request->ar_message ,null , null , null);
    }
    flash('تم ارسال الاشعار لسائقين التطبيق')->success();
    return redirect()->route('public_driver_notifications');
    }

    public function changeLogo(){

        $image = Setting::find(1)->image;
        return view('admin/admins/change-logo',compact('image'));
    }
    public function LogoImage(Request $request){
        $setting = Setting::find(1);
        $this->validate($request, ['image' => 'required|mimes:jpeg,jpg,png']);
        $setting->update([
            'image' => $request->image == null ? $setting->image : UploadImageEdit($request->image, 'photo', '/uploads/logo', $setting->image)
        ]);
        flash('تم تعديل الصورة بنجاح');
        return back();
    }

}
