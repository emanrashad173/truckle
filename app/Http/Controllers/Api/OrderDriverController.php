<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Rate;
use App;
use Validator;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderDriverController extends Controller
{
    /**
     * get pending orders
     * @getPending
     */
    public function getPending(Request $request)
    { 
        $rules = [
            'city_id'      => 'sometimes',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        
        $user = App\Order::where('state','=','completed')->where('driver_id',$request->user()->id)->whereIn('paid',[0,1])->count();
        $setting = App\Setting::first()->value('order_count');
        
        if($user >= $setting)
        {
            $errors = ['key'=>'pending-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        if($request->city_id == '0' || $request->city_id == null)
        {

        $orders = App\Order::whereIn('state',['pending','accepted'])->orderBy('created_at' , 'desc')->get();
        }
        else{
         $orders = App\Order::whereIn('state',['pending','accepted'])->where('city_id', $request->city_id)->orderBy('created_at' , 'desc')->get();

        }


        if($orders->count() > 0)
        {
            $all=[];

         foreach($orders as $order)
         {
             $driver_order = App\OrderDriver:: where('order_id',$order->id)->where('driver_id',$request->user()->id)->first();
             $ordertime  = $order->created_at->format('Y-m-d');
             $setting    = Setting::first();
             $duration   = $setting->order_time;
             $total      = Carbon::parse($ordertime)->addDays($duration);
             $now        = Carbon::now()->format('Y-m-d');
             if(!$driver_order)
             {
             if($total>$now)
             {      
                array_push($all,[
                    'id'                    => $order->id,
                    'user_id'               => $order->user_id,
                    'user'                  => $order->user->name,
                    'rate'                  => intval($order->user->rate),
                    'photo'                 => $order->user->photo == null ? null : asset('/uploads/clients/'.$order->user->photo),
                    'size'                  => $order->size,
                    'detials'               => $order->detials,
                    'arrival_time'          => $order->arrival_time,
                    'arrival_date'          => $order->arrival_date,
                    'travel_time'           => $order->travel_time,
                    'travel_date'           => $order->travel_date,
                    'code'                  => $order->code,
                    'travel_longitude'      => $order->travel_longitude,
                    'travel_latitude'       => $order->travel_latitude,
                    'travel_address'        => $order->travel_address,
                    'arrival_longitude'     => $order->arrival_longitude,
                    'arrival_latitude'      => $order->arrival_latitude,
                    'arrival_address'       => $order->arrival_address,
                    'user_id'               => $order->user_id,
                    'user'                  => $order->user->name,
                    'rate'                  => intval($order->user->rate),
                    'state'                 => $order->state,
                    'discount_percentage'   => $order->code == null? null :  App\PromoCode::where('name','like',$order->code)->value('percentage'),
                    'category_id'           => $order->category_id,
                    'city_id'               => $order->city_id,
                    'created_at'            => $order->created_at->format('Y-m-d'),
                ]);
             }
             else{
                 $order->update([
                     'state'  => 'end'
                 ]);
              }
            }
         }
          if ($all == [])
         {
            $errors = ['key'=>'pending-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        else
        {    
         return $order
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
      }
      else{
        $errors = ['key'=>'pending-order',
        'value'=> trans('messages.no_orders')
        ];
        return ApiController::respondWithErrorClient(array($errors));
      }
    }


     /**
     * accepted order by order_id
     * @accepted
     */
    public function accepted(Request $request,$id)
    {
        $rules = [
            'price'                  => 'required|numeric',
            'details_driver'         => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $order = App\Order::find($id);
        if ($order)
        {
            $driver = App\OrderDriver::create([
                'price'              => $request->price,
                'details_driver'     => $request->details_driver,
                'driver_id'          => $request->user()->id,
                'order_id'           => $order->id,

            ]); 
            $order->update([
                'state'        => 'accepted'
            ]);  
            
             $devicesTokens = App\UserDevice::where('user_id', $order->user_id)
                ->get()
                ->pluck('device_token')
                ->toArray();
             if ($devicesTokens) {
                sendMultiNotification("الطلبات", "تم ارسال عرض سعر علي طلبك"   ,$devicesTokens);
            }
            saveNotification($order->user_id, "الطلبات" ,"orders",'आदेश', '2', "تم ارسال عرض سعر علي طلبك " , "Offer price has sent to your order ",'आपके आदेश पर ऑफ़र मूल्य भेजा गया है' , $order->id );  
           
            $success = ['key'=>'accepted-order',
            'value'=> trans('messages.order_accepted')
            ];        
            return $order
                ? ApiController::respondWithSuccess( $success)
                : ApiController::respondWithServerErrorObject();
        }
        else{
            $errors = ['key'=>'accepted-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }


    /**
     * delivered order by order_id
     * @delivered
     */
    public function delivered(Request $request,$id)
    {
        $order = App\Order::where('user_id',$request->user()->id)->where('id',$id)->first();
        if ($order)
        {
            $order->update(['state' => ' completed']);
                       
            return $order
                ? ApiController::respondWithSuccess( trans('messages.order_delivered'))
                : ApiController::respondWithServerErrorObject();
        }
        else{
            $errors = ['key'=>'completed-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }


    /**
     * get new orders
     * @getNew
     */
    public function getNew(Request $request)
    {
        $orders = App\Order::where('driver_id',$request->user()->id)->where('state','=','confirmed')->orderBy('travel_date','desc')->get();

        if($orders->count() > 0)
        {
         $all=[];

         foreach($orders as $order)
         {

            array_push($all,[
              'id'                    => $order->id,
              'size'                  => $order->size,
              'detials'               => $order->detials,
              'arrival_time'          => $order->arrival_time,
              'arrival_date'          => $order->arrival_date,
              'travel_time'           => $order->travel_time,
              'travel_date'           => $order->travel_date,
              'code'                  => $order->code,
              'travel_longitude'      => $order->travel_longitude,
              'travel_latitude'       => $order->travel_latitude,
              'travel_address'        => $order->travel_address,
              'arrival_longitude'     => $order->arrival_longitude,
              'arrival_latitude'      => $order->arrival_latitude,
              'arrival_address'       => $order->arrival_address,
              'user_id'               => $order->user_id,
              'user'                  => $order->user->name,
              'photo'                 => $order->user->photo == null ? null : asset('/uploads/clients/'.$order->user->photo),
              'rate'                  => intval($order->user->rate),
              'state'                 => $order->state,
              'category_id'           => $order->category_id,
              'city_id'               => $order->city_id,
              'created_at'            => $order->created_at->format('Y-m-d'),

              
          ]);
         }
         return $orders
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'new-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

     /**
     * activated order by order_id
     * @activated
     */
    public function activated(Request $request,$id)
    {
        $driver = App\Order::where('driver_id',$request->user()->id)->where('state','active')->get();
        if($driver->count() > 0)
        {
            $errors = ['key'=>'active-order',
            'value'=> trans('messages.order_not_completed')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
        $order = App\Order::find($id);
        if($order)
        {
        $order->update([
            'state'        => 'active'
        ]);  
         $devicesTokens = App\UserDevice::where('user_id', $order->user_id)
        ->get()
        ->pluck('device_token')
        ->toArray();
        if ($devicesTokens) {
            sendMultiNotification("الطلبات", " تم بدء الرحلة "   ,$devicesTokens);
        }
        saveNotification($order->user_id, "الطلبات" ,"orders",'आदेश', '2', "تم بدء الرحلة " , "The trip start ",'यात्रा शुरू कर दी गई है
        ' , $order->id ); 
        $success = ['key'=>'active-order',
        'value'=> trans('messages.order_active')
        ];        
        return $order
            ? ApiController::respondWithSuccess( $success)
            : ApiController::respondWithServerErrorObject();
    }
    else{
        $errors = ['key'=>'active-order',
        'value'=> trans('messages.no_orders')
        ];
        return ApiController::respondWithErrorClient(array($errors));
      }

    }

     /**
     * get Activated orders 
     * @getActivated
     */
    public function getActivated(Request $request)
    {
        $order = App\Order::where('driver_id',$request->user()->id)->where('state','=','active')->orderBy('id','desc')->first();
       
        if($order)
        {
         
            $all=[];
            array_push($all,[
              'id'                    => $order->id,
              'size'                  => $order->size,
              'detials'               => $order->detials,
              'arrival_time'          => $order->arrival_time,
              'arrival_date'          => $order->arrival_date,
              'travel_time'           => $order->travel_time,
              'travel_date'           => $order->travel_date,
              'code'                  => $order->code,
              'travel_longitude'      => $order->travel_longitude,
              'travel_latitude'       => $order->travel_latitude,
              'travel_address'        => $order->travel_address,
              'arrival_longitude'     => $order->arrival_longitude,
              'arrival_latitude'      => $order->arrival_latitude,
              'arrival_address'       => $order->arrival_address,
              'user_id'               => $order->user_id,
              'user'                  => $order->user->name,
              'photo'                 => $order->user->photo == null ? null : asset('/uploads/clients/'.$order->user->photo),
              'phone_number'          => $order->user->phone_number,
              'rate'                  => intval($order->user->rate),
              'state'                 => $order->state,
              'category_id'           => $order->category_id,
              'city_id'               => $order->city_id,
              'created_at'            => $order->created_at->format('Y-m-d'),

          ]);
         
         return $order
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'activated-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     * completed order by order_id
     * @completed
     */
    public function completed(Request $request,$id)
    {
        $order = App\Order::find($id);
        if($order)
        {
        $order->update([
            'state'        => 'completed'
        ]);   
         $devicesTokens = App\UserDevice::where('user_id', $order->user_id)
            ->get()
            ->pluck('device_token')
            ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("الطلبات", " تم اكتمال الرحلة "   ,$devicesTokens);
            }
            saveNotification($order->user_id, "الطلبات" ,"orders",'आदेश', '2', "تم اكتمال الرحلة " , "The trip complete ",'उड़ान पूरी हो गई है
            ' , $order->id ); 
        $success = ['key'=>'completed-order',
        'value'=> trans('messages.order_completed')
        ];        
        return $order
            ? ApiController::respondWithSuccess( $success)
            : ApiController::respondWithServerErrorObject();
    }
    else{
        $errors = ['key'=>'completed-order',
        'value'=> trans('messages.no_orders')
        ];
        return ApiController::respondWithErrorClient(array($errors));
      }

    }

    /**
     * get Completed orders 
     * @getCompleted
     */
    public function getCompleted(Request $request)
    {
        $orders = App\Order::where('driver_id',$request->user()->id)->where('state','=','completed')->orderBy('id','desc')->get();
       
        if($orders->count() > 0)
        {

         $all=[];

         foreach($orders as $order)
         {
            $rateOrder = App\RateUser::where('order_id',$order->id)
            ->where('user_id',$order->driver_id)
            ->where('rated_id',$order->user_id)
            ->first();
            if($rateOrder)
            {
                $rate = true;
            }
            else
            {
                $rate = false; 
            }
            array_push($all,[
              'id'                    => $order->id,
              'size'                  => $order->size,
              'detials'               => $order->detials,
              'arrival_time'          => $order->arrival_time,
              'arrival_date'          => $order->arrival_date,
              'travel_time'           => $order->travel_time,
              'travel_date'           => $order->travel_date,
              'code'                  => $order->code,
              'travel_longitude'      => $order->travel_longitude,
              'travel_latitude'       => $order->travel_latitude,
              'travel_address'        => $order->travel_address,
              'arrival_longitude'     => $order->arrival_longitude,
              'arrival_latitude'      => $order->arrival_latitude,
              'arrival_address'       => $order->arrival_address,
              'user_id'               => $order->user_id,
              'user'                  => $order->user->name,
              'photo'                 => $order->user->photo == null ? null : asset('/uploads/clients/'.$order->user->photo),
              'rate'                  => intval($order->user->rate),
              'rate_state'            => $rate,
              'state'                 => $order->state,
              'category_id'           => $order->category_id,
              'city_id'               => $order->city_id,
              'created_at'            => $order->created_at->format('Y-m-d'),

          ]);
         }
         return $orders
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'complete-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     * get Rejected orders 
     * @getRejected
     */
    public function getRejected(Request $request)
    {
        $orders = App\Order::where('driver_id',$request->user()->id)->where('state','=','rejected')->get();
        
        if($orders->count() > 0)
        {
            $all=[];

        foreach($orders as $order)
        {


            array_push($all,[
            'id'                    => $order->id,
            'size'                  => $order->size,
            'detials'               => $order->detials,
            'arrival_time'          => $order->arrival_time,
            'arrival_date'          => $order->arrival_date,
            'travel_time'           => $order->travel_time,
            'travel_date'           => $order->travel_date,
            'code'                  => $order->code,
            'travel_longitude'      => $order->travel_longitude,
            'travel_latitude'       => $order->travel_latitude,
            'travel_address'        => $order->travel_address,
            'arrival_longitude'     => $order->arrival_longitude,
            'arrival_latitude'      => $order->arrival_latitude,
            'arrival_address'       => $order->arrival_address,
            'user_id'               => $order->user_id,
            'user'                  => $order->user->name,
            'photo'                 => $order->user->photo == null ? null : asset('/uploads/clients/'.$order->user->photo),
            'rate'                  => intval($order->user->rate),
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'notes'                 => $order->notes,
            'city_id'               => $order->city_id,
            'created_at'            => $order->created_at->format('Y-m-d'),

        ]);
        }
        return $order
        ? ApiController::respondWithSuccess($all)
        : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'rejected-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    /**
     *  Rate driver to client by order_id ($id)  
     * @rate
     */
    public function rate(Request $request , $id)
    {
          $rules = [
              'rating'      => 'required|in:1,2,3,4,5',
          ];

          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails())
              return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

         $order = App\Order::find($id);
         if($order)
         {
          // create new  rate
          $ratting = App\RateUser::create([
              'user_id'        => $request->user()->id,
              'rated_id'       => $order->user_id,
              'rating'         => $request->rating,
              'order_id'       => $id,
          ]);
         // store the total rate to user
            $rates = App\RateUser::whereRated_id($order->user_id)->get();
            $all = 0;
            if ($rates->count() > 0)
            {
                foreach ($rates as $rate)
                {
                    $all = $all + $rate->rating;
                }
                $all = $all / $rates->count();
            }
            $updated = App\User::where('id',$order->user_id)->update(['rate' => $all]);

            
          $data = [];
          array_push($data , [
            'user_id'         => $request->user()->id,
            'rated_id'        => $order->user_id,
            'rating'          => $request->rating,
          ]);
          return $order
                  ? ApiController::respondWithSuccess($data)
                  : ApiController::respondWithServerErrorObject();
      }else{
          
        $errors = ['key'=>'rate',
        'value'=> trans('messages.no_orders')
        ];
        return ApiController::respondWithErrorClient(array($errors));
      }
    }

     /**
     *  new Payment completed not paid
     * @newPayment
     */
    public function newPayment(Request $request)
    {
        $orders = App\Order::where('driver_id',$request->user()->id)->where('state','=','completed')->where('paid','=','0')->orderBy('id','desc')->get();
        if($orders->count() > 0)
        {
            $all=[];
            
        foreach($orders as $order)
        {

            $setting = Setting::first();
            $commission = $setting->commission;
            $price = $order->price;
            $commission_price = $price * $commission/100;
            $total = $price +  $commission_price;
            $order->total_price = $total;
            $order->commission_price = $commission_price;
            $order->save();

            array_push($all,[
            'id'                    => $order->id,
            'size'                  => $order->size,
            'detials'               => $order->detials,
            'arrival_time'          => $order->arrival_time,
            'arrival_date'          => $order->arrival_date,
            'travel_time'           => $order->travel_time,
            'travel_date'           => $order->travel_date,
            'code'                  => $order->code,
            'travel_longitude'      => $order->travel_longitude,
            'travel_latitude'       => $order->travel_latitude,
            'travel_address'        => $order->travel_address,
            'arrival_longitude'     => $order->arrival_longitude,
            'arrival_latitude'      => $order->arrival_latitude,
            'arrival_address'       => $order->arrival_address,
            'user_id'               => $order->user_id,
            'user'                  => $order->user->name,
            'rate'                  => intval($order->user->rate),
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'notes'                 => $order->notes,
            'city_id'               => $order->city_id,
            'user_id'               => $order->user_id,
            'user'                  => $order->user->name,
            'rate'                  => intval($order->user->rate),
            'price'                 => intval($order->price),
            'commission_price'      => intval($order->commission_price),
            'total_price'           => intval($order->total_price),
            'created_at'            => $order->created_at->format('Y-m-d'),

        ]);
        }
        return $order
        ? ApiController::respondWithSuccess($all)
        : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'new-payment',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    /**
     *  paid Payment and confirmed by admin
     * @paidPayment
     */
    public function paidPayment(Request $request)
    {
        $orders = App\Order::where('driver_id',$request->user()->id)->where('state','=','completed')->where('paid','=','2')->orderBy('id','desc')->get();
        if($orders->count() > 0)
        {
            $all=[];
            
        foreach($orders as $order)
        {

            array_push($all,[
            'id'                    => $order->id,
            'size'                  => $order->size,
            'detials'               => $order->detials,
            'arrival_time'          => $order->arrival_time,
            'arrival_date'          => $order->arrival_date,
            'travel_time'           => $order->travel_time,
            'travel_date'           => $order->travel_date,
            'code'                  => $order->code,
            'travel_longitude'      => $order->travel_longitude,
            'travel_latitude'       => $order->travel_latitude,
            'travel_address'        => $order->travel_address,
            'arrival_longitude'     => $order->arrival_longitude,
            'arrival_latitude'      => $order->arrival_latitude,
            'arrival_address'       => $order->arrival_address,
            'user_id'               => $order->user_id,
            'user'                  => $order->user->name,
            'rate'                  => intval($order->user->rate),
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'notes'                 => $order->notes,
            'city_id'               => $order->city_id,
            'user_id'               => $order->user_id,
            'user'                  => $order->user->name,
            'rate'                  => intval($order->user->rate),
            'price'                 => intval( $order->price),
            'commission_price'      => intval($order->commission_price),
            'total_price'           => intval($order->total),
            'created_at'            => $order->created_at->format('Y-m-d'),
        ]);
        }
        return $order
        ? ApiController::respondWithSuccess($all)
        : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'paid-payment',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        } 
    }
    
    
    /**
     *  pay commission
     * @pay
     */
    public function pay(Request $request)
    {
        $rules = [
            'order_id'   => 'required',
            'price'      => 'required',
            'photo'      => 'required|image|max:3000',
           
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return ApiController::respondWithErrorObject(validateRules($validation->errors(), $rules));
        }
        $user  = $request->user();
        $order = $user->orders->where('id',$request->order_id)->where('state','completed')->first();
        $order->update([
            'pay_photo' => UploadImage($request->photo, 'photo', 'uploads/payments'),
            'paid' => 1
        ]);
        // $devicesTokens = App\UserDevice::where('user_id', $request->user()->id)
        //     ->get()
        //     ->pluck('device_token')
        //     ->toArray();

        // if ($devicesTokens) {
        //     sendMultiNotification("تم ارسال طلب الدفع للطلب المكتمل", "طلب الدفع",$devicesTokens);
        // }
        //     saveNotification($request->user()->id,"طلب الدفع" , "pay request",1,"تم ارسال طلب الدفع للطلب المكتمل بنجاح برجاء انتظار رد الادارة","pay request sent successfully Please wait for a response from the administration");

        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
        {
            $data = [
                'key'   => 'pay',
                'value' =>  'تم ارسال طلب الدفع للطلب  المكتمل بنجاح برجاء انتظار رد الادارة'
            ];
            return ApiController::respondWithSuccess($data);
        }
        elseif($request->header('X-localization')=="en" )
        {
            $data = [
                'key'   => 'pay',
                'value' =>  'pay request sent successfully Please wait for a response from the administration'
            ];
            return ApiController::respondWithSuccess($data);
        }
        elseif($request->header('X-localization')=="hi" )
        {
            $data = [
                'key'   => 'pay',
                'value' =>  'सफलतापूर्वक भेजे गए अनुरोध का भुगतान करें। कृपया प्रशासन से प्रतिक्रिया की प्रतीक्षा करें'
            ];
            return ApiController::respondWithSuccess($data);
        }
    }


    
 
}
