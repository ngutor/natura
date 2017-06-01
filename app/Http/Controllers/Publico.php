<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Mail;
use Request;
use Session;
use App\User as User;

class Publico extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("guest", ["except" => ["logout"]]);
    }

    public function login() {
        $arrData = [];
        $error = Session::get("error");
        if($error != "") $arrData["err"] = $error;
        return view("public.login")->with($arrData);
    }

    public function validate_login() {
        extract(Request::input());
        if(isset($usr, $psw)) {
            if(Auth::attempt(["v_Codusuario" => $usr, "v_clave" => $psw, "password" => env("APP_DEFAULT_PSW")], true)) {
                return redirect("/");
            }
            else {
                Session::flash("error", "El usuario y/o clave ingresados son incorrectos");
                return redirect("login");
            }
        }
        else {
            Session::flash("error", "Parámetros incorrectos");
            return redirect("login");
        }
    }

    public function logout() {
        Auth::logout();
        return redirect("login");
    }

    public function recupera_clave() {
        $arrData = [];
        $error = Session::get("error");
        if($error != "") $arrData["err"] = $error;
        return view("public.recupera_clave")->with($arrData);
    }

    public function post_recupera_clave() {
        extract(Request::input());
        if(isset($name)) {
            $usuario = DB::table("seg_user")
                ->where("v_Codusuario", $name)
                ->orWhere("v_Email", $name)
                ->select("v_Codusuario as cod", "v_Email as mail");
            if($usuario->count() > 0) {
                $token = str_replace("/", "@", \Hash::make(date("YmdHis")));
                $usuario = $usuario->first();
                DB::table("seg_user")->where("v_Codusuario", $usuario->cod)->update([
                    "token" => $token
                ]);
                //enviar mail
                $data = [
                    "user" => $usuario->cod,
                    "token" => $token
                ];
                Mail::send("emails.recupera_psw", $data, function ($message) use ($usuario) {
                    $message->to($usuario->mail);
                    $message->subject("Recuperación de contraseña");
                });
                //fin mail
                Session::flash("error", "Se ha enviado un mensaje a su dirección de correo. Revíselo y siga las instrucciones para restablecer su contraseña.");
                return redirect("recupera");
            }
            else {
                Session::flash("error", "No se encontró registros con nombre de usuario o email <i>" . $name . "</i>. Intente nuevamente.");
                return redirect("login");
            }
        }
        else {
            Session::flash("error", "Debe ingresar su nombre de usuario o email para recuperar su clave");
            return redirect("login");
        }
    }

    public function reset_password($cod, $token) {
        $usuario = DB::table("seg_user")
            ->where("v_Codusuario", $cod)
            ->where("token", $token)
            ->count();
        if($usuario > 0) {
            $arrData = ["coduser" => $cod];
            return view("public.reset_password")->with($arrData);
        }
        else {
            Session::flash("error", "Su enlace de reestablecimiento ha expirado. Solicite el reestablecimiento de su contraseña nuevamente.");
            return redirect("login");
        }
    }

    public function post_reset_password() {
        extract(Request::input());
        if(isset($user,$pass,$passr)) {
            if(strcmp($pass, $passr) == 0) {
                DB::table("seg_user")->where("v_Codusuario", $user)->update([ "v_Clave" => $pass ]);
                return redirect("clavegenerada");
            }
            else {
                Session::flash("error", "Los campos de contraseña deben coincidir. Vuelva a solicitar una nueva contraseña.");
                return redirect("login");
            }
        }
        else {
            Session::flash("error", "Los campos de contraseña son obligatorios. Vuelva a solicitar una nueva contraseña.");
            return redirect("login");
        }
    }

    public function confirm_password() {
        return view("public.confirm_password");
    }

}