<?php

Route::group(['prefix' => '/api', 'middleware' => ['api']], function () {

    Route::group(['prefix' => '/v1', 'namespace' => 'API\V1'], function () {

        Route::get('recipes', 'RecipeController@index');

        Route::get('/{username}/recipes', 'RecipeController@indexByUser');

        Route::get('/{username}/recipes/{id}', 'RecipeController@show');
        
        Route::post('/{username}/recipes', 'RecipeController@store')->middleware([
            'auth.once', 'json',
        ]);

        Route::patch('/{username}/recipes/{id}', 'RecipeController@update')->middleware([
            'auth.once', 'json',
        ]);

        Route::delete('/{username}/recipes/{id}', 'RecipeController@destroy')->middleware([
            'auth.once', 'json',
        ]);

    });

});
