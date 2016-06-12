<?php

Route::group(['prefix' => '/api', 'middleware' => ['api', 'cors']], function () {

    Route::group(['prefix' => '/v1', 'namespace' => 'API\V1'], function () {

        Route::post('/accounts', 'AuthController@postRegister');

        Route::get('/recipes', 'RecipeController@index');

        Route::get('/recipes/{id}', 'RecipeController@show');
        
        Route::post('/recipes', 'RecipeController@store')->middleware([
            'auth.once', 'json',
        ]);

        Route::patch('/recipes/{id}', 'RecipeController@update')->middleware([
            'auth.once', 'json',
        ]);

        Route::delete('/recipes/{id}', 'RecipeController@destroy')->middleware([
            'auth.once', 'json',
        ]);

    });

});
