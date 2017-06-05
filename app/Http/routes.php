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

Route::get("psw", function() {
	return Hash::make(env("APP_DEFAULT_PSW"));
});
Route::get("clave", function() {
	$array = ["user" => "miguel", "token" => "asidnspadbasyiudnbasioudb"];
	return view("emails.recupera_psw")->with($array);
});

//autenticacion
Route::get("/", "Intranet@home");
Route::get("login", "Publico@login");
Route::get("recupera", "Publico@recupera_clave");
	Route::post("recupera", "Publico@post_recupera_clave");
	Route::post("login", "Publico@validate_login");
	Route::get("generaclave/{cod}/{token}", "Publico@reset_password");
	Route::post("generaclave", "Publico@post_reset_password");
	Route::get("clavegenerada", "Publico@confirm_password");
Route::get("logout", "Publico@logout");
//
Route::get("revistas", "Intranet@revistas");
Route::get("auditoria", "Intranet@auditoria");
Route::get("indicadores/entrega", "Intranet@indicadores_entrega");
Route::get("indicadores/reclamos", "Intranet@indicadores_reclamos");
Route::get("reclamos", "Intranet@reclamos");
Route::get("usuarios", "Intranet@usuarios");
Route::get("perfil", "Intranet@perfil");

//Ajax
Route::group(["prefix" => "ajax", "namespace" => "Ajax"], function() {
	Route::post("upd_banner", "Usuarios@upd_banner");
	Route::group(["prefix" => "revistas"], function() {
		Route::post("busca", "Revistas@dt_busqueda");
			Route::post("busca/clientes", "Revistas@dt_busqueda_clientes");
			Route::post("busca/usuariogr", "Revistas@dt_busqueda_usuariogr");
		Route::post("detalle", "Revistas@ls_detalle");
		Route::post("reclamo", "Revistas@sv_reclamo");
		Route::post("auditoria/agregar", "Revistas@registra_auditoria");
		Route::post("auditoria/quitar", "Revistas@elimina_auditoria");
		Route::post("email", "Revistas@send_email");
	});
	Route::group(["prefix" => "auditoria"], function() {
		Route::post("busca", "Auditoria@dt_busqueda");
		Route::post("detalle", "Auditoria@ls_detalle");
			Route::post("datos", "Auditoria@ls_datos");
			Route::post("cmb-subestados", "Auditoria@cmb_subestados");
			Route::post("actualiza", "Auditoria@upd_auditoria");
	});
	Route::group(["prefix" => "indicadores"], function() {
		Route::post("reclamos", "Indicadores@dt_reclamos");
		Route::post("gestion", "Indicadores@dt_gestion_reclamos");
		Route::post("emailreclamo", "Indicadores@send_email_reclamos");
	});
	Route::group(["prefix" => "reclamos"], function() {
		Route::post("busca", "Reclamos@dt_busqueda");
		Route::post("busca/usuariogr", "Reclamos@dt_busqueda_usuariogr");
		Route::post("detalle", "Reclamos@ls_detalle");
		Route::post("edicion", "Reclamos@dt_reclamo");
		Route::post("upd_reclamo", "Reclamos@actualiza_reclamo");
	});
	Route::group(["prefix" => "usuarios"], function() {
		Route::post("busca", "Usuarios@dt_busqueda");
		Route::post("registro", "Usuarios@sv_usuario");
		Route::post("edicion", "Usuarios@dt_usuario");
		Route::post("actualiza", "Usuarios@sv_perfil");
	});
	Route::group(["prefix" => "combos"], function() {
		Route::post("ls_gerencias", "Combos@combo_gerencias");
		Route::post("ls_sectores", "Combos@combo_sectores");
		Route::post("ls_cnos", "Combos@combo_cnos");
		Route::post("ls_efinal", "Combos@combo_estadofinal");
		Route::post("ls_subestados", "Combos@combo_subestados");
	});
	//Route::post("login", "Ajax@validate_login");
});
//XLS
Route::group(["prefix" => "export"], function() {
	Route::post("revistas", "Export@revistas");
	Route::post("auditoria", "Export@auditoria");
	Route::post("reclamos", "Export@reclamos");
	Route::post("usuarios", "Export@usuarios");
	Route::post("descarga", "Export@descarga_reclamos");
	Route::post("reclamo", "Export@reclamo_individual");
});
Route::group(["prefix" => "import"], function() {
	Route::post("upload", "Import@sube_reclamo");
});