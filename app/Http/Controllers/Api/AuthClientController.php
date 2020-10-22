<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App;
use Auth;
use App\User;
use App\ResetPassword;
use Illuminate\Support\Facades\Hash;

class AuthClientController extends Controller
{

    public function registerMobile(Request $request) {
        $rules = [
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
            
        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','1')->first();
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
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'device_token'          => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
    
        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','1')->first();
        if($phoneCheck)
        {
            $errors = ['key'=>'message',
            'value'=> trans('messages.sorry_this_user_register_before')
            ];
             return ApiController::respondWithErrorArray(array($errors));
        }
        $all=[];
       
        $user = App\User::create([
            'phone_number'       => $request->phone_number,
            'name'               => $request->name,
            'email'              => $request->email,
            'active'             => 1,
            'type'               => 1,
            'password'           => Hash::make($request->password),
        ]);
       
        $user->update(['api_token' => generateApiToken($user->id, 10)]);

        $user_rate = App\RateUser::where('rated_id',$user->id)->get();
        $count_rate = $user_rate->count();

         App\PhoneVerification::where('phone_number',$request->phone_number)->orderBy('id','desc')->delete();
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
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


            if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password, 'type'=>1])) {

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

                $user_rate = App\RateUser::where('rated_id',$user->id)->get();
                $count_rate = $user_rate->count();
                $all=[];
                array_push($all,[
                    'id'            =>$user->id,
                    'name'          =>$user->name,
                    'email'         =>$user->email,
                    'phone_number'  =>$user->phone_number,
                    'active'        =>$user->active,
                    'type'          =>$user->type,
                    'api_token'     =>$user->api_token,
                    'photo'         =>$user->photo == null ? null : asset('/uploads/clients/'.$user->photo),
                    'rate'          =>intval($user->rate),
                    'count_rate'    =>$count_rate,
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
            ->where('type' , '1')
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

            $updated=  App\User::where('phone_number',$request->phone_number)->where('type' , '1')->update([
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

        $user= App\User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->where('type' , '1')->first();
        if ($user){
            $updated=  App\User::where('phone_number',$request->phone_number)->where('verification_code',$request->code)->where('type' , '1')->update([
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

        $user = App\User::where('phone_number',$request->phone_number)->where('type' , '1')->first();
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

        $phoneCheck = App\User::where('phone_number',$request->phone_number)->where('type','1')->whereNotIn('id',[$request->user()->id])->first();
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
        $updated=  App\User::where('id',Auth::user()->id)->where('type' , '1')->update([
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

        $user= App\User::where('id',Auth::user()->id)->where('verification_code', $request->code)->where('type' , '1')->first();
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

    public function logout(Request $request)
    {

        $rules = [
            'device_token'     => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $exists = App\UserDevice::where('id',Auth::user()->id)->where('device_token',$request->device_token)->get();

        if (count($exists) !== 0){
            foreach ($exists  as $new){
                $new->delete();
            }

        }
        $users=  App\User::where('id',Auth::user()->id)->first()->update(
            [
                'api_token'=>null
            ]
        );
        return $users
            ? ApiController::respondWithSuccess([])
            : ApiController::respondWithServerErrorArray();


    }
    

    public function editProfile(Request $request)
    {
        $rules = [
            'photo'       => 'nullable|mimes:jpeg,bmp,png,jpg|max:5000',
            'name'        => 'nullable',
            'email'       => 'nullable|email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $user = App\User::where('id', $request->user()->id)->first();
        $updated =  $user->update([
            'photo'        =>  $request->photo == null ? $user->photo  : UploadImage($request->file('photo'), 'photo', '/uploads/clients'),
            'name'         =>  $request->name == null ? $user->name  : $request->name,
            'email'        =>  $request->email == null ? $user->email  : $request->email,       
        ]);

        return $updated
            ? ApiController::respondWithSuccess([
                'photo'     => $request->photo ==null && $user->photo==null ? null :asset('uploads/clients/' . $user->photo),
                'name'      => $user->name,
                'email'     => $user->email,
               
            ])
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
