<?php

namespace App\Http\Controllers\Api;

use App\About;
use App\AppAdd;
use App\ContactUs;
use App\Education;
use App\Field;
use App\Group;
use App\GroupUser;
use App\Notification;
use App\Rating;
use App\SawaqUserDevice;
use App\TermsCondition;
use App\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ApiController extends Controller
{
    

    


    public function listNotifications(Request $request) {

        $notifications = Notification::Where('user_id', $request->user()->id)->orderBy('id','desc')->get();
        if($notifications->count()>0)
        {
            $data = [];
            foreach ($notifications as $notification)
            {
                if ($notification->type == '1')
                {
                    array_push($data , [
                        'id'          => intval($notification->id),
                        'type'        => intval($notification->type),
                        'ar_title'    => $notification->ar_title,
                        'ar_message'  => $notification->ar_message,
                        'user_id'     => intval($notification->user_id),
                        'order_id'    => $notification->order_id,
                        'created_at'  => $notification->created_at->format('Y-m-d')
                    ]);
                }
                else{
                    if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
                    {
                        array_push($data , [
                            'id'          => intval($notification->id),
                            'type'        => intval($notification->type),
                            'ar_title'    => $notification->ar_title,
                            'ar_message'  => $notification->ar_message,
                            'user_id'     => intval($notification->user_id),
                            'order_id'    => $notification->order_id,
                            'created_at'  => $notification->created_at->format('Y-m-d')
                        ]);
                    }
                    elseif($request->header('X-localization')=="en")
                    { 
                        array_push($data , [
                            'id'          => intval($notification->id),
                            'type'        => intval($notification->type),
                            'ar_title'    => $notification->en_title,
                            'ar_message'  => $notification->en_message,
                            'user_id'     => intval($notification->user_id),
                            'order_id'    => $notification->order_id,
                            'created_at'  => $notification->created_at->format('Y-m-d')
                        ]);

                    }
                    elseif($request->header('X-localization')=="hi")
                    { 
                        array_push($data , [
                            'id'          => intval($notification->id),
                            'type'        => intval($notification->type),
                            'ar_title'    => $notification->hi_title,
                            'ar_message'  => $notification->hi_message,
                            'user_id'     => intval($notification->user_id),
                            'order_id'    => $notification->order_id,
                            'created_at'  => $notification->created_at->format('Y-m-d')
                        ]);
                    }
                }
           }
         return $this->respondWithSuccess($data);
        }
        else
        {
            $errors = ['key'=>'notifications',
            'value'=> trans('messages.no_notifications')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        
    }


    public function deleteNotification( $id , Request $request) {

        $data = Notification::Where('id', $id)->where('user_id',$request->user()->id)->delete();

        return $data
        ? $this->respondWithSuccess([
            'value'=> trans('messages.delete_notification')
        ])
        :$this->respondWithServerErrorArray();     
        
    }

    public static function createUserDeviceToken($userId, $deviceToken, $deviceType) {

        $created = UserDevice::updateOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'device_type' => $deviceType,
                'device_token' => $deviceToken
            ]);

        return $created;
    }


    public static function respondWithSuccess($data) {
        http_response_code(200);
        return response()->json(['mainCode'=> 1,'code' =>  http_response_code()  , 'data' => $data, 'error' => null])->setStatusCode(200);
    }

    public static function respondWithErrorArray($errors) {
        http_response_code(422);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(422);
    }public static function respondWithErrorObject($errors) {
    http_response_code(422);  // set the code
    return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(422);
}
    public static function respondWithErrorNOTFoundObject($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(404);
    }
    public static function respondWithErrorNOTFoundArray($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(404);
    }
    public static function respondWithErrorClient($errors) {
        http_response_code(400);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(400);
    }
    public static function respondWithErrorAuthObject($errors) {
        http_response_code(401);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(401);
    }
    public static function respondWithErrorAuthArray($errors) {
        http_response_code(401);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(401);
    }


    public static function respondWithServerErrorArray() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }
    public static function respondWithServerErrorObject() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , 'data' => null, 'error' => $errors])->setStatusCode(500);
    }



}
