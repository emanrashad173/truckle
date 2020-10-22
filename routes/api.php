<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
  });

Route::prefix('v1')->group(function () {
    Route::group(['middleware' =>  'cors'], function () {
        
        // get data before user has register (General)
        Route::get( '/countries', [
            'uses' => 'Api\GeneralController@countries',
            'as'   => 'countries'
        ] );
        Route::get( '/cities', [
            'uses' => 'Api\GeneralController@cities',
            'as'   => 'cities'
        ] );
        Route::get( '/truckles', [
            'uses' => 'Api\GeneralController@truckles',
            'as'   => 'truckles'
        ] );
       
        Route::get( '/categories', [
            'uses' => 'Api\GeneralController@categories',
            'as'   => 'categories'
        ] );
        Route::get( '/get-order-by-id/{id}', [
            'uses' => 'Api\GeneralController@getOrderById',
            'as'   => 'get-order-by-id'
        ] );
        
         Route::get( '/get-driver-by-id/{id}', [
            'uses' => 'Api\GeneralController@getDriverById',
            'as'   => 'get-driver-by-id'
        ] );
        
         Route::get( '/get-client-by-id/{id}', [
            'uses' => 'Api\GeneralController@getClientById',
            'as'   => 'get-client-by-id'
        ] );
        // store user device
        Route::post( '/device-token', [
            'uses' => 'Api\GeneralController@deviceToken',
            'as'   => 'device-token'
        ] );

        //pages
        Route::get( '/terms-and-conditions', [
            'uses' => 'Api\GeneralController@termsAndConditions',
            'as'   => 'terms-and-conditions'
        ] );
        Route::get( '/about-us', [
            'uses' => 'Api\GeneralController@aboutUs',
            'as'   => 'about-us'
        ] );


        Route::prefix('client')->group(function () {
                // client register
                Route::post( '/register-mobile', [
                    'uses' => 'Api\AuthClientController@registerMobile',
                    'as'   => 'register-mobile'
                ] );
                Route::post( '/phone-verification/{lang}', [
                    'uses' => 'Api\AuthClientController@verifyPhone',
                    'as'   => 'phone-verification'
                ] );
                Route::post( '/resend-code', [
                    'uses' => 'Api\AuthClientController@resendCode',
                    'as'   => 'resend-code'
                ] );
                Route::post( '/register', [
                    'uses' => 'Api\AuthClientController@register',
                    'as'   => 'register'
                ] );
                Route::post( '/login', [
                    'uses' => 'Api\AuthClientController@login',
                    'as'   => 'login'
                ] );
                Route::post( '/forget-password', [
                    'uses' => 'Api\AuthClientController@forgetPassword',
                    'as'   => 'forget-password'
                ] );
                Route::post( '/confirm-reset-code', [
                    'uses' => 'Api\AuthClientController@confirmResetCode',
                    'as'   => 'confirm-reset-code'
                ] );
                Route::post( '/reset-password', [
                    'uses' => 'Api\AuthClientController@resetPassword',
                    'as'   => 'reset-password'
                ] );

        });

        Route::prefix('driver')->group(function () {
            // client register
            Route::post( '/register-mobile', [
                'uses' => 'Api\AuthDriverController@registerMobile',
                'as'   => 'register-mobile'
            ] );
            Route::post( '/phone-verification/{lang}', [
                'uses' => 'Api\AuthDriverController@verifyPhone',
                'as'   => 'phone-verification'
            ] );
            Route::post( '/resend-code', [
                'uses' => 'Api\AuthDriverController@resendCode',
                'as'   => 'resend-code'
            ] );
            Route::post( '/register', [
                'uses' => 'Api\AuthDriverController@register',
                'as'   => 'register'
            ] );
            Route::post( '/login', [
                'uses' => 'Api\AuthDriverController@login',
                'as'   => 'login'
            ] );
            Route::post( '/forget-password', [
                'uses' => 'Api\AuthDriverController@forgetPassword',
                'as'   => 'forget-password'
            ] );
            Route::post( '/confirm-reset-code', [
                'uses' => 'Api\AuthDriverController@confirmResetCode',
                'as'   => 'confirm-reset-code'
            ] );
            Route::post( '/reset-password', [
                'uses' => 'Api\AuthDriverController@resetPassword',
                'as'   => 'reset-password'
            ] );

       });

    });



    Route::group(['middleware' => ['auth:api', 'cors']], function () {


        Route::prefix('client')->group(function () {

             //notification client
            Route::get('/list_notifications', 'Api\ApiController@listNotifications');
            Route::post('/delete_notification/{id}', 'Api\ApiController@deleteNotification');
        
            //get-track
            Route::get( '/get-track/{driver_id}', [
                'uses' => 'Api\GeneralController@getTrack',
                'as'   => 'get-track'
                ] );
           
            //Order-client
            Route::post( '/create-order', [
                'uses' => 'Api\OrderClientController@createOrder',
                'as'   => 'create-order'
            ] );

            Route::get( '/get-order', [
                'uses' => 'Api\OrderClientController@getOrder',
                'as'   => 'get-order'
            ] );
            Route::get( '/get-pending', [
                'uses' => 'Api\OrderClientController@getPending',
                'as'   => 'pending'
            ] );
            Route::get( '/get-new', [
                'uses' => 'Api\OrderClientController@getNew',
                'as'   => 'get-new'
            ] );
            Route::get( '/get-confirmed', [
                'uses' => 'Api\OrderClientController@getConfirmed',
                'as'   => 'get-confirmed'
            ] );
            Route::get( '/get-accepted-order/{id}', [
                'uses' => 'Api\OrderClientController@getAcceptedOrder',
                'as'   => 'get-accepted-order'
            ] );
           
            Route::get( '/confirmed/{id}', [
                'uses' => 'Api\OrderClientController@confirmed',
                'as'   => 'confirmed'
            ] );
            Route::post( '/canceled/{id}', [
                'uses' => 'Api\OrderClientController@canceled',
                'as'   => 'canceled'
            ] );
            Route::get( '/get-activated', [
                'uses' => 'Api\OrderClientController@getActivated',
                'as'   => 'activated'
            ] );
            Route::get( '/get-completed', [
                'uses' => 'Api\OrderClientController@getCompleted',
                'as'   => 'completed'
            ] );
            Route::get( '/get-rejected', [
                'uses' => 'Api\OrderClientController@getRejected',
                'as'   => 'rejected'
            ] );
            Route::post( '/rate/{id}', [
                'uses' => 'Api\OrderClientController@rate',
                'as'   => 'rate'
            ] );
            



            //profile client
            Route::post( '/change-password', [
                'uses' => 'Api\AuthClientController@changePassword',
                'as'   => 'change-password'
            ] );
            Route::post( '/change-phone', [
                'uses' => 'Api\AuthClientController@changePhoneNumber',
                'as'   => 'change-phone'
            ] );
            Route::post( '/check-code-change-phone', [
                'uses' => 'Api\AuthClientController@checkCodeChangeNumber',
                'as'   => 'check-code-change-phone'
            ] );

            Route::post( '/edit-profile', [
                'uses' => 'Api\AuthClientController@editProfile',
                'as'   => 'edit-profile'
            ] );
            Route::get( '/user-data', [
                'uses' => 'Api\GeneralController@getUserData',
                'as'   => 'user-data'
            ] );
        });


        Route::prefix('driver')->group(function () {

            //notification driver
            Route::get('/list_notifications', 'Api\ApiController@listNotifications');
            Route::post('/delete_notification/{id}', 'Api\ApiController@deleteNotification');
           
            //track
            Route::post( '/track', [
            'uses' => 'Api\GeneralController@track',
            'as'   => 'track'
            ] );

           //profile  driver
           Route::post( '/change-password', [
            'uses' => 'Api\AuthDriverController@changePassword',
            'as'   => 'change-password'
            ] );
           Route::post( '/change-phone', [
                'uses' => 'Api\AuthDriverController@changePhoneNumber',
                'as'   => 'change-phone'
            ] );
           Route::post( '/check-code-change-phone', [
                'uses' => 'Api\AuthDriverController@checkCodeChangeNumber',
                'as'   => 'check-code-change-phone'
            ] );

           Route::post( '/edit-profile', [
                'uses' => 'Api\AuthDriverController@editProfile',
                'as'   => 'edit-profile'
            ] );
             Route::post( '/edit-car-data', [
                'uses' => 'Api\AuthDriverController@editCarData',
                'as'   => 'edit-car-data'
            ] );

           Route::get( '/user-data', [
                'uses' => 'Api\GeneralController@getUserData',
                'as'   => 'user-data'
            ] );

            //order-driver
            Route::post( '/get-pending', [
                'uses' => 'Api\OrderDriverController@getPending',
                'as'   => 'pending'
            ] );
            Route::post( '/accepted/{id}', [
                'uses' => 'Api\OrderDriverController@accepted',
                'as'   => 'accepted'
            ] );
            Route::get( '/get-new', [
                'uses' => 'Api\OrderDriverController@getNew',
                'as'   => 'new'
            ] );
            Route::get( '/activated/{id}', [
                'uses' => 'Api\OrderDriverController@activated',
                'as'   => 'activated'
            ] );
            Route::get( '/get-activated', [
                'uses' => 'Api\OrderDriverController@getActivated',
                'as'   => 'activated'
            ] );
            Route::get( '/completed/{id}', [
                'uses' => 'Api\OrderDriverController@completed',
                'as'   => 'completed'
            ] );

            Route::get( '/get-completed', [
                'uses' => 'Api\OrderDriverController@getCompleted',
                'as'   => 'completed'
            ] );
            Route::get( '/get-rejected', [
                'uses' => 'Api\OrderDriverController@getRejected',
                'as'   => 'rejected'
            ] );
            Route::post( '/rate/{id}', [
                'uses' => 'Api\OrderDriverController@rate',
                'as'   => 'rate'
            ] );


            //payment 
            Route::get( '/get-new-payment', [
                'uses' => 'Api\OrderDriverController@newPayment',
                'as'   => 'get-new-payment'
            ] );
            Route::get( '/get-paid-payment', [
                'uses' => 'Api\OrderDriverController@paidPayment',
                'as'   => 'get-paid-payment'
            ] );
            Route::post( '/pay', [
                'uses' => 'Api\OrderDriverController@pay',
                'as'   => 'pay'
            ] );



            
       });
        
       


        
        //settings
        Route::get('/settings', 'Api\GeneralController@settings');

        // //refreshToken 
        // Route::post('/refresh-device-token', [
        //     'uses' => 'Api\DetailsController@refreshDeviceToken',
        //     'as'   => 'refreshDeviceToken'
        // ] );
        // Route::post('/refreshToken', [
        //     'uses' => 'Api\DetailsController@refreshToken',
        //     'as'   => 'refreshToken'
        // ] );

        // //logout
        // Route::post('/logout', [
        //     'uses' => 'Api\AuthController@logout',
        //     'as'   => 'logout'
        // ]);
    });
});
