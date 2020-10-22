<?php

namespace App\Http\Controllers\AdminController;

use App\City;

use App\Http\Controllers\Controller;
use App\User;
use App\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use Image;
use App\Country;
use App\Order;
use App\UserPhotos;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $users = User::where('type', 1)->get();
         return view('admin.users.index', compact('users'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        return view('admin.users.create');
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
            'email'                 => 'required|email',
            'photo'                 => 'required|mimes:jpeg,bmp,png,jpg|max:5000',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
        $phoneCheck = User::where('phone_number',$request->phone_number)->where('type',1)->first();
        if($phoneCheck)
        {
            flash('رقم المستخدم مسجل من قبل ')->error();
            return redirect()->back();
        }

        
        $user= User::create([
            'phone_number'    => $request->phone_number,
            'name'            => $request->name,
            'email'            => $request->email,
            'active'          => 1,
            'type'            => 1,
            'photo'           => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/clients'),
            'password'        => Hash::make($request->password),
            'api_token'       => $request->token,
        ]);
        flash('تم أنشاء المستخدم  بنجاح')->success();
        return redirect('admin/users');
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
         $user = User::where('type', 1)->findOrfail($id);
         return view('admin.users.edit', compact('user'));
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
            'phone_number'   => 'required',
            'name'           => 'required|max:40',
            'email'          => 'required|email',
            'photo'          => 'sometimes|mimes:jpeg,bmp,png,jpg|max:5000',

        ]);

        $users= User::find($id);
        $phoneCheck = User::where('phone_number',$request->phone_number)->where('type',1)->whereNotIn('id',[$users->id])->first();
        if($phoneCheck)
        {
            flash('رقم المستخدم مسجل من قبل ')->error();
            return redirect()->back();
        }

        User::where('id',$id)->first()->update([
            'phone_number'    => $request->phone_number,
            'name'            => $request->name,
            'email'           => $request->email,
            'photo'           => $request->file('photo') == null ? $users->photo : UploadImage($request->file('photo'), 'photo', '/uploads/clients'),

            
        ]);

        return redirect()->back()->with('information', 'تم تعديل بيانات المستخدم');
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
        flash('تم تعديل كلمة المرور المستخدم')->success();
        return redirect()->back();
    }

    public function update_privacy(Request $request, $id)
    {

      $this->validate($request, [
          'active' => 'required',
      ]);
      $users = User::findOrfail($id);
      $users->active = $request->active;
      $users->save();
      return redirect()->back()->with('information', 'تم تعديل اعدادات المستخدم');

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
        $users = Order::whereUser_id($id)->get();
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
