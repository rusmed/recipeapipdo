<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get    ('recipes',         ['uses' => 'RecipeController@getRecipes']);
    $router->get    ('recipes/{id}',    ['uses' => 'RecipeController@getRecipe']);
    $router->post   ('recipes',         ['middleware' => 'auth', 'uses' => 'RecipeController@createRecipe']);
    $router->put    ('recipes/{id}',    ['middleware' => 'auth', 'uses' => 'RecipeController@updateRecipe']);
    $router->delete ('recipes/{id}',    ['middleware' => 'auth', 'uses' => 'RecipeController@deleteRecipe']);

    $router->post   ('images',          ['middleware' => 'auth', 'uses' => 'ImageController@uploadImage']);

    $router->post   ('signin',          ['uses' => 'AuthController@signin']);
    $router->post   ('signup',          ['uses' => 'AuthController@signup']);
});
