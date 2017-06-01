<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use Session;
use App\User as User;

class Indicadores extends Controller {
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
    
    public function dt_reclamos() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$grn,$str,$cno)) {
                $data = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.codautogen", "=", "da.Codautogen")
                            ->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl");
                    })
                    ->join("estados_envios", "envxp.CodEstadoWeb", "=", "estados_envios.CodEstadoEnvio")
                    ->join("motivos_envios", "envxp.CodMotivoWeb", "=", "motivos_envios.CodMotivoEnvio")
                    ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
                    ->whereRaw("gi.dtmguia >= '2017-01-01'")
                    ->where("gip.FlgIngresosReclamo", "N")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                    ->whereIn("da.NroDocuCliente", $grn)
                    ->whereIn("da.Sector", $str)
                    ->whereIn("da.GrupoCliente", $cno)
                    //->whereRaw("envxp.DtUltVisitaWeb is not null")
                    ->select("gi.CiCloCorteFactuCliente as ciclo","da.NroDocuCliente as gerencia","da.Sector as sector",
                        "da.GrupoCliente as cno","estados_envios.DesEstadoEnvio as estado",
                        "motivos_envios.DesMotivoEnvio as motivo",DB::raw("ifnull(date_format(envxp.DtUltVisitaWeb,'%Y-%m-%d'),'Pendiente') as fechavisita"),
                        DB::raw("COUNT(*) as cant"))
                    ->groupBy("ciclo","gerencia","sector","cno","estado","motivo","fechavisita")
                    ->get();
            	return Response::json([
            		"success" => true,
            		"data" => $data
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

    public function dt_gestion_reclamos() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$grn,$str,$cno,$ein,$efn)) {
                $data = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as exp", function($join_exp) {
                        $join_exp->on("exp.codautogen", "=", "da.Codautogen")
                            ->on("exp.nroproceso", "=", "da.Nroproceso")
                            ->on("exp.NroControl", "=", "da.NroControl");
                    })
                    ->join("estados_envios as een", "exp.CodEstadoWeb", "=", "een.CodEstadoEnvio")
                    ->join("motivos_envios as men", "exp.CodMotivoWeb", "=", "men.CodMotivoEnvio")
                    ->join("atc_cab as atc", function($join_atc) {
                        $join_atc->on("atc.CodAutogen", "=", "exp.CodAutogen")
                            ->on("atc.NroProceso", "=", "exp.NroProceso")
                            ->on("atc.NroControl", "=", "exp.NroControl");
                    })
                    ->join("atc_estados_gestion as aes", "aes.iCodConclusion", "=", "atc.iCodConclusion")
                    ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
                    ->whereRaw("gi.dtmguia >= '2017-01-01'")
                    ->where("gip.FlgIngresosReclamo", "N")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ccl)
                    ->whereIn("da.NroDocuCliente", $grn)
                    ->whereIn("da.Sector", $str)
                    ->whereIn("da.GrupoCliente", $cno)
                    ->whereIn("aes.vConclusion", $ein)
                    ->whereIn("aes.vEstado", $efn)
                    ->select("gi.CiCloCorteFactuCliente as ciclo","da.NroDocuCliente as gerencia","da.Sector as sector",
                        "da.GrupoCliente as cno","een.DesEstadoEnvio as estadoenv","men.DesMotivoEnvio as motivo",
                        DB::raw("ifnull(date_format(exp.DtUltVisitaWeb,'%Y-%m-%d'),'Pendiente') as fechavisita"),"aes.vConclusion as conclusion",
                        "aes.vEstado as estado",DB::raw("COUNT(*) as cant"))
                    ->groupBy("ciclo","gerencia","sector","cno","estadoenv","motivo","fechavisita","conclusion","estado")
                    ->get();
                return Response::json([
                    "success" => true,
                    "data" => $data
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