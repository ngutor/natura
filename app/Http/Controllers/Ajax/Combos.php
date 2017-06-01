<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use Session;
use App\User as User;

class Combos extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        ///$this->middleware("auth", ["except" => ["validate_login_z"]]);
        $this->middleware("auth");
    }
    
    public function combo_estadofinal() {
    	if(Request::ajax()) {
            extract(Request::input());
            if(isset($ein)) {
                $estados = DB::table("atc_estados_gestion")
                	->where("cTipoGestionAtc", "R")
                	->whereIn("vConclusion", $ein)
                	->select("vEstado as value","vEstado as text")
                    ->distinct()
                	->get();
                return Response::json([
                    "success" => true,
                    "data" => $estados
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

    public function combo_gerencias() {
    	if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl)) {
            	$user = Auth::user();
                $gerencias = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->select("da.NroDocuCliente as value")
                	->distinct()
                	->get();
                $sectores = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->select("da.Sector as value")
                	->distinct()
                	->get();
                $cnos = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->select("da.GrupoCliente as value")
                	->distinct()
                	->get();
                return Response::json([
                    "success" => true,
                    "gerencias" => $gerencias,
                    "sectores" => $sectores,
                    "cnos" => $cnos
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

    public function combo_sectores() {
    	if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$grn)) {
            	$user = Auth::user();
                $sectores = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->whereIn("da.NroDocuCliente", $grn)
                	->select("da.Sector as value")
                	->distinct()
                	->get();
                $cnos = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->whereIn("da.NroDocuCliente", $grn)
                	->select("da.GrupoCliente as value")
                	->distinct()
                	->get();
                return Response::json([
                    "success" => true,
                    "sectores" => $sectores,
                    "cnos" => $cnos
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

    public function combo_cnos() {
    	if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$grn,$sec)) {
            	$user = Auth::user();
                $cnos = DB::table("guias_ing_procesos as gip")
                	->join("datos_adicionales as da", function($join_da) {
                		$join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                			->on("da.NroProceso", "=", "gip.NroProceso");
                	})
                	->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
                	->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
                	->whereRaw("gi.DtmGuia >= '2017-01-01'")
                	->where("ac.CodCliente", $user->i_CodCliente)
                	->where("gip.FlgIngresosReclamo", "N")
                	->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                	->whereIn("da.NroDocuCliente", $grn)
                	->whereIn("da.Sector", $sec)
                	->select("da.GrupoCliente as value")
                	->distinct()
                	->get();
                return Response::json([
                    "success" => true,
                    "cnos" => $cnos
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

    public function combo_subestados() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($cls)) {
                $estados = DB::table("atc_estados_gestion")
                    ->where("cTipoGestionAtc", "A")
                    ->whereIn("vConClusion", $cls)
                    ->select("iCodConclusion as value","vEstado as text")
                    ->distinct()
                    ->get();
                return Response::json([
                    "success" => true,
                    "data" => $estados
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

}