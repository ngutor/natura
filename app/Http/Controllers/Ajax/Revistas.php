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

class Revistas extends Controller {
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
    
    public function dt_busqueda() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno,$grn,$str)) {
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $gerencias = isset($grn) ? $grn : [];
                $sectores = isset($str) ? $str : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $busqueda = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl")
                            ->on("envxp.codautogen", "=", "da.Codautogen");
                    })
                    ->join("motivos_envios as moti", "envxp.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
                    ->leftJoin("atc_cab as cab", "envxp.iCodAutogenAudi_ult", "=", "cab.iCodAutogenAtc")
                    ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
                    ->where("gi.dtmguia", ">=", "2017-01-01")
                    ->where("gip.FlgIngresosReclamo", "N")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.NroDocuCliente", $gerencias)
                    ->whereIn("da.Sector", $sectores)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("da.IdeDestinatario as codcn", "gi.CiCloCorteFactuCliente as ciclo", "moti.DesMotivoEnvio as situacion",
                        "da.CodAutogen as agn", "da.NroProceso as npr", "da.NroControl as nct",
                        DB::raw("if(envxp.iCodAutogenAudi_ult > 0,'check','uncheck') as auditoria"),"envxp.iCodAutogenAudi_ult as audit",
                        DB::raw("if(cab.iCodConclusion = 1 or envxp.iCodAutogenAudi_ult = 0, 'S', 'N') as enab"),"envxp.iCodAutogenAtc_ult as ereclamo");
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
    
    public function ls_detalle() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($agn,$npr,$ncn)) {
                $binfo = DB::table("datos_adicionales as da")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.codautogen", "=", "da.Codautogen")
                            ->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl");
                    })
                    ->join("direcciones as direc","envxp.CodDestinatario", "=", "direc.CodDestinatario")
                    ->join("vcode as vcd", "vcd.CodVcodAuto", "=", "direc.CodVcodAuto")
                    ->where("envxp.codautogen", $agn)
                    ->where("envxp.Nroproceso", $npr)
                    ->where("envxp.NroControl", $ncn)
                    ->select("direc.nomdestinatario as nombre", "direc.dirdestinatario as direccion", "da.nrotelefdesti as telefono", "da.situacioncliente as situac", "vcd.abrvcode as distrito", DB::raw("ifnull(envxp.FlgCargoPendiente,'S') as flag"))
                    ->first();
                $tracking = DB::table("envios_x_proceso_seg as seg")
                    ->join("motivos_envios as moti", "seg.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
                    ->join("pasos_procesos as pasos", "pasos.CodPaso", "=", "seg.CodPaso")
                    ->where("seg.codautogen", $agn)
                    ->where("seg.Nroproceso", $npr)
                    ->where("seg.NroControl", $ncn)
                    ->select(DB::raw("date_format(seg.DtmPaso,'%Y-%m-%d %H:%i') as fecha"), "pasos.DesPaso as estado", "seg.ObsResultado as observ")
                    ->get();
                return Response::json([
                    "success" => true,
                    "info" => $binfo,
                    "tracking" => $tracking
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

    public function sv_reclamo() {
        if(Request::ajax()) {
            $user = Auth::user();
            extract(Request::input());
            if(isset($tpo,$ttl,$msj,$agn,$npr,$ncn)) {
                DB::table("atc_cab")->insert([
                    "iCodContacto" => $user->i_CodContacto,
                    "fRegistro" => date("Y-m-d H:i:s"),
                    "vUsuRegistra" => $user->v_Codusuario,
                    "cTipoGestionAtc" => "R",
                    "iMotivoGestionAtc" => $tpo,
                    "vEstado" => "PENDIENTE",
                    "iCodConclusion" => 5,
                    "vAsunto" => $ttl,
                    "vDescripcion" => $msj,
                    "cFlgEnviado" => "N",
                    "CodAutogen" => $agn,
                    "NroProceso" => $npr,
                    "NroControl" => $ncn
                ]);
                return Response::json([
                    "success" => true,
                    "message" => "Se ha registrado el reclamo correctamente"
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

    public function registra_auditoria() {
        if(Request::ajax()) {
            $user = Auth::user();
            extract(Request::input());
            if(isset($agn,$npr,$ncn)) {
                $nuevo_autogen = DB::table("atc_cab")->insertGetId([
                    "iCodContacto" => $user->i_CodContacto,
                    "fRegistro" => date("Y-m-d H:i:s"),
                    "vUsuRegistra" => $user->v_Codusuario,
                    "cTipoGestionAtc" => 'A',
                    "iMotivoGestionAtc" => 700,  
                    "vEstado" => "PENDIENTE",
                    "iCodConclusion" => 1,
                    "vAsunto" => "Auditado por el Cliente",
                    "cFlgEnviado" => "S",
                    "CodAutogen" => $agn,
                    "NroProceso" => $npr,
                    "NroControl" => $ncn
                ]);
                return Response::json([
                    "success" => true,
                    "message" => "Auditoria registrada",
                    "autogen" => $nuevo_autogen
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

    public function elimina_auditoria() {
        if(Request::ajax()) {
            $user = Auth::user();
            extract(Request::input());
            if(isset($agn)) {
                DB::table("atc_cab")->where("iCodAutogenAtc", $agn)->delete();
                return Response::json([
                    "success" => true,
                    "message" => "Auditoria levantada"
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

    public function dt_busqueda_clientes() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno,$ccn)) {
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $codigos = isset($ccn) ? $ccn : [];
                $busqueda = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl")
                            ->on("envxp.codautogen", "=", "da.Codautogen");
                    })
                    ->join("motivos_envios as moti", "envxp.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
                    ->leftJoin("atc_cab as cab", "envxp.iCodAutogenAudi_ult", "=", "cab.iCodAutogenAtc")
                    ->where("gi.dtmguia", ">=", "2017-01-01")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.IdeDestinatario", $codigos)
                    ->select("da.IdeDestinatario as codcn", "gi.CiCloCorteFactuCliente as ciclo", "moti.DesMotivoEnvio as situacion",
                        "da.CodAutogen as agn", "da.NroProceso as npr", "da.NroControl as nct");
                $data = $busqueda->get();
                $nRows = $busqueda->count();
                $pages = floor($nRows / 5) + ($nRows % 5 == 0 ? 0 : 1);
                return Response::json([
                    "success" => true,
                    "data" => $data,
                    "pages" => $pages
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

    public function dt_busqueda_usuariogr() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno)) {
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $busqueda = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl")
                            ->on("envxp.codautogen", "=", "da.Codautogen");
                    })
                    ->join("motivos_envios as moti", "envxp.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
                    ->leftJoin("atc_cab as cab", "envxp.iCodAutogenAudi_ult", "=", "cab.iCodAutogenAtc")
                    ->where("gi.dtmguia", ">=", "2017-01-01")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("da.IdeDestinatario as codcn", "gi.CiCloCorteFactuCliente as ciclo", "moti.DesMotivoEnvio as situacion",
                        "da.CodAutogen as agn", "da.NroProceso as npr", "da.NroControl as nct");
                $data = $busqueda->get();
                $nRows = $busqueda->count();
                $pages = floor($nRows / 5) + ($nRows % 5 == 0 ? 0 : 1);
                return Response::json([
                    "success" => true,
                    "data" => $data,
                    "pages" => $pages
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

    public function send_email() {
        ini_set('max_execution_time', 180);
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno,$grn,$str,$mail,$msn)) {
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $gerencias = isset($grn) ? $grn : [];
                $sectores = isset($str) ? $str : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $result = DB::table("guias_ingreso as gi")
                    ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                    ->join("envios_x_proceso as envxp", function($join) {
                        $join->on("envxp.nroproceso", "=", "da.Nroproceso")
                            ->on("envxp.NroControl", "=", "da.NroControl")
                            ->on("envxp.codautogen", "=", "da.Codautogen");
                    })
                    ->join("motivos_envios as moti", "envxp.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
                    ->where("gi.dtmguia", ">=", "2017-01-01")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.NroDocuCliente", $gerencias)
                    ->whereIn("da.Sector", $sectores)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("da.IdeDestinatario as consultora", "gi.CiCloCorteFactuCliente as ciclo", "moti.DesMotivoEnvio as situacion", DB::raw("if(envxp.iCodAutogenAudi_ult > 0,'Si','No') as auditoria"),DB::raw("if(envxp.iCodAutogenAtc_ult = 0,'No','Si') as ereclamo"))
                    ->get();
                $filename = "rep_visitas_" . date("Ymd_His");
                Excel::create($filename, function($excel) use($result, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                    $excel->sheet("Data", function($sheet) use($result, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                        $sheet->loadView("xls.xls_revistas", [
                            "filas" => $result,
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