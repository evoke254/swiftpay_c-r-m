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
Route::get('/', function () {
    return view('welcome');
});

*/

Auth::routes();
Route::resource('/admin', 'AdminController');

Route::get('/', 'DashboardController@index')->name('home');
Route::match(['get', 'post'], 'dashboard', 'DashboardController@index');
Route::resource('opportunities', 'OpportunitiesController');
Route::resource('products', 'ProductsController');
Route::get('/getproduct/{id}', 'ProductsController@getproduct');
Route::resource('sales', 'SalesController');
Route::resource('organizations', 'OrganizationController');
Route::resource('contacts', 'ContactsController');
Route::get('transactions', 'TransactionController@index');
Route::get('transactions/{id}', 'TransactionController@show');
Route::resource('employees', 'EmployeeController');
Route::resource('pointofsales', 'PointofsaleController');
Route::post('/posorders', 'PointofsaleController@orders');
Route::get('/pendingbills', 'PointofsaleController@pendingBills');
Route::get('/clearedbills', 'PointofsaleController@clearedBills');
/*
/*
* Quotes Routes
*/
Route::resource('quotes', 'QuotesController');
Route::get('quotes/create/{moduleName}/{assignedTo}', 'QuotesController@create');
Route::post('/quotestore', 'QuotesController@quotestore');
Route::post('/quotesUpdate/{id}', 'QuotesController@quotesUpdate');

/*
* Invoices Routes
*/
Route::resource('invoices', 'InvoicesController');
Route::get('invoices/create/{moduleName}/{assignedTo}', 'InvoicesController@create');
Route::post('/invoicestore', 'InvoicesController@invoicestore');
Route::post('/invoicesUpdate/{id}', 'InvoicesController@invoicesUpdate');


/*
*Chats / Conversation routes. Axios
*
*/
Route::get('moduleChats/{module_name}/{object_id}', 'ModulechatsController@retrieve');
Route::post('moduleChats', 'ModulechatsController@send');

/*
*Routes handles everything to do with calendar
*
*/
Route::get('calendar/{module_name}/{object_id}', 'CalendarController@retrieve');
Route::get('calendar/{selectedEvent}', 'CalendarController@retrieveSelected');
Route::get('calendarcount/{module_name}/{object_id}', 'CalendarController@counttask');
Route::post('calendar', 'CalendarController@post');


Route::get('documents/{module_name}/{object_id}', 'DocumentsController@retrieve');
Route::post('documents/{module_name}/{object_id}', 'DocumentsController@post');

Route::post('/0XRifQ3yTPMYIGOp', 'MpesacallbacksController@posmpesa');