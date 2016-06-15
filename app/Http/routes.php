<?php

Route::group(['prefix' => '/api', 'middleware' => ['api', 'cors']], function () {

    Route::group(['prefix' => '/v1', 'namespace' => 'API\V1'], function () {

        Route::post('/auth', 'AuthController@authenticate');

        Route::post('/accounts', 'AuthController@postRegister');

        Route::get('/recipes', 'RecipeController@index');

        Route::get('/recipes/{id}', 'RecipeController@show');
        
        Route::group(['middleware' => 'json', 'jwt.auth'], function () {

            Route::post('/recipes', 'RecipeController@store');

            Route::patch('/recipes/{id}', 'RecipeController@update');

            Route::delete('/recipes/{id}', 'RecipeController@destroy');

            Route::post('/recipes/{id}/image', 'RecipeController@postImage');

        });

        Route::get('/users', 'UserController@index');

        Route::resource('recipes.comments', 'CommentController', [
            'only' => ['index', 'store', 'destroy'],
        ]);

    });

});
