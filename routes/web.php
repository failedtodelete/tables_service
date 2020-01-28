<?php

Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    Route::get('/{any}', 'AppController@app')->where('any', '.*');
});
