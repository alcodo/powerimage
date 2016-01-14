<?php

Route::group(['namespace' => 'Alcodo\PowerImage\Controllers'], function () {
    Route::get('/uploads/images/{image}', 'ImageController@show');
});