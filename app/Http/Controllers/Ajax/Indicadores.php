<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use Mail;
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
                    ->whereIn("da.GrupoCliente", $cno)
                    ->whereIn("da.NroDocuCliente", $grn)
                    ->whereIn("da.Sector", $str)
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
                    ->where("atc.cTipoGestionAtc", "R")
                    //->whereIn("ast.vConclusion", $ein)
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

    function send_email_reclamos() {
        ini_set('max_execution_time', 180);
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno,$grn,$str,$mail,$msn)) {
                $estados = isset($est) ? $est : [];
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $gerencias = isset($grn) ? $grn : [];
                $sectores = isset($str) ? $str : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $result = DB::table("atc_cab as cab")
                    ->join("seg_user as usr","cab.vUsuRegistra", "=", "usr.v_Codusuario")
                    ->join("guias_ingreso as gi", "gi.CodAutogen", "=", "cab.CodAutogen")
                    ->join("envios_x_proceso as exp", function($join_exp) {
                        $join_exp->on("exp.CodAutogen", "=", "cab.CodAutogen")
                            ->on("exp.NroProceso", "=", "cab.NroProceso")
                            ->on("exp.NroControl", "=", "cab.NroControl");
                    })
                    ->join("datos_adicionales as da", function($join_da) {
                        $join_da->on("exp.CodAutogen", "=", "da.CodAutogen")
                            ->on("exp.NroProceso", "=", "da.NroProceso")
                            ->on("exp.NroControl", "=", "da.NroControl");
                    })
                    ->join("atc_estados_gestion as ast", function($join_ast) {
                        $join_ast->on("ast.cTipoGestionAtc", "=", "cab.cTipoGestionAtc")
                            ->on("ast.iCodConclusion", "=", "cab.iCodConclusion");
                    })
                    ->where("cab.cTipoGestionAtc", "R")
                    ->where("ast.vestado", "PENDIENTE")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.NroDocuCliente", $gerencias)
                    ->whereIn("da.Sector", $sectores)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("gi.CiCloCorteFactuCliente as ciclo", "da.IdeDestinatario as consultora", "cab.fEnvio as fecha", DB::raw("CONCAT(usr.v_Apellidos,' ', usr.v_Nombres) as generado"), "iNroDiasGestion as dias", "ast.vestado as einic", "ast.vConclusion as efinal")
                    ->get();
                $filename = "rep_visitas_" . date("Ymd_His");
                Excel::create($filename, function($excel) use($result, $estados, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                    $excel->sheet("Data", function($sheet) use($result, $estados, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                        $sheet->loadView("xls.xls_reclamos", [
                            "filas" => $result,
                            "estados" => $estados,
                            "ciclos" => $ciclos,
                            "cnos" => $cnos,
                            "gerencias" => $gerencias,
                            "sectores" => $sectores,
                            "codigo" => $codigo
                        ]);
                    });
                })->store("xls");
                $pos = strpos($mail, ";");
                if ($pos !== false) $mail = explode(";", $mail);
                $data = ["bodyText" => ((isset($msn) && $msn != "") ? $msn : ("Estimado usuario, se adjunta el reporte de Revistas solicitado."))];
                Mail::send("emails.adjunta_xls", $data, function ($message) use ($mail, $filename) {
                    $message->to($mail);
                    $message->subject("Reporte de revistas");
                    $message->attach(storage_path("exports") . DIRECTORY_SEPARATOR . $filename . ".xls");
                });
                return Response::json([
                    "success" => true,
                    "message" => "Se envió el reporte"
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