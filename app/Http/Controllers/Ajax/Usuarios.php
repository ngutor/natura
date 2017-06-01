<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use Session;
use App\User as User;

class Usuarios extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("auth");
    }
    
    public function dt_busqueda() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($cno,$grn,$str,$prf)) {
                $cnos = isset($cno) ? $cno : [];
                $gerencias = isset($grn) ? $grn : [];
                $sectores = isset($str) ? $str : [];
                $perfiles = isset($prf) ? $prf : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $busqueda = DB::table("seg_user as su")
                    ->join("seg_perfiles as sp", "sp.i_CodTipoPerfil", "=", "su.i_CodTipoPerfil")
                    ->whereIn("su.v_PerClienteAgrupa1", $gerencias)
                    ->whereIn("su.v_PerClienteAgrupa2", $sectores)
                    ->whereIn("su.v_PerClienteAgrupa3", $cnos)
                    ->whereIn("su.i_CodTipoPerfil", $perfiles)
                    ->whereRaw("su.v_IdPerCliente like '" . $codigo . "'")
                    ->select("su.v_IdPerCliente as codigo", DB::raw("concat(su.v_Nombres,' ',su.v_Apellidos) as nombre"), "sp.v_NombrePerfil as perfil", "sp.v_CodEstado as estado", "su.v_Codusuario as code");
                $data = $busqueda->get();
                $nRows = $busqueda->count();
                $pages = floor($nRows / 5) + ($nRows % 5 == 0 ? 0 : 1);
                return Response::json([
                    "success" => true,
                    "data" => $data,
                    "pages" => $pages,
                    "records" => $nRows
                ]);
            }
            else return Response::json([
                "success" => false,
                "message" => "Parámetros incorrectos"
            ]);
        }
        else return Response::json([
            "success" => false,
            "message" => "No tiene permisos para acceder aquí"
        ]);
    }

    public function sv_usuario() {
        if(Request::ajax()) {
            $usuario = Auth::user();
            extract(Request::input());
            if(isset($mod,$nom,$ape,$prf,$tpd,$nrd,$eml,$tlf,$grn,$str,$cno,$als,$psw,$rpw)) {
                if(strcmp($mod, "ins") == 0) { //nuevo usuario
                    $count = DB::table("seg_user")->where("v_Codusuario", $als)->count();
                    if($count == 0) {//proceder a insertar
                        switch($prf) {
                            case "1":$tp_cliente = "admin";break;
                            case "2":$tp_cliente = "usuario-gv";break;
                            case "3":$tp_cliente = "usuario-gr";break;
                            case "4":$tp_cliente = "clientes";break;
                            default:break;
                        }
                        DB::table("seg_user")->insert([
                            "v_Codusuario" => $als,
                            "v_Apellidos" => $ape,
                            "v_Nombres" => $nom,
                            "c_TipoDocide" => $tpd,
                            "v_NroDocide" => $nrd,
                            "v_Email" => $eml,
                            "v_Telefonos" => $tlf,
                            "v_IdPerCliente" => $cod,
                            "v_PerClienteAgrupa1" => $grn,
                            "v_PerClienteAgrupa2" => $str,
                            "v_PerClienteAgrupa3" => $cno,
                            "i_CodCliente" => $usuario->i_CodCliente,
                            "c_TipoUsuario" => "E",
                            "v_Clave" => $psw,
                            "v_CodEstado" => "Vigente",
                            "i_CodTipoPerfil" => $prf,
                            "tp_cliente" => $tp_cliente
                        ]);
                        return Response::json([
                            "success" => true,
                            "message" => "Usuario registrado con éxito"
                        ]);
                    }
                    else return Response::json([
                        "success" => false,
                        "message" => "El usuario \"" . $als . "\" ya existe. Utilice otro nombre de usuario"
                    ]);
                }
                else { //actualizar usuario
                    DB::table("seg_user")->where("v_Codusuario", $als)->update([
                        "v_Apellidos" => $ape,
                        "v_Nombres" => $nom,
                        "c_TipoDocide" => $tpd,
                        "v_NroDocide" => $nrd,
                        "v_Email" => $eml,
                        "v_Telefonos" => $tlf,
                        "v_IdPerCliente" => $cod,
                        "v_PerClienteAgrupa1" => $grn,
                        "v_PerClienteAgrupa2" => $str,
                        "v_PerClienteAgrupa3" => $cno,
                        "c_TipoUsuario" => "E",
                        "v_CodEstado" => "Vigente",
                        "i_CodTipoPerfil" => $prf
                    ]);
                    return Response::json([
                        "success" => true,
                        "message" => "Usuario actualizado con éxito"
                    ]);
                }
            }
            else return Response::json([
                "success" => false,
                "message" => "Parámetros incorrectos"
            ]);
        }
        else return Response::json([
            "success" => false,
            "message" => "No tiene permisos para acceder aquí"
        ]);
    }

    public function dt_usuario() {
        if(Request::ajax()) {
            $usuario = Auth::user();
            extract(Request::input());
            if(isset($cod)) {
                $user = DB::table("seg_user")
                    ->where("v_Codusuario", $cod)
                    ->select("v_Apellidos as ape", "v_Nombres as nom", "c_TipoDocide as tpd", "v_NroDocide as nrd", "v_Email as eml",
                        "v_Telefonos as tlf", "v_IdPerCliente as cod", "v_PerClienteAgrupa1 as grn", "v_PerClienteAgrupa2 as str",
                        "v_PerClienteAgrupa3 as cno", "i_CodTipoPerfil as prf", "v_Codusuario as als")
                    ->first();
                return Response::json([
                    "success" => true,
                    "user" => $user
                ]);
            }
            else return Response::json([
                "success" => false,
                "message" => "Parámetros incorrectos"
            ]);
        }
        else return Response::json([
            "success" => false,
            "message" => "No tiene permisos para acceder aquí"
        ]);
    }

    public function upd_banner() {
        //r-digital
        $file = $_FILES["banner"];
        $file_path = implode(DIRECTORY_SEPARATOR, [public_path(),"asset","img","r-digital.jpg"]);
        if(isset($file["type"]) && $file["type"] == "image/jpeg") {
            $tmp_name = $file["tmp_name"];
            @unlink($file_path);
            if(rename($tmp_name, $file_path)) {
                return redirect("/");
            }
            else {
                Session::flash("error", "No se pudo subir la imagen");
                return redirect("/");
            }
        }
        else {
            Session::flash("error", "Archivo inválido. Recuerde que debe elegir un archivo de imagen en formato JPEG.");
            return redirect("/");
        }
    }

    public function sv_perfil() {
        extract(Request::input());
        $user = Auth::user();
        if(strcmp($user->v_Codusuario, $codigo) == 0) {
            if(isset($pass,$rpass) && strcmp($pass,$rpass) == 0) {
                DB::table("seg_user")
                    ->where("v_Codusuario",$user->v_Codusuario)
                    ->where("v_IdPerCliente", $cno)
                    ->update([
                        "v_Nombres" => $nombre,
                        "v_Apellidos" => $apellidos,
                        "v_Telefonos" => $telf,
                        "v_Email" => $email
                    ]);
            }
            else {
                DB::table("seg_user")
                    ->where("v_Codusuario",$user->v_Codusuario)
                    ->where("v_IdPerCliente", $cno)
                    ->update([
                        "v_Nombres" => $nombre,
                        "v_Apellidos" => $apellidos,
                        "v_Telefonos" => $telf,
                        "v_Email" => $email,
                        "v_Clave" => $pass
                    ]);
            }
            Session::flash("message","Datos actualizados!");
        }
        else Session::flash("message","Los datos ingresados son inválidos. Intente nuevamente.");
        return redirect("perfil");
    }

}