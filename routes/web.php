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
/*
 * Welcome to app
 */
Route::get('/tasks/disable', 'TasksController@disable')->name('tasks.disable');

Route::get('/tasks/enable', 'TasksController@enable')->name('tasks.enable');

Route::get('/tasks/complete', 'TasksController@complete')->name('tasks.complete');

Route::get('/tasks/notcomplete', 'TasksController@notcomplete')->name('tasks.notcomplete');

Route::get('/', function () {
    return view('welcome');
});
/*
 * All Lists
 */
Route::any('/lists', function () {
    return view('lists');
})->name('lists.home');
/*
 * All Tasks
 */
Route::any('/tasks', function () {
    return view('tasks');
})->name('tasks.home');

/*
 * Add New List
 */

/*
 * Add New Task
 */
/*
Route::post('/tasks', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    // Create The Task...
})
*/
Route::resource('/lists','ListsController');
Route::resource('/tasks','TasksController');
