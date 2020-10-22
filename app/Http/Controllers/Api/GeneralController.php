<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
   
    public function aboutUs()
    {
        $about = App\AboutUs::first();
        $all=[
            'title'=>$about->title,
            'content'=>$about->content,
        ];
        return ApiController::respondWithSuccess($all);
    }
    public function termsAndConditions()
    {
        $terms = App\TermsCondition::first();
        $all=[
            'title'=>$terms->title,
            'content'=>$terms->content,
        ];
        return ApiController::respondWithSuccess($all);
    }
    public function settings()
    {
        $about = App\Setting::first();
        $all=[
            'phone_number'=>$about->phone_number,
            'bank_name'=>$about->bank_name,
            'account_number'=>$about->account_number,

        ];


        return ApiController::respondWithSuccess($all);
    }


     /**
     *  all  countries
     * @countries
     */
      public function countries(Request $request)
    {
        $countries = Country::orderBy('created_at' , 'desc')->get();
        if($countries->count() > 0)
        {
            $all = [];
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
            {
                foreach($countries as $country)
                {
                    array_push($all , [
                        'id'         => intval($country->id),
                        'name'       => $country->ar_name,
                        'created_at' => $country->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="en")
            {
                foreach($countries as $country)
                {
                    array_push($all , [
                        'id'         => intval($country->id),
                        'name'       => $country->en_name,
                        'created_at' => $country->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="hi")
            {
                foreach($countries as $country)
                {
                    array_push($all , [
                        'id'         => intval($country->id),
                        'name'       => $country->hi_name,
                        'created_at' => $country->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }
        }else{
            $errors = ['key'=>'countries',
                'value'=> trans('messages.no_countries')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

    /**
     *  all  cities
     * @cities
     */
    public function cities(Request $request)
    {
        $cities = App\City::orderBy('created_at' , 'desc')->get();
        if($cities->count() > 0)
        {
            $all = [];
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
            {
                foreach($cities as $city)
                {
                    array_push($all , [
                        'id'         => intval($city->id),
                        'name'       => $city->ar_name,
                        'country_id' => $city->country_id,
                        'country'    => $city->country->ar_name,
                        'created_at' => $city->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="en")
            {
                foreach($cities as $city)
                {
                    array_push($all , [
                        'id'         => intval($city->id),
                        'name'       => $city->en_name,
                        'country_id' => $city->country_id,
                        'country'    => $city->country->en_name,
                        'created_at' => $city->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="hi")
            {
                foreach($cities as $city)
                {
                    array_push($all , [
                        'id'         => intval($city->id),
                        'name'       => $city->hi_name,
                        'country_id' => $city->country_id,
                        'country'    => $city->country->hi_name,
                        'created_at' => $city->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }
        }else{
            $errors = ['key'=>'countries',
                'value'=> trans('messages.no_countries')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }


     /**
     *  all  truckles
     * @truckles
     */
    public function truckles(Request $request)
    {
        $truckles = App\Truckle::orderBy('created_at' , 'desc')->get();
        if($truckles->count() > 0)
        {
            $all = [];
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)
            {
                foreach($truckles as $truckle)
                {
                    array_push($all , [
                        'id'         => intval($truckle->id),
                        'name'       => $truckle->ar_name,
                        'created_at' => $truckle->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="en")
            {
                foreach($truckles as $truckle)
                {
                    array_push($all , [
                        'id'         => intval($truckle->id),
                        'name'       => $truckle->en_name,
                        'created_at' => $truckle->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="hi")
            {
                foreach($truckles as $truckle)
                {
                    array_push($all , [
                        'id'         => intval($truckle->id),
                        'name'       => $truckle->hi_name,
                        'created_at' => $truckle->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }
        }else{
            $errors = ['key'=>'truckles',
                       'value'=> trans('messages.no_truckles')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }

     /**
     *  all  categories
     * @categories
     */
    public function categories(Request $request)
    {
        $categories = App\Category::orderBy('created_at' , 'desc')->get();
        if($categories->count() > 0)
        {
            $all = [];
            if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)
            {
                foreach($categories as $category)
                {
                    array_push($all , [
                        'id'         => intval($category->id),
                        'name'       => $category->ar_name,
                        'photo'      => asset('/uploads/categories/'.$category->photo),
                        'created_at' => $category->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="en")
            {
                foreach($categories as $category)
                {
                    array_push($all , [
                        'id'         => intval($category->id),
                        'name'       => $category->en_name,
                        'photo'      => asset('/uploads/categories/'.$category->photo),
                        'created_at' => $category->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }elseif($request->header('X-localization')=="hi")
            {
                foreach($categories as $category)
                {
                    array_push($all , [
                        'id'         => intval($category->id),
                        'name'       => $category->hi_name,
                        'photo'      => asset('/uploads/categories/'.$category->photo),
                        'created_at' => $category->created_at->format('Y-m-d'), 
                    ]);
                }
                return ApiController::respondWithSuccess($all);
            }
        }else{
            $errors = ['key'=>'categories',
                       'value'=> trans('messages.no_categories')
            ];
            return ApiController::respondWithErrorClient(array($errors));
        }
    }
    

     /**
     *  Get Order By Id
     * @getOrderById 
     */
   public function getOrderById(Request $request,$id)
    {
        $order = App\Order::find($id);
        if($order)
        {
            if($order->state == 'accepted')
            {
            $drivers = App\OrderDriver::where('order_id',$id)->get();
            $driver = [];
            if($drivers->count() > 0)
            {
                foreach($drivers as $driv)
                {
                    if($order->code!=null)
                    {
                        $driver_price = $driv->price;
                        $promo_code = App\PromoCode::where('name','like',$order->code)->first();
                        $percentage = $promo_code->percentage;
                        $price      = strval($driver_price - ($driver_price * ($percentage / 100)));

                    }
                    else
                    {
                        $price = $driv->price;
                    }
                    array_push($driver , [
                        'id'              => intval($driv->id),
                        'driver_id'       => $driv->driver_id,
                        'driver_name'     => $driv->driver->name,
                        'photo'           => $driv->driver->photo== null ? null : asset('/uploads/drivers/'.$driv->driver->photo),
                        'driver_rate'     => intval($driv->driver->rate),
                        'order_id'        => $driv->order_id,
                        'price'           => intval($price),
                        'details_driver'  => $driv->details_driver,
                        'created_at'      => $driv->created_at->format('Y-m-d'),
                    ]);
                }
            }
        
         }
           elseif($order->state == 'confirmed' || $order->state == 'completed'|| $order->state == 'rejected'|| $order->state == 'active')
           {
             $driver = [];
             array_push($driver,[
                 'driver_id'              => $order->driver_id,
                 'driver_name'             => $order->driver->name,
                 'photo'                   => $order->driver->photo == null ? null : asset('/uploads/drivers/'.$order->driver->photo),
                 'driver_rate'             => intval($order->driver->rate),
                 'price'                   => $order->price,
                 'details_driver'          => $order->details_driver,

             ]);
        }
        elseif($order->state == 'pending'||$order->state == 'end'){
            $driver = null;
        }
        
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
              'user'                  => $order->user->name,
              'photo'                 => $order->user->photo == null ? null : asset('/uploads/users/'.$order->user->photo),
              'rate'                  => intval($order->user->rate),
              'state'                 => $order->state,
              'driver'                => $driver,
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
                'user_id'               => $order->user_id,
                'user'                  => $order->user->name,
                'photo'                 => $order->user->photo == null ? null : asset('/uploads/users/'.$order->user->photo),
                'rate'                  => intval($order->user->rate),
                'state'                 => $order->state,
                'driver'                => $driver,
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
                'user_id'               => $order->user_id,
                'user'                  => $order->user->name,
                'photo'                 => $order->user->photo == null ? null : asset('/uploads/users/'.$order->user->photo),
                'rate'                  => intval($order->user->rate),
                'state'                 => $order->state,
                'driver'                => $driver,
                'notes'                 => $order->notes,
                'category_id'           => $order->category_id,
                'category'              => $order->category->hi_name,
                'city_id'               => $order->city_id,
            ]);
          }
          return $order
          ? ApiController::respondWithSuccess($all)
          : ApiController::respondWithServerErrorArray();
          }
          else{
              $errors = ['key'=>'get-order-by-id',
              'value'=> trans('messages.no_order')
              ];
              return ApiController::respondWithErrorClient(array($errors));
          }

        
    }

     /**
     *  Device Token
     * @deviceToken 
     */

    public function deviceToken(Request $request)
    {
        $rules = [
          'device_token'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $created  = App\Device::create([
            'device_token' => $request->device_token,
        ]);
        return $created
        ? ApiController::respondWithSuccess('تمت الاضافه بنجاح')
        : ApiController::respondWithServerErrorArray();

    }

    /**
     *  Get User Data
     * @getUserData 
     */
    public function getUserData(Request $request)
    {
        $user = App\User::where('id' , $request->user()->id)->first();

        if($user->type==1)
        {
            $user_rate = App\RateUser::where('rated_id',$user->id)->get();
            $count_rate = $user_rate->count();
        }
        elseif($user->type==2)
        {
            $user_rate = App\RateDriver::where('rated_id',$user->id)->get();
            $count_rate = $user_rate->count();
        }
        if($user->photo == null)
        {
            $photo = null;
        }
        elseif($user->type==1)
        {
            $photo = asset('/uploads/clients/'.$user->photo);
        }
        elseif($user->type==2)
        {
            $photo = asset('/uploads/drivers/'.$user->photo);
        }
        $all=[];

        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
        {

            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null?null :$user->truckle->ar_name,
                'category_id'   => $user->category_id,
                'category'       => $user->category_id==null?null :$user->category->ar_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);

        }
        elseif($request->header('X-localization')=="en")
        {
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null?null :$user->truckle->en_name,
                'category_id'   => $user->category_id,
                'category'       => $user->category_id==null?null :$user->category->en_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);
        }
        elseif($request->header('X-localization')=="hi")
        {
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null ? null :$user->truckle->hi_name,
                'category_id'   => $user->category_id,
                'category'       => $user->category_id==null?null :$user->category->hi_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);
        }
            return $user
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
        
    }
    
    
    
     public function getDriverById(Request $request,$id)
    { 

        $user = App\User::where('id' , $id)->where('type','2')->first();

        if($user)
        {
        $user_rate = App\RateDriver::where('rated_id',$user->id)->get();
        $count_rate = $user_rate->count();
       
        if($user->photo == null)
        {
            $photo = null;
        }
        else
        {
            $photo = asset('/uploads/drivers/'.$user->photo);
        }
        $all=[];

        if($request->header('X-localization')=="ar" || $request->header('X-localization')==null)  
        {

            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null?null :$user->truckle->ar_name,
                'category_id'   =>$user->category_id,
                'category'      => $user->category_id==null?null :$user->category->ar_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);

        }
        elseif($request->header('X-localization')=="en")
        {
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null?null :$user->truckle->en_name,
                'category_id'   =>$user->category_id,
                'category'      => $user->category_id==null?null :$user->category->en_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);
        }
        elseif($request->header('X-localization')=="hi")
        {
            array_push($all,[
                'id'            =>$user->id,
                'name'          =>$user->name,
                'last_name'     =>$user->last_name,
                'email'         =>$user->email,
                'phone_number'  =>$user->phone_number,
                'active'        =>$user->active,
                'type'          =>$user->type,
                'api_token'     =>$user->api_token,
                'photo'         => $photo,
                'country_id'    =>$user->country_id,
                'truckle_id'    =>$user->truckle_id,
                'truckle'       => $user->truckle_id==null ? null :$user->truckle->hi_name,
                'category_id'   => $user->category_id,
                'category'      => $user->category_id==null?null :$user->category->hi_name,
                'identity'      => $user->identity == null ? null : asset('/uploads/drivers/document/identity/'.$user->identity),
                'license'       => $user->license == null ? null : asset('/uploads/drivers/document/license/'.$user->license),
                'car_form'      => $user->car_form == null ? null : asset('/uploads/drivers/document/car_form/'.$user->car_form),
                'transportation_card'  => $user->transportation_card == null ? null : asset('/uploads/drivers/document/transportation_card/'.$user->transportation_card),
                'insurance'     => $user->insurance == null ? null : asset('/uploads/drivers/document/insurance/'.$user->insurance),
                'rate'          =>intval($user->rate),
                'count_rate'    =>$count_rate,
                'created_at'    =>$user->created_at->format('Y-m-d'),
            ]);
        }
            return $user
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
      }
      else
      {
        $errors = ['key'=>'get_driver_by_id',
        'value'=> trans('messages.not_found')
      ];
      return ApiController::respondWithErrorClient(array($errors));
      }
    }
    
    
    public function getClientById(Request $request,$id)
    { 

        $user = App\User::where('id' , $id)->where('type','1')->first();

        if($user)
        {
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

        
            return $user
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();
      }
      else
      {
        $errors = ['key'=>'get_client_by_id',
        'value'=> trans('messages.not_found')
      ];
      return ApiController::respondWithErrorClient(array($errors));
      }
    }
    
    /**
     *  Track
     * @Track 
     */
   public function track(Request $request)
    {
        $rules = [
            'longitude'    => 'required',
            'latitude'     => 'required',
            'driver_id'    => 'required|exists:users,id',
          ];
  
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        
        // $order = App\Order::where('state','=','active')
        // ->where('driver_id',$request->driver_id)
        // ->first();

        // if($order)
        // {
            $created  = App\Track::updateOrCreate([
                // 'order_id'  => $order->id,
                'driver_id' => $request->driver_id,],
                [
                    'longitude' => $request->longitude,
                    'latitude'  => $request->latitude,
                    
                ]);

            $all=[];
            
            array_push($all,[
                    'longitude'    =>$created->longitude,
                    'latitude'     =>$created->latitude,
                    // 'order_id'     =>$created->order_id,
                    'driver_id'    =>$created->driver_id
            ]);
            return $created
            ? ApiController::respondWithSuccess($all)
            : ApiController::respondWithServerErrorArray();
        // }
        // else
        // {
        //     $errors = ['key'=>'track',
        //     'value'=> trans('messages.no_orders')
        //     ];
        //     return ApiController::respondWithErrorClient(array($errors));
        // }

    }
    

    public function getTrack(Request $request,$driver_id)
     {
           $track = App\Track::where('driver_id',$driver_id)->first();
            $all=[];
            if($track)
            {
                array_push($all,[
                    'longitude'    =>$track->longitude,
                    'latitude'     =>$track->latitude,
                    // 'order_id'     =>$track->order_id,
                    'driver_id'    =>$track->driver_id ,
                ]);  
                return $track
                ? ApiController::respondWithSuccess($all)
                : ApiController::respondWithServerErrorArray();  
            }
            else
            {
                  $errors = ['key'=>'get_track',
                  'value'=> trans('messages.no_orders')
                ];
                return ApiController::respondWithErrorClient(array($errors));
            }
     }


}
