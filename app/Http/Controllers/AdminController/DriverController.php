<?php

namespace App\Http\Controllers\AdminController;

use App\City;

use App\Http\Controllers\Controller;
use App\User;
use App\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Image;
use App\Country;
use App\Category;
use App\Order;
use  App\Truckle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $drivers = User::where('type', 2)->get();
         return view('admin.drivers.index', compact('drivers'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
        return view('admin.drivers.create');
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
            'phone_number'          => 'required',
            'name'                  => 'required|max:40',
            'last_name'             => 'required|max:40',
            'email'                 => 'required|email',
            'category_id'           => 'required|exists:categories,id',
            'photo'                 => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'identity'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'license'               => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'car_form'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'transportation_card'   => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'insurance'             => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
        $phoneCheck = User::where('phone_number',$request->phone_number)->where('type',2)->first();
        if($phoneCheck)
        {
            flash('رقم المستخدم مسجل من قبل ')->error();
            return redirect()->back();
        }

        $provider= User::create([
            'phone_number'    => $request->phone_number,
            'name'            => $request->name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'active'          => 1,
            'type'            => 2,
            'country_id'      => $request->country_id,
            'truckle_id'      => $request->truckle_id,
            'category_id'     => $request->category_id,
            'photo'                 => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/drivers'),
            'identity'              => $request->file('identity') == null ? null : UploadImage($request->file('identity'), 'photo', '/uploads/drivers/document/identity'),
            'license'               => $request->file('license') == null ? null : UploadImage($request->file('license'), 'photo', '/uploads/drivers/document/license'),
            'car_form'              => $request->file('car_form') == null ? null : UploadImage($request->file('car_form'), 'photo', '/uploads/drivers/document/car_form'),
            'transportation_card'   => $request->file('transportation_card') == null ? null : UploadImage($request->file('transportation_card'), 'photo', '/uploads/drivers/document/transportation_card'),
            'insurance'             => $request->file('insurance') == null ? null : UploadImage($request->file('insurance'), 'photo', '/uploads/drivers/document/insurance'),
            'password'        => Hash::make($request->password),
            'api_token'       => $request->token,
        ]);
        flash('تم أنشاء السائق بنجاح')->success();
        return redirect('admin/drivers');
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
     {   $countries = Country::all();
         $truckles = Truckle::all();
         $categories = Category::all();
         $driver = User::where('type', 2)->findOrfail($id);
         return view('admin.drivers.edit', compact('driver','countries','truckles','categories'));
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

        $this->validate($request, [
            'phone_number'          => 'required',
            'name'                  => 'required|max:40',
            'last_name'             => 'required|max:40',
            'email'                 => 'required|email',
            'category_id'           => 'required|exists:categories,id',
            'photo'                 => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'identity'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'license'               => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'car_form'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'transportation_card'   => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'insurance'             => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
        ]);
        $users= User::find($id);
        $phoneCheck = User::where('phone_number',$request->phone_number)->where('type',2)->whereNotIn('id',[$users->id])->first();
        if($phoneCheck)
        {
            flash('رقم المستخدم مسجل من قبل ')->error();
            return redirect()->back();
        }



        User::where('id',$id)->first()->update([
            'phone_number'    => $request->phone_number,
            'name'            => $request->name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'country_id'      => $request->country_id,
            'truckle_id'      => $request->truckle_id,
            'category_id'     => $request->category_id,
            'photo'                        => $request->file('photo') == null ? $users->photo : UploadImage($request->file('photo'), 'photo', '/uploads/drivers'),
            'identity'                     => $request->file('identity') == null ? $users->identity : UploadImage($request->file('identity'), 'photo', '/uploads/drivers/document/identity'),
            'license'                      => $request->file('license') == null ? $users->license : UploadImage($request->file('license'), 'photo', '/uploads/drivers/document/license'),
            'car_form'                     => $request->file('car_form') == null ? $users->car_form : UploadImage($request->file('car_form'), 'photo', '/uploads/drivers/document/car_form'),
            'transportation_card'          => $request->file('transportation_card') == null ? $users->transportation_card : UploadImage($request->file('transportation_card'), 'photo', '/uploads/drivers/document/transportation_card'),
            'insurance'                    => $request->file('insurance') == null ? $users->insurance : UploadImage($request->file('insurance'), 'photo', '/uploads/drivers/document/insurance'),

           
            
        ]);
        
        
        
       

        return redirect()->back()->with('information', 'تم تعديل بيانات السائق');
    }
    
    
    public function update_pass(Request $request, $id)
    {
        //
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',

        ]);
        $users = User::findOrfail($id);
        $users->password = Hash::make($request->password);

        $users->save();

        return redirect()->back()->with('information', 'تم تعديل كلمة المرور السائق');
    }
    
    public function update_privacy(Request $request, $id)
    {

      $this->validate($request, [
          'active' => 'required',
      ]);
      $users = User::findOrfail($id);
      $users->active = $request->active;
      $users->save();       
      return redirect()->back()->with('information', 'تم تعديل اعدادات السائق');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
            $user = User::find($id);
            $users = Order::whereDriver_id($id)->get();
            if ($users->count() > 0)
            {
                flash('لا يمكن  الحذف')->error();
                return back();
            }else{
                $user->delete();
                flash('تم الحذف  بنجاح')->success();
                return back();
            }
            
    }

}
