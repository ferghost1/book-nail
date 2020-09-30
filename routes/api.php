<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cookie as Cook;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('test-cookie', function(Request $req) {
// 	return dd(Cook::get('Authorize1'));
// });
// Route::post('/admin/login', 'UserController@backendLogin')->name('login-admin');
// Route::post('test-cookie', function(Request $req) {
// 	return response('ok')->cookie(Cook::make('name', 'val', 555));
// });

// Route::post('users/login', 'UserController@login');
// Route::post('users/logout', 'UserController@login');
// Route::post('users/backendLogin', 'UserController@backendLogin');

// Route::get('booking/getLocations', 'BookingController@getLocations');
// Route::get('booking/Services', 'BookingController@getServices');
// Route::get('booking/getServiceEmployees', 'BookingController@getServiceEmployees');
// Route::get('booking/getEmployeeBooked', 'BookingController@getEmployeeBooked');

// // type user = 3 is customers
// Route::middleware(['checkLogin:3'])->group(function () {

// 	Route::post('booking/book', 'BookingController@book');
// 	Route::post('users/updateCustomerProfile', 'UserController@updateCustomerProfile');
// 	Route::get('booking/getCustomerAppointments', 'BookingController@getCustomerAppointments');
// 	Route::post('booking/updateProfile', 'UserController@updateProfile');
// });

// // type user = 3 is staffs
// Route::middleware(['checkLogin:2'])->group(function () {
// 	Route::get('staff/getOwnAppointments', 'StaffController@getOwnAppointments');
// });

// // type user = 3 is admin
// Route::middleware(['checkLogin:1'])->group(function () {
// 	Route::post('admin/saveLocation', 'AdminController@saveLocation');
// 	Route::post('admin/deleteLocation', 'AdminController@deleteLocation');
// 	Route::post('admin/saveService', 'AdminController@saveService');
// 	Route::post('admin/deleteService', 'AdminController@deleteService');
// 	Route::post('admin/saveEmployee', 'AdminController@saveEmployee');
// 	Route::post('admin/deleteEmployee', 'AdminController@deleteEmployee');
// 	Route::post('admin/saveServiceEmployee', 'AdminController@saveServiceEmployee');
// 	Route::get('admin/getAppointments', 'AdminController@getAppointments');
// 	Route::post('admin/saveAppointment', 'AdminController@saveAppointment');
// 	// Route::post('admin/changeStatusAppointment', 'AdminController@changeStatusAppointment');
// 	Route::get('admin/getUsers', 'AdminController@getUsers');
// 	Route::post('admin/changeUserActive', 'AdminController@changeUserActive');
// 	Route::post('admin/saveUserProfile', 'AdminController@saveUserProfile');
// });

// Route::middleware(['checkLogin'])->group(function () {
// 	Route::post('admin/test-chet', 'AdminController@saveLocation');

// });

