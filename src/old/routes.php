<?php

Route::group(['namespace' => 'Alcodo\PowerImage\Controllers'], function () {
    Route::get('/powerimage/{file}', 'PowerImageController@showFile');

    Route::get('/powerimage/{prefix}/{file}', 'PowerImageController@show')->where('prefix', '.+');


//        ->where('prefix', '^.*/[^\.]*$');

//    Route::get('/powerimage/{filepath}', [
//        'as' => 'powerimage.show',
//        'uses' => 'PowerImageController@show',
//    ])->where('filepath', '.+');

//    Route::get('/powerimage/{directory}/{type}/{filename}.{fileextension}', [
//        'as' => 'powerimage.show',
//        'uses' => 'PowerImageController@show',
//    ]);
});


//Route::get('user/{directory}/{name}', function ($id, $name) {
//    //
//})->where(['directory' => '[0-9]+', 'name' => '[a-z]+']);
//
//Route::get('user/{directory}', function ($name) {})->where('directory', '[A-Za-z]+');
