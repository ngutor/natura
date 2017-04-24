<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use App\User as User;

class Intranet extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("auth");
    }

    public function home() {
        $user = Auth::user();
        $arrData = ["user" => $user, "idx" => 0];
        return view($user->tp_cliente . ".index")->with($arrData);
    }

    public function revistas() {
        $user = Auth::user();
        $arrData = [
            "user" => $user,
            "idx" => 1,
            "route_i" => env("APP_WS") . "/client/indicadores/entrega-ini",
            "route_d" => env("APP_WS") . "/client/revistas/busca",
            "route_det" => env("APP_WS") . "/client/revistas/detalle"
        ];
        return view($user->tp_cliente . ".revistas")->with($arrData);
    }

    public function indicadores_entrega() {
        $user = Auth::user();
        $arrData = [
            "user" => $user,
            "idx" => 4,
            "route_i" => env("APP_WS") . "/client/indicadores/entrega-ini"
        ];
        return view($user->tp_cliente . ".indicadores-entrega")->with($arrData);
    }

    public function logout() {
        Auth::logout();
        return redirect("login");
    }

}