<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    \Illuminate\Support\Facades\Artisan::call('check::commission');
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*admin panel routes*/

Route::get('/admin/home', ['middleware'=> 'auth:admin', 'uses'=>'AdminController\HomeController@index'])->name('admin.home');

Route::prefix('admin')->group(function () {

    //admin auth
    Route::get('login', 'AdminController\Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'AdminController\Admin\LoginController@login')->name('admin.login.submit');
    Route::get('password/reset', 'AdminController\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'AdminController\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'AdminController\Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'AdminController\Admin\ResetPasswordController@reset')->name('admin.password.update');
    Route::post('logout', 'AdminController\Admin\LoginController@logout')->name('admin.logout');


    Route::group(['middleware'=> ['web','auth:admin']],function(){


        // public notifications
        Route::get('public_notifications' , 'AdminController\HomeController@public_notifications')->name('public_notifications');
        Route::post('store_public_notifications' , 'AdminController\HomeController@store_public_notifications')->name('storePublicNotification');
        Route::get('selected_notifications' , 'AdminController\HomeController@selected_notifications')->name('public_selected_notifications');
        Route::post('store_selected_notifications' , 'AdminController\HomeController@store_selected_notifications')->name('storePublicSelectedNotification');
        Route::get('client_notifications' , 'AdminController\HomeController@client_notifications')->name('public_client_notifications');
        Route::post('store_client_notifications' , 'AdminController\HomeController@store_client_notifications')->name('storePublicClientNotification');
        Route::get('driver_notifications' , 'AdminController\HomeController@driver_notifications')->name('public_driver_notifications');
        Route::post('store_driver_notifications' , 'AdminController\HomeController@store_driver_notifications')->name('storePublicDriverNotification');

        //change logo
        Route::get('/change-logo','AdminController\HomeController@changeLogo')->name('change_logo');
        Route::post('change-logo','AdminController\HomeController@LogoImage')->name('changeLogo.store');
        
        
        //setttings
        Route::get('settings','AdminController\SettingController@index');
        Route::post('add/settings','AdminController\SettingController@store');


        //about 
        Route::get('pages/about','AdminController\PageController@about');
        Route::post('add/pages/about','AdminController\PageController@store_about');

        //terms and conditions
        Route::get('pages/terms','AdminController\PageController@terms');
        Route::post('add/pages/terms','AdminController\PageController@store_terms');

        //user routes
        Route::get('users','AdminController\UserController@index')->name('User');
        Route::get('users/create','AdminController\UserController@create')->name('createUser');
        Route::post('users/store','AdminController\UserController@store')->name('storeUser');
        Route::get('users/edit/{id}','AdminController\UserController@edit')->name('editUser');
        Route::get('edit/userAccount/{id}/{type}','AdminController\UserController@edit_account');
        Route::post('update/userAccount/{id}/{type}','AdminController\UserController@update_account');
        Route::post('users/update/{id}','AdminController\UserController@update')->name('updateUser');
        Route::post('users/update/pass/{id}','AdminController\UserController@update_pass');
        Route::post('users/update/privacy/{id}','AdminController\UserController@update_privacy');
        Route::get('users/delete/{id}','AdminController\UserController@destroy')->name('deleteUser');

        //driver routes
        Route::get('drivers','AdminController\DriverController@index')->name('Driver');
        Route::get('drivers/create','AdminController\DriverController@create')->name('createDriver');
        Route::post('drivers/store','AdminController\DriverController@store')->name('storeDriver');
        Route::get('drivers/edit/{id}','AdminController\DriverController@edit')->name('editDriver');
        Route::get('edit/driverAccount/{id}/{type}','AdminController\DriverController@edit_account');
        Route::post('update/driverAccount/{id}/{type}','AdminController\DriverController@update_account');
        Route::post('drivers/update/{id}','AdminController\DriverController@update')->name('updateDriver');
        Route::post('drivers/update/pass/{id}','AdminController\DriverController@update_pass');
        Route::post('drivers/update/privacy/{id}','AdminController\DriverController@update_privacy');
        Route::get('drivers/delete/{id}','AdminController\DriverController@destroy')->name('deleteDriver');

        // countries  Routes
        Route::get('countries','AdminController\CountryController@index')->name('Country');
        Route::get('countries/create','AdminController\CountryController@create')->name('createCountry');
        Route::post('countries/store','AdminController\CountryController@store')->name('storeCountry');
        Route::get('countries/edit/{id}','AdminController\CountryController@edit')->name('editCountry');
        Route::post('countries/update/{id}','AdminController\CountryController@update')->name('updateCountry');
        Route::get('countries/delete/{id}','AdminController\CountryController@destroy')->name('deleteCountry');

         // cities  Routes
         Route::get('cities','AdminController\CityController@index')->name('City');
         Route::get('cities/create','AdminController\CityController@create')->name('createCity');
         Route::post('cities/store','AdminController\CityController@store')->name('storeCity');
         Route::get('cities/edit/{id}','AdminController\CityController@edit')->name('editCity');
         Route::post('cities/update/{id}','AdminController\CityController@update')->name('updateCity');
         Route::get('cities/delete/{id}','AdminController\CityController@destroy')->name('deleteCity');

        // truckles  Routes
        Route::get('truckles','AdminController\TruckleController@index')->name('Truckle');
        Route::get('truckle/create','AdminController\TruckleController@create')->name('createTruckle');
        Route::post('truckle/store','AdminController\TruckleController@store')->name('storeTruckle');
        Route::get('truckle/edit/{id}','AdminController\TruckleController@edit')->name('editTruckle');
        Route::post('truckle/update/{id}','AdminController\TruckleController@update')->name('updateTruckle');
        Route::get('truckle/delete/{id}','AdminController\TruckleController@destroy')->name('deleteTruckle');
      
        // Categories  Routes
        Route::get('categories','AdminController\CategoryController@index')->name('Category');
        Route::get('categories/create','AdminController\CategoryController@create')->name('createCategory');
        Route::post('categories/store','AdminController\CategoryController@store')->name('storeCategory');
        Route::get('categories/edit/{id}','AdminController\CategoryController@edit')->name('editCategory');
        Route::post('categories/update/{id}','AdminController\CategoryController@update')->name('updateCategory');
        Route::get('categories/delete/{id}','AdminController\CategoryController@destroy')->name('deleteCategory');
        
        //splash routes
        Route::get('splashes','AdminController\SplashController@index')->name('Splash');
        Route::get('splashes/create','AdminController\SplashController@create')->name('createSplash');
        Route::post('splashes/store','AdminController\SplashController@store')->name('storeSplash');
        Route::get('splashes/edit/{id}','AdminController\SplashController@edit')->name('editSplash');
        Route::post('splashes/update/{id}','AdminController\SplashController@update')->name('updateSplash');
        Route::get('splashes/delete/{id}','AdminController\SplashController@destroy')->name('deleteSplash');

        //order routes
        Route::get('/orders/new', 'AdminController\OrderController@new')->name('orders.new');
        Route::get('/orders/active', 'AdminController\OrderController@active')->name('orders.active');
        Route::get('/orders/completed', 'AdminController\OrderController@completed')->name('orders.completed');
        Route::get('/orders/rejected', 'AdminController\OrderController@rejected')->name('orders.rejected');
        Route::get('/orders/{id}/delete', 'AdminController\OrderController@destroy')->name('orders.delete');
        Route::get('/show/{id}', 'AdminController\OrderController@show')->name('order.show');


       //payment routes
        Route::get('/payments/new', 'AdminController\PaymentController@new')->name('payments.new');
        Route::get('/payments/paid', 'AdminController\PaymentController@paid')->name('payments.paid');
        Route::get('/payments/confirmed', 'AdminController\PaymentController@confirmed')->name('payments.confirmed');
        Route::get('/payments/{id}/delete', 'AdminController\PaymentController@destroy')->name('payments.delete');
        Route::get('payments/{id}/update-status', 'AdminController\PaymentController@updateStatus')->name('payments.update-status');
        Route::post('payments/{id}/update-status', 'AdminController\PaymentController@postUpdateStatus')->name('payments.post-update-status');


         //promo-code
         Route::get('promo-codes','AdminController\PromoCodeController@index')->name('PromoCode');
         Route::get('promo-codes/create','AdminController\PromoCodeController@create')->name('createPromoCode');
         Route::post('promo-codes/store','AdminController\PromoCodeController@store')->name('storePromoCode');
         Route::get('promo-codes/edit/{id}','AdminController\PromoCodeController@edit')->name('editPromoCode');
         Route::post('promo-codes/update/{id}','AdminController\PromoCodeController@update')->name('updatePromoCode');
         Route::get('promo-codes/delete/{id}','AdminController\PromoCodeController@destroy')->name('deletePromoCode');
 

        // Admins Route
        Route::resource('admins', 'AdminController\AdminController');

        Route::get('/profile', [
            'uses' => 'AdminController\AdminController@my_profile',
            'as' => 'my_profile' // name
        ]);
        Route::post('/profileEdit', [
            'uses' => 'AdminController\AdminController@my_profile_edit',
            'as' => 'my_profile_edit' // name
        ]);
        Route::get('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass',
            'as' => 'change_pass' // name
        ]);
        Route::post('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass_update',
            'as' => 'change_pass' // name
        ]);

        Route::get('/admin_delete/{id}', [
            'uses' => 'AdminController\AdminController@admin_delete',
            'as' => 'admin_delete' // name
        ]);

    });



});
Route::get('/Privacy-Policy' , function ()
{
   return view('admin.privacyAndPolicy');
});