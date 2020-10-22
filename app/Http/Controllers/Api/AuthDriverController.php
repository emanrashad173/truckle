<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App;
use Auth;
use App\User;
use App\City;
use App\ResetPassword;
use Illuminate\Support\Facades\Hash;

class AuthDriverController extends Controller
{

    public function registerMobile(Request $request) {
        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','2')->first();
        if($phoneCheck)
        {
            $errors = ['key'=>'message',
            'value'=> trans('messages.sorry_this_user_register_before')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $code = mt_rand(1000, 9999);

        $jsonObj = array(
            'mobile' => '',
            'password' => '',
            'sender'=>'TQNEE',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في تراكل هو :'.$code,

            'msgId' => rand(1,99999),

            'timeSend' => '0',

            'dateSend' => '0',

            'deleteKey' => '55348',
            'lang' => '3',
            'applicationType' => 68,
            );
            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);
            $created = App\PhoneVerification::create([
                'code'=>$code,
                'phone_number'=>$request->phone_number
            ]);


            return $created
            ? ApiController::respondWithSuccess( trans('messages.success_send_code'))
            : ApiController::respondWithServerErrorObject();
     }

    
    public function verifyPhone(Request $request,$lang){

        $rules = [
            'code' => 'required',
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->first();
        if ($user){

            if($user->code == $request->code){
                $successLogin = ['key'=>'message',
                'value'=> trans('messages.success_code')
            ];
                return ApiController::respondWithSuccess($successLogin);
            }else{
                $errorsLogin = ['key'=>'message',
                    'value'=> trans('messages.error_code')
                ];
                return ApiController::respondWithErrorClient(array($errorsLogin));
            }

        }else{

            $errorsLogin = ['key'=>'message',
            'value'=> trans('messages.error_code')
        ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }
        

    }


    public function resendCode(Request $request){

        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));


        $code = mt_rand(1000, 9999);
        $jsonObj = array(
            'mobile' => '',
            'password' => '',
            'sender'=>'TQNEE',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في تراكل هو :'.$code,
            'msgId' => rand(1,99999),
            'timeSend' => '0',
            'dateSend' => '0',
            'deleteKey' => '55348',
            'lang' => '3',
            'applicationType' => 68,
         );
        // دالة الإرسال JOSN
        $result=$this->sendSMS($jsonObj);
        $created = App\PhoneVerification::create([
            'code'=>$code,
            'phone_number'=>$request->phone_number
        ]);

            return $created
                ? ApiController::respondWithSuccess( trans('messages.success_send_code'))
                : ApiController::respondWithServerErrorObject();
    }


    public function register(Request $request) {

        $rules = [
            'phone_number'          => 'required',
            'email'                 => 'nullable|unique:users',
            'name'                  => 'required',
            'last_name'             => 'required',
            'country_id'            => 'required|exists:countries,id',
            'truckle_id'            => 'required|exists:truckles,id',
            'category_id'           => 'required|exists:categories,id',
            'identity'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'license'               => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'car_form'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'transportation_card'   => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'insurance'             => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'device_token'          => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','2')->first();
        if($phoneCheck)
        {
            $errors = ['key'=>'message',
            'value'=> trans('messages.sorry_this_user_register_before')
            ];
                return ApiController::respondWithErrorArray(array($errors));
        }
        $all=[];

        $user = App\User::create([
            'phone_number'          => $request->phone_number,
            'name'                  => $request->name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'active'                => 0,
            'type'                  => 2,
            'country_id'            => $request->country_id,
            'truckle_id'            => $request->truckle_id,
            'category_id'           => $request->category_id,
            'identity'              => $request->file('identity') == null ? null : UploadImage($request->file('identity'), 'photo', '/uploads/drivers/document/identity'),
            'license'               => $request->file('license') == null ? null : UploadImage($request->file('license'), 'photo', '/uploads/drivers/document/license'),
            'car_form'              => $request->file('car_form') == null ? null : UploadImage($request->file('car_form'), 'photo', '/uploads/drivers/document/car_form'),
            'transportation_card'   => $request->file('transportation_card') == null ? null : UploadImage($request->file('transportation_card'), 'photo', '/uploads/drivers/document/transportation_card'),
            'insurance'             => $request->file('insurance') == null ? null : UploadImage($request->file('insurance'), 'photo', '/uploads/drivers/document/insurance'),
            'password'              => Hash::make($request->password),
        ]);
       
        $user->update(['api_token' => generateApiToken($user->id, 10)]);

        $user_rate = App\RateDriver::where('rated_id',$user->id)->get();
        $count_rate = $user_rate->count();
        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null) 
         {
             $category = App\Category::where('id', $request->category_id)->first();
             $category_name = $category->ar_name;
             
         } 
         elseif($request->header('X-localization')=="en")
         {
             $category = App\Category::where('id', $request->category_id)->first();
             $category_name = $category->en_name;
         }
         elseif($request->header('X-localization')=="hi")
         {
             $category = App\Category::where('id', $request->category_id)->first();
             $category_name = $category->hi_name;

         }

         App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->delete();
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'category_id'   => intval($user->category_id),
                'category'      =>$category_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'      => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'active'        =>$user->active,
                'type'          =>$user->type,
                'count_rate'    =>$count_rate,
                'api_token'     =>$user->api_token,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);
        //save_device_token....
        $created = ApiController::createUserDeviceToken($user->id, $request->device_token, $request->device_type);
        return $user
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
    }



    public function login(Request $request) {

        $rules = [
            'phone_number'  => 'required',
            'password'      => 'required',
            'device_token'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));


            if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password, 'type'=>2])) {

                if (Auth::user()->active == 0){
                    $errors = ['key'=>'message',
                        'value'=> trans('messages.Sorry_your_membership_was_stopped_by_Management')
                    ];
                    return ApiController::respondWithErrorArray(array($errors));
                }

                //save_device_token....
                $created = ApiController::createUserDeviceToken(Auth::user()->id, $request->device_token, $request->device_type);

                $all = App\User::where('phone_number', $request->phone_number)->first();
                $all->update(['api_token' => generateApiToken($all->id, 10)]);
                $user =  App\User::where('phone_number', $request->phone_number)->first();

                $user_rate = App\RateDriver::where('rated_id',$user->id)->get();
                $count_rate = $user_rate->count();
                if($request->header('X-localization')=="ar" || $request->header('X-localization')==null) 
                 {
                     $category_name = $user->category->ar_name;

                     
                 } 
                 elseif($request->header('X-localization')=="en")
                 {
                    $category_name = $user->category->en_name;

                 }
                 elseif($request->header('X-localization')=="hi")
                 {
                     $category_name = $user->category->hi_name;
        
                 }

                $all=[];
                array_push($all,[
                    'id'            =>$user->id,
                    'name'          =>$user->name,
                    'last_name'     =>$user->last_name,
                    'email'         =>$user->email,
                    'phone_number'  =>$user->phone_number,
                    'country_id'    =>$user->country_id,
                    'truckle_id'    =>$user->truckle_id,
                    'category_id'   =>$user->category_id,
                    'category'      =>$category_name,
                    'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity'.$user->identity),
                    'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license'.$user->license),
                    'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form'.$user->car_form),
                    'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card'.$user->transportation_card),
                    'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance'.$user->insurance),
                    'active'        =>$user->active,
                    'type'          =>$user->type,
                    'photo'         =>$user->photo == null ? null : asset('/uploads/drivers/'.$user->photo),
                    'rate'          =>intval($user->rate),
                    'count_rate'    =>$count_rate,
                    'api_token'     =>$user->api_token,
                    'created_at'    =>$user->created_at->format('Y-m-d'),
                ]);


                return $created
                    ? ApiController::respondWithSuccess($all)
                    : ApiController::respondWithServerErrorArray();
            }else{
                $user = User::wherePhone_number($request->phone_number)->first();
                if ($user == null)
                {
                    $errors = [
                        'key'=>'message',
                        'value'=>trans('messages.Wrong_phone'),
                    ];
                    return ApiController::respondWithErrorNOTFoundArray(array($errors));
                }else{
                    $errors = [
                        'key'=>'message',
                        'value'=>trans('messages.error_password'),
                    ];
                    return ApiController::respondWithErrorNOTFoundArray(array($errors));
                }
            }
    }


    public function forgetPassword(Request $request) {
        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = User::where('phone_number',$request->phone_number)
            ->where('type' , '2')
            ->first();

        if($user) {
            $code = mt_rand(1000, 9999);
            $jsonObj = array(
                'mobile' => '',
                'password' => '',
                'sender'=>'TQNEE',
                'numbers' => $request->phone_number,
                'msg'=>'كود التأكيد الخاص بك في تراكل هو :'.$code,
                'msgId' => rand(1,99999),
                'timeSend' => '0',
                'dateSend' => '0',
                'deleteKey' => '55348',
                'lang' => '3',
                'applicationType' => 68,
                );

            // دالة الإرسال JOSN
            $result=$this->sendSMS($jsonObj);

            $updated=  App\User::where('phone_number',$request->phone_number)->where('type' , '2')->update([
                'verification_code'=>$code,
            ]);
            $success = ['key'=>'message',
                'value'=> trans('messages.success_send_code')
            ];

                return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();
        }

        $errorsLogin = ['key'=>'message',
            'value'=> trans('messages.Wrong_phone')
        ];
        return ApiController::respondWithErrorClient(array($errorsLogin));
    }


    public function confirmResetCode(Request $request){

        $rules = [
            'phone_number' => 'required',
            'code' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= App\User::where('phone_number',$request->phone_number)->where('type' , '2')->where('verification_code',$request->code)->first();
        if ($user){
            $updated=  App\User::where('phone_number',$request->phone_number)->where('type' , '2')->where('verification_code',$request->code)->update([
                'verification_code'=>null
            ]);
            $success = ['key'=>'message',
                'value'=> trans('messages.success_code')
            ];
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else{

            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


    }

    public function resetPassword(Request $request) {
        $rules = [
            'phone_number'          => 'required',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = App\User::where('phone_number',$request->phone_number)->where('type' , '2')->first();
//        $user = User::wherePhone($request->phone)->first();

        if($user)
            $updated = $user->update(['password' => Hash::make($request->password)]);
        else{
            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.Wrong_phone')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }


        return $updated
            ? ApiController::respondWithSuccess(trans('messages.Password_reset_successfully'))
            : ApiController::respondWithServerErrorObject();
    }



    public function changePassword(Request $request)
    {
        $rules = [
            'current_password'      => 'required',
            'new_password'          => 'required',
            'password_confirmation' => 'required|same:new_password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $error_old_password = ['key'=>'message',
            'value'=> trans('messages.error_old_password')
        ];
        if (!(Hash::check($request->current_password, Auth::user()->password)))
                return ApiController::respondWithErrorNOTFoundObject(array($error_old_password));
//        if( strcmp($request->current_password, $request->new_password) == 0 )
//            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'New password cant be the same as the old one.']);

        //update-password-finally ^^
        $updated = Auth::user()->update(['password' => Hash::make($request->new_password)]);

        $success_password = ['key'=>'message',
            'value'=> trans('messages.Password_reset_successfully')
        ];

        return $updated
            ? ApiController::respondWithSuccess($success_password)
            : ApiController::respondWithServerErrorObject();
    }



    public function changePhoneNumber(Request $request) 
    {
        $rules = [
            'phone_number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','2')->whereNotIn('id',[$request->user()->id])->first();
        if($phoneCheck)
        {
            $errors = ['key'=>'message',
            'value'=> trans('messages.sorry_this_user_register_before')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }
        $code = mt_rand(1000, 9999);
        $jsonObj = array(
            'mobile' => '',
            'password' => '',
            'sender'=>'TQNEE',
            'numbers' => $request->phone_number,
            'msg'=>'كود التأكيد الخاص بك في  تراكل هو :'.$code,
            'msgId' => rand(1,99999),
            'timeSend' => '0',
            'dateSend' => '0',
            'deleteKey' => '55348',
            'lang' => '3',
            'applicationType' => 68,
        );

        // دالة الإرسال JOSN
        $result=$this->sendSMS($jsonObj);
        $updated=  App\User::where('id',Auth::user()->id)->where('type','2')->update([
            'verification_code'=>$code,
        ]);

        $success = ['key'=>'message',
            'value'=> trans('messages.success_send_code')
        ];
        return $updated
                    ? ApiController::respondWithSuccess($success)
                    : ApiController::respondWithServerErrorObject();
    }


     public function checkCodeChangeNumber(Request $request){

        $rules = [
            'code' => 'required',
            'phone_number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user= App\User::where('id',Auth::user()->id)->where('verification_code', $request->code)->where('type' , '2')->first();
        if ($user){
            $updated=  $user->update([
                'verification_code'=>null,
                'phone_number'=>$request->phone_number,
            ]);

            $success = ['key'=>'message',
                'value'=> trans('messages.success_phone_change')
            ];
            
            return $updated
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }else{

            $errorsLogin = ['key'=>'message',
                'value'=> trans('messages.error_code')
            ];
            return ApiController::respondWithErrorClient(array($errorsLogin));
        }
    }


    public function editProfile(Request $request)
    {
        $rules = [
            'photo'       => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'name'        => 'nullable',
            'last_name'   => 'nullable',
            'email'       => 'nullable|email',
            'country_id'  => 'nullable|exists:countries,id',
            'truckle_id'  => 'nullable|exists:truckles,id',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = App\User::where('id', $request->user()->id)->first();
        $updated =  $user->update([
            'photo'        =>  $request->photo == null ? $user->photo  : UploadImage($request->file('photo'), 'photo', '/uploads/drivers'),
            'name'         =>  $request->name == null ? $user->name  : $request->name,
            'last_name'    =>  $request->last_name == null ? $user->last_name  : $request->last_name,
            'email'        =>  $request->email == null ? $user->email  : $request->email,  
            'country_id'   =>  $request->country_id == null ? $user->country_id  : $request->country_id,
            'truckle_id'   =>  $request->truckle_id == null ? $user->truckle_id  : $request->truckle_id, 
 
        ]);

        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null )
        {

            return $updated
                ? ApiController::respondWithSuccess([
                    'photo'           => $request->photo ==null && $user->photo==null ? null :asset('uploads/drivers/' . $user->photo),
                    'name'            => $user->name,
                    'last_name'       => $user->last_name,
                    'email'           => $user->email,
                    'country_id'      => $user->country_id,
                    'country'         => $user->country->ar_name,
                    'truckle_id'      => $user->truckle_id,
                    'truckle'         => $user->truckle->ar_name,

                ])
                : ApiController::respondWithServerErrorObject();
        }
        elseif($request->header('X-localization')=="en")
        {

            return $updated
                ? ApiController::respondWithSuccess([
                    'photo'           => $request->photo ==null && $user->photo==null ? null :asset('uploads/drivers/' . $user->photo),
                    'name'            => $user->name,
                    'last_name'       => $user->last_name,
                    'email'           => $user->email,
                    'country_id'      => $user->country_id,
                    'country'         => $user->country->en_name,
                    'truckle_id'      => $user->truckle_id,
                    'truckle'         => $user->truckle->en_name,

                ])
                : ApiController::respondWithServerErrorObject();
        }
        elseif($request->header('X-localization')=="hi")
        {

            return $updated
                ? ApiController::respondWithSuccess([
                    'photo'           => $request->photo ==null && $user->photo==null ? null :asset('uploads/drivers/' . $user->photo),
                    'name'            => $user->name,
                    'last_name'       => $user->last_name,
                    'email'           => $user->email,
                    'country_id'      => $user->country_id,
                    'country'         => $user->country->hi_name,
                    'truckle_id'      => $user->truckle_id,
                    'truckle'         => $user->truckle->hi_name,

                ])
                : ApiController::respondWithServerErrorObject();
        }
    }
    
    
 public function editCarData(Request $request)
    {
        $rules = [
            'identity'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'license'               => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'car_form'              => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'transportation_card'   => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'insurance'             => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

           
            $user = App\User::where('id', $request->user()->id)->first();
            $updated =  $user->update([
                'identity'        =>  $request->identity == null ? $user->identity  : UploadImage($request->file('identity'), 'photo', '/uploads/drivers/document/identity'),
                'license'         =>  $request->license == null ? $user->license  : UploadImage($request->file('license'), 'photo', '/uploads/drivers/document/license'),
                'car_form'        =>  $request->car_form == null ? $user->car_form  : UploadImage($request->file('car_form'), 'photo', '/uploads/drivers/document/car_form'),
                'transportation_card'   =>  $request->transportation_card == null ? $user->transportation_card  : UploadImage($request->file('transportation_card'), 'photo', '/uploads/drivers/document/transportation_card'),
                'insurance'        =>  $request->insurance == null ? $user->insurance  : UploadImage($request->file('insurance'), 'photo', '/uploads/drivers/document/insurance'),
          
            ]);
            if ($request->identity == null && $request->license == null  &&  $request->car_form == null && $request->transportation_card == null && $request->insurance == null)
            {
                $data=[];
                array_push($data,[
                  
                    'identity'      =>  asset('/uploads/drivers/document/identity/'.$user->identity),
                    'license'       =>  asset('/uploads/drivers/document/license/'.$user->license),
                    'car_form'      => asset('/uploads/drivers/document/car_form/'.$user->car_form),
                    'transportation_card'  => asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                    'insurance'     => asset('/uploads/drivers/document/insurance/'.$user->insurance),
                ]);

            }
            else
            {
                $active = $user->update([
                    'active'  => 0
                ]);
                $data = ['key'=>'message',
                'value'=> trans('messages.Waiting_for_a_response_from_the_administration_until_it_is_approved')
               ];
            
           
            }
            return $updated
            ? ApiController::respondWithSuccess($data)
            : ApiController::respondWithServerErrorObject();
          
    }
   
   
   
    public  function sendSMS($jsonObj)
    {
        $contextOptions['http'] = array('method' => 'POST', 'header'=>'Content-type: application/json', 'content'=> json_encode($jsonObj), 'max_redirects'=>0, 'protocol_version'=> 1.0, 'timeout'=>10, 'ignore_errors'=>TRUE);
        $contextResouce  = stream_context_create($contextOptions);
        $url = "http://www.alfa-cell.com/api/msgSend.php";
        $arrayResult = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
        $result = $arrayResult[0];

        return $result;
    }
    

}
