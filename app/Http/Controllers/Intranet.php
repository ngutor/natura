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
        //return "zona de usuarios<br/>Tipo: " . Auth::user()->tp_cliente;
        /*Auth::logout();
        return redirect("login");*/
        $user = Auth::user();
        $arrData = ["user" => $user];
        return view($user->tp_cliente . ".index")->with($arrData);
    }

}