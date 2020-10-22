<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Rate;
use App\UserDevice;
use App;
use App\PromoCode;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderClientController extends Controller
{
    
    /**
     * create order
     * @createOrder
     */
   public function createOrder(Request $request)
   {
    $rules = [
        'category_id'           => 'required|exists:categories,id',
        'size'                  => 'required|numeric',
        'detials'               => 'required',
        'arrival_time'          => 'required',
        'arrival_date'          => 'required',
        'travel_time'           => 'required',
        'travel_date'           => 'required',
        'code'                  => 'nullable',
        'travel_longitude'      => 'required',
        'travel_latitude'	    => 'required',
        'travel_address'        => 'required',
        'arrival_longitude'     => 'required',
        'arrival_latitude'	    => 'required',
        'arrival_address'       => 'required',
        'city_id'               => 'nullable|exists:cities,id',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails())
        return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        
        if($request->code!==null)
        {
            $promo_code = PromoCode::where('name', 'like', $request->code)->first();
            if (!$promo_code)
            {
                $errors = ['key'=>'create_order',
                'value'=> trans('messages.wrong_code')
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }
        }
        
         $user = App\Order::create([
            'size'                  => $request->size,
            'detials'               => $request->detials,
            'arrival_time'          => $request->arrival_time,
            'arrival_date'          => $request->arrival_date,
            'travel_time'           => $request->travel_time,
            'travel_date'           => $request->travel_date,
            'code'                  => $request->code,
            'travel_longitude'      => $request->travel_longitude,
            'travel_latitude'       => $request->travel_latitude,
            'travel_address'        => $request->travel_address,
            'arrival_longitude'     => $request->arrival_longitude,
            'arrival_latitude'      => $request->arrival_latitude,
            'arrival_address'       => $request->arrival_address,
            'user_id'               => $request->user()->id,
            'state'                 => "pending",
            'category_id'           => $request->category_id,
            'city_id'               => $request->city_id,
        ]);
        
        // send notification to all  drivers that are upload the app
           $drivers = App\User::where('type',2)->where('category_id',$request->category_id)->get();
           foreach ($drivers as $driver)
           {
                $users = App\Order::where('state','=','completed')->where('driver_id',$driver->id)->whereIn('paid',[0,1])->count();
                $setting = App\Setting::find(1)->value('order_count');
                if($users < $setting)
                {
                    // Notification type 2 to public
                    $devicesTokens = UserDevice::where('user_id', $driver->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                    if ($devicesTokens) {
                        sendMultiNotification('الطلبات', 'تم أضافة طلب جديد' ,$devicesTokens);
                    }
                    saveNotification($driver->id, 'الطلبات' ,'orders' ,'आदेश' ,  '2', 'تم أضافة طلب جديد' ,'A New Order Are Added' , 'एक नया आदेश जोड़ा गया है' , $user->id);
                }    
           }

        $all=[];
        array_push($all,[

            'id'                    => $user->id,
            'size'                  => $user->size,
            'detials'               => $user->detials,
            'arrival_time'          => $user->arrival_time,
            'arrival_date'          => $user->arrival_date,
            'travel_time'           => $user->travel_time,
            'travel_date'           => $user->travel_date,
            'code'                  => $user->code,
            'travel_longitude'      => $user->travel_longitude,
            'travel_latitude'       => $user->travel_latitude,
            'travel_address'        => $user->travel_address,
            'arrival_longitude'     => $user->arrival_longitude,
            'arrival_latitude'      => $user->arrival_latitude,
            'arrival_address'       => $user->arrival_address,
            'user_id'               => $user->user_id,
            'state'                 => $user->state,
            'category_id'           => $user->category_id,
            'city_id'               => $user->city_id,
            'created_at'            => $user->created_at->format('Y-m-d'),


        ]);

        return $user
            ? ApiController::respondWithSuccess($all)
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
   
    /**
     * get Order  details 
     * @getOrder
     */
    public function getOrder(Request $request)
    {
          $order = App\Order::where('user_id',$request->user()->id)->orderBy('id','desc')->first();

          $all=[];

         if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'category'              => $order->category->ar_name,
            'city_id'               => $order->city_id,
            'created_at'            => $order->created_at->format('Y-m-d'),

        ]);
    }
    elseif($request->header('X-localization')=="en")
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
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'category'              => $order->category->en_name,
            'city_id'               => $order->city_id,
            'created_at'            => $order->created_at->format('Y-m-d'),

        ]);
    }
    elseif($request->header('X-localization')=="hi")
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
            'state'                 => $order->state,
            'category_id'           => $order->category_id,
            'category'              => $order->category->hi_name,
            'city_id'               => $order->city_id,
            'created_at'            => $order->created_at->format('Y-m-d'),

        ]);
    }
        return $order
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
    }
    
    
        public function getPending(Request $request)
    {
        $orders = App\Order::where('user_id',$request->user()->id)->where('state','=','pending')->orderBy('created_at' , 'desc')->get();
       
        if($orders->count() > 0)
        {
            $all=[];

         foreach($orders as $order)
         {

            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->ar_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="en")  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="hi")  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
         }
         return $orders
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'pending_orders',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     *  get new  orders
     * @getNew
     */
    public function getNew(Request $request)
    {
        $orders = App\Order::where('user_id',$request->user()->id)->where('state','=','accepted')->orderBy('created_at' , 'desc')->get();
       
        if($orders->count() > 0)
        {
            $all=[];

         foreach($orders as $order)
         {
            $driver_count = App\OrderDriver::where('order_id',$order->id)->count();

            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->ar_name,
                'city_id'               => $order->city_id,
                'driver_count'          => $driver_count,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="en")  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
                'driver_count'          => $driver_count,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="hi")  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
               'driver_count'          => $driver_count,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
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
     *  get Confirmed  orders
     * @getConfirmed
     */
    public function getConfirmed(Request $request)
    {
        $orders = App\Order::where('user_id',$request->user()->id)->where('state','=','confirmed')->orderBy('created_at' , 'desc')->get();
       
        if($orders->count() > 0)
        {
            $all=[];

         foreach($orders as $order)
         {

            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
                'state'                 => $order->state,
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'category_id'           => $order->category_id,
                'category'              => $order->category->ar_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="en")  
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
                'state'                 => $order->state,
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
            elseif($request->header('X-localization')=="hi")  
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
                'state'                 => $order->state,
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),

             ]);
            }
         }
         return $orders
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'pending_orders',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     *  get Accepted Order  by order_id($id)
     * @getAcceptedOrder
     */
    public function getAcceptedOrder(Request $request,$id)
    {
         $order = App\Order::where('id',$id)->where('user_id',$request->user()->id)->where('state','accepted')->first();
         if($order)
         {
            $drivers = App\OrderDriver::where('order_id',$id)->get();
            $driver = [];
            if($drivers->count() > 0)
            {
                foreach($drivers as $driv)
                {
                    array_push($driver , [
                        'id'              => intval($driv->id),
                        'driver_id'       => $driv->driver_id,
                        'driver_name'     => $driv->driver->name,
                        'photo'           => $driv->driver->photo== null ? null : asset('/uploads/drivers/'.$driv->driver->photo),
                        'driver_rate'     => intval($driv->driver->rate),
                        'order_id'        => $driv->order_id,
                        'price'           => $driv->price,
                        'details_driver'  => $driv->details_driver,
                        'created_at'      => $driv->created_at->format('Y-m-d'),
                    ]);
                }
            }
            $all = [];
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->ar_name,
                'city_id'               => $order->city_id,
                'driver'                => $driver,
                'created_at'            => $order->created_at->format('Y-m-d'),

            ]);
          }
          elseif($request->header('X-localization')=="en")
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
                'driver'                => $driver,
                'created_at'            => $order->created_at->format('Y-m-d'),

            ]);
          }
          elseif($request->header('X-localization')=="hi")
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
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
                'driver'                => $driver,
                'created_at'            => $order->created_at->format('Y-m-d'),

            ]);
          }
           return $order
           ? ApiController::respondWithSuccess($all)
           : ApiController::respondWithServerErrorArray();
         }
         else{
            $errors = ['key'=>'order_by_id',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     *  confirmed order by driver_order_id($id)
     * @confirmed
     */
    public function confirmed(Request $request,$id)
    {
       $driver = App\OrderDriver::where('id',$id)->first();
       if($driver)
       {
            $driver_price = $driver->price;
            $order = App\Order::find($driver->order_id);
            if($order->code!==null)
            {
                $promo_code = App\PromoCode::where('name','like',$order->code)->first();
                $percentage = $promo_code->percentage;
                $price      = strval($driver_price - ($driver_price * ($percentage / 100)));

                $orderUpdated = App\Order::where('id',$driver->order_id)->update([
                    'state'               => 'confirmed',
                    'driver_id'           => $driver->driver_id,
                    'price'               => $price,
                    'details_driver'      => $driver->details_driver,
                ]);  
            }
            else
            {
                $orderUpdated = App\Order::where('id',$driver->order_id)->update([
                    'state'               => 'confirmed',
                    'driver_id'           => $driver->driver_id,
                    'price'               => $driver->price,
                    'details_driver'      => $driver->details_driver,
                    
                ]);  
            }
            
             $devicesTokens = UserDevice::where('user_id', $driver->driver_id)
                ->get()
                ->pluck('device_token')
                ->toArray();
            if ($devicesTokens) {
                sendMultiNotification("الطلبات", "تم قبول طلب سعرك من قبل  العميل"   ,$devicesTokens);
            }
            saveNotification($driver->driver_id, "الطلبات" ,"orders",'आदेश', '2', "تم قبول عرض سعرك من قبل  العميل" , "User Accept Your Price ",'उपयोगकर्ता आपकी कीमत स्वीकार करते हैं
            ' , $order->id );
            
            // delete other driver orders
             $driver_orders = App\OrderDriver::whereOrder_id($order->id)
             ->where('driver_id' , '!=' , $driver->driver_id)
             ->get();
                if ($driver_orders->count() > 0)
                {
                    foreach ($driver_orders as $order)
                    {
                        $order->delete();
                    }
                }
            
            $success = ['key'=>'confirmed-order',
            'value'=> trans('messages.order_confirmed')
            ];          
            return $order
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        
       }
       else{
        $errors = ['key'=>'confirmed-order',
        'value'=> trans('messages.no_orders')
        ];
        return ApiController::respondWithErrorClient(array($errors));
      }
      
    }

    /**
     *  cancel order by order_id($id)
     * @canceled
     */
    public function canceled(Request $request,$id)
    {
        $rules = [
            'notes'                  => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $order = App\Order::where('user_id',$request->user()->id)->where('id',$id)->first();
        if ($order)
        {
            $order->update([
                'state'     => 'rejected',
                'notes'     => $request->notes,
            ]); 
            $success = ['key'=>'order_cancel',
            'value'=> trans('messages.order_cancel')
            ];            
            return $order
                ? ApiController::respondWithSuccess($success)
                : ApiController::respondWithServerErrorObject();
        }
        else{
            $errors = ['key'=>'order_cancel',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     *  get rejected orders
     * @getRejected
     */
    public function getRejected(Request $request)
    {
        $orders = App\Order::where('user_id',$request->user()->id)->where('state','=','rejected')->get();
        
        if($orders->count() > 0)
        {
            $all=[];

        foreach($orders as $order)
        {


            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
              'driver_id'             => $order->driver_id,
              'driver'                => $order->driver->name,
              'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
              'rate'                  => intval($order->driver->rate),
              'state'                 => $order->state,
              'notes'                 => $order->notes,
              'category_id'           => $order->category_id,
              'category'              => $order->category->ar_name,
              'city_id'               => $order->city_id,
          ]);
          }
          elseif($request->header('X-localization')=="en")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'state'                 => $order->state,
                'notes'                 => $order->notes,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
            ]);
          }
          elseif($request->header('X-localization')=="hi")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'state'                 => $order->state,
                'notes'                 => $order->notes,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
            ]);
          }
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
     *  get activated orders 
     * @getActivated
     */
    public function getActivated(Request $request)
    {
        $order = App\Order::where('user_id',$request->user()->id)->where('state','=','active')->orderBy('id','desc')->first();
       
        if($order)
        {
        $all=[];
        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
              'driver_id'             => $order->driver_id,
              'driver'                => $order->driver->name,
              'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
              'rate'                  => intval($order->driver->rate),
              'state'                 => $order->state,
              'category_id'           => $order->category_id,
              'category'              => $order->category->ar_name,
              'city_id'               => $order->city_id,
              'created_at'            => $order->created_at->format('Y-m-d'),

          ]);

        }
        elseif($request->header('X-localization')=="en")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),
  
            ]);
        }
        elseif($request->header('X-localization')=="hi")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'state'                 => $order->state,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
                'created_at'            => $order->created_at->format('Y-m-d'),
  
            ]);
        }
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
     *  get completed orders 
     * @getCompleted
     */
    public function getCompleted(Request $request)
    {
        $orders = App\Order::where('user_id',$request->user()->id)->where('state','=','completed')->orderBy('id','desc')->get();
       
        if($orders->count() > 0)
        {
        $all=[];

         foreach($orders as $order)
         {
            $rateOrder = App\RateDriver::where('order_id',$order->id)
            ->where('user_id',$order->user_id)
            ->where('rated_id',$order->driver_id)
            ->first();
            if($rateOrder)
            {
                $rate = true;
            }
            else
            {
                $rate = false; 
            }
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
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
              'driver_id'             => $order->driver_id,
              'driver'                => $order->driver->name,
              'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
              'rate'                  => intval($order->driver->rate),
              'rate_state'            => $rate,
              'state'                 => $order->state,
              'price'                 => $order->price,
              'category_id'           => $order->category_id,
              'category'              => $order->category->ar_name,
              'city_id'               => $order->city_id,
          ]);
          }
          elseif($request->header('X-localization')=="en")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'rate_state'            => $rate,
                'state'                 => $order->state,
                'price'                 => $order->price,
                'category_id'           => $order->category_id,
                'category'              => $order->category->en_name,
                'city_id'               => $order->city_id,
            ]);
          }
          elseif($request->header('X-localization')=="hi")
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
                'driver_id'             => $order->driver_id,
                'driver'                => $order->driver->name,
                'photo'                 => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                'rate'                  => intval($order->driver->rate),
                'rate_state'            => $rate,                
                'state'                 => $order->state,
                'price'                 => $order->price,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
            ]);
          }
         }
         return $order
         ? ApiController::respondWithSuccess($all)
         : ApiController::respondWithServerErrorArray();
        }
        else{
            $errors = ['key'=>'completed-order',
            'value'=> trans('messages.no_orders')
            ];
            return ApiController::respondWithErrorClient(array($errors));
          }
    }

    /**
     *  Rate client to driver by order_id ($id)  
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
          $ratting = App\RateDriver::create([
              'user_id'        => $request->user()->id,
              'rated_id'       => $order->driver_id,
              'rating'         => $request->rating,
              'order_id'       => $id,
          ]);
          
         // store the total rate to driver
        $rates = App\RateDriver::whereRated_id($order->driver_id)->get();
        $all = 0;
        if ($rates->count() > 0)
        {
            foreach ($rates as $rate)
            {
                $all = $all + $rate->rating;
            }
            $all = intval($all / $rates->count());
        }
        $updated = App\User::where('id',$order->driver_id)->update(['rate' => $all]);



          $data = [];
          array_push($data , [
            'user_id'         => $request->user()->id,
            'rated_id'        => $order->driver_id,
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

 
}
