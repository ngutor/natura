<?php

namespace App\Http\Controllers\Ws;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Ajax extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("auth", ["except" => ["validate_login"]]);
        //$this->middleware("guest");
    }

    public function validate_login() {
        if(Request::ajax()) {
            $post = Request::input();
            $ch = curl_init(implode([env("APP_WS"), "client", "login"], "/"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = json_decode(curl_exec($ch));
            if($response->success) {
                $data = $response->data;
                $countUser = DB::table("users")->where("cod_usuario", $data->cod_usuario)->count();
                if($countUser == 0) {
                    switch($data->tp_cliente) {
                        case 1: $folder = "clientes"; break;
                        case 2: $folder = "usuario-gv"; break;
                        case 3: $folder = "usuario-gr"; break;
                        case 4: $folder = "admin"; break;
                        default: $folder = "admin"; break;
                    }
                    DB::table("users")->insert([
                        "cod_usuario" => $data->cod_usuario,
                        "des_apellidos" => $data->des_apellidos,
                        "des_nombres" => $data->des_nombres,
                        "es_vigencia" => $data->es_vigencia,
                        "tp_cliente" => $folder,
                        "token" => $response->token
                    ]);
                }
                if(Auth::attempt(["cod_usuario" => $data->cod_usuario, "password" => env("APP_DEFAULT_PSW")], true)) {
                    return Response::json([
                        "success" => true
                    ]);
                }
                else {
                    return Response::json([
                        "success" => false,
                        "message" => "Ocurrió un problema al validar sus datos. Comuníquese con el administrador del sistema"
                    ]);
                }
            }
            else return Response::json($response);
        }
        else return Response::json([
            "success" => false,
            "message" => "No tiene permisos para acceder aquí"
        ]);
    }

}