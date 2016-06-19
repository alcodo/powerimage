<?php

Route::group(['namespace' => 'Alcodo\PowerImage\Controllers'], function () {
    Route::get('/uploads/images/{path}', [
        'as' => 'powerimage.show',
        'uses' => 'PowerImage@show',
    ])->where('path', '.+');
});
