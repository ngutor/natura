<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get("/", function () {
    return view("welcome");
});*/

Route::get("psw", function() {
	return Hash::make(env("APP_DEFAULT_PSW"));
});
Route::get("/", "Intranet@home");
Route::get("login", "Publico@login");
Route::get("revistas", "Intranet@revistas");
Route::get("indicadores/entrega", "Intranet@indicadores_entrega");
Route::get("indicadores/reclamos", "Intranet@indicadores_reclamos");
Route::get("logout", "Intranet@logout");

//ws
Route::group(["prefix" => "ajax", "namespace" => "Ws"], function() {
	Route::post("login", "Ajax@validate_login");
});