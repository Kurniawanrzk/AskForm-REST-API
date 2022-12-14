<?php


/** @var \Laravel\Lumen\Routing\Router $router */

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group(["prefix" => "api"], function() use($router) {
    $router->group(["prefix" => "auth"], function() use($router) {
        $router->post("login", "AuthController@login");
        $router->group(["middleware" => "auth"], function() use($router) {
            $router->get("me", "AuthController@me");
            $router->get("logout", "AuthController@logout");
        });
    });

    $router->group(["prefix" => "forms"], function() use($router) {
        $router->group(["middleware" => "auth"], function() use($router) {
         $router->post("/", "FormsController@create");
         $router->get("/", "FormsController@getAll");
         $router->get("/{slug}", "FormsController@detailForm");
         $router->post("/{slug}/questions", "QuestionsController@create");
         $router->delete("/{slug}/questions/{question_id}", "QuestionsController@delete");
         $router->post("/{slug}/responses", "ResponsesController@create");
        });
    });

});