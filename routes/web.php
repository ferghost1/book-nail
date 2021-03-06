<?php

use Illuminate\Support\Facades\Route;
use Cookie as Cook;
use App\Http\Requests\TestMakeRequest;
// use auth;

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
    return view('frontend');
});
Route::get('csrf', function () {
    return csrf_token();
});
// customer login
Route::post('users/cusLogin', 'UserController@cusLogin');
Route::post('users/checkCusLogin', 'UserController@checkCusLogin');

// admin login
Route::get('login', function () {
    return view('backend-login');
});
Route::post('login', 'UserController@backendLogin')->name('login-admin');
Route::middleware(['checkLogin:1,login'])->group(function () {
	Route::get('/admin', function () {
    	return view('admin');
	});
});

// staff login
Route::middleware(['checkLogin:2,login'])->group(function () {
	Route::get('/staff', function () {
    	// return view('staff');
	});
});

// logout for all type users
Route::post('users/logout', 'UserController@logout');


// get service every type can access
Route::get('booking/getLocations', 'BookingController@getLocations');
Route::get('booking/Services', 'BookingController@getServices');
Route::get('booking/getServiceByLocation', 'BookingController@getServiceByLocation');
Route::get('booking/getServiceRelation', 'BookingController@getServiceRelation');
Route::get('booking/getServiceEmployees', 'BookingController@getServiceEmployees');
Route::get('booking/getEmployeeBookedTime', 'BookingController@getEmployeeBookedTime');
Route::get('booking/getEmployeeByLocation', 'BookingController@getEmployeeByLocation');

// type user = 3 is customers
Route::middleware(['checkLogin:3'])->group(function () {
	Route::post('booking/book', 'BookingController@book')->middleware('checkBlockedUser');
	Route::post('users/updateCustomerProfile', 'UserController@updateCustomerProfile');
	Route::get('booking/getCustomerAppointments', 'BookingController@getCustomerAppointments');
});

// type user = 2 is staffs
Route::middleware(['checkLogin:2'])->group(function () {
	Route::get('staff/getOwnAppointments', 'StaffController@getOwnAppointments');
});

// type user = 1 is admin
Route::middleware(['checkLogin:1'])->group(function () {
	Route::post('admin/saveLocation', 'AdminController@saveLocation');
	Route::post('admin/deleteLocation', 'AdminController@deleteLocation');
	Route::get('admin/getServiceByLocation', 'AdminController@getServiceByLocation');
	Route::post('admin/saveService', 'AdminController@saveService');
	Route::post('admin/deleteService', 'AdminController@deleteService');
	Route::post('admin/saveEmployee', 'AdminController@saveEmployee');
	Route::post('admin/deleteEmployee', 'AdminController@deleteEmployee');
	Route::post('admin/saveServiceEmployee', 'AdminController@saveServiceEmployee');
	Route::get('admin/getAppointments', 'AdminController@getAppointments');
	Route::post('admin/saveAppointment', 'AdminController@saveAppointment');
	// Route::post('admin/changeStatusAppointment', 'AdminController@changeStatusAppointment');
	Route::get('admin/getUsers', 'AdminController@getUsers');
	Route::post('admin/changeUserActive', 'AdminController@changeUserActive');
	Route::post('admin/saveCustomer', 'AdminController@saveCustomer');
});
