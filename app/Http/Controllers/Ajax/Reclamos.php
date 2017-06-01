<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use Session;
use App\User as User;

class Reclamos extends Controller {
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
            if(isset($ccl,$cno,$grn,$str)) {
                $estados = isset($est) ? $est : [];
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $gerencias = isset($grn) ? $grn : [];
                $sectores = isset($str) ? $str : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $busqueda = DB::table("atc_cab as cab")
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
                    ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
                    ->whereRaw("gi.dtmguia >= '2017-01-01'")
                    ->where("gip.FlgIngresosReclamo", "N")
                    ->where("cab.cTipoGestionAtc", "R")
                    ->whereIn("ast.vConclusion", $estados)
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.NroDocuCliente", $gerencias)
                    ->whereIn("da.Sector", $sectores)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("gi.CiCloCorteFactuCliente as ciclo", "da.IdeDestinatario as consultora", "cab.fEnvio as fechareclamo", DB::raw("CONCAT(usr.v_Apellidos,' ', usr.v_Nombres) as usuario"), "iNroDiasGestion as dresol", "ast.vestado as estainicial", "ast.vConclusion as estafinal", "da.CodAutogen as agn", "da.NroProceso as npr", "da.NroControl as nct", "cab.iCodAutogenAtc as autoatc");
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
                    ->select("direc.nomdestinatario as nombre", "direc.dirdestinatario as direccion", "da.nrotelefdesti as telefono", "da.situacioncliente as situac", "vcd.abrvcode as distrito", "envxp.FlgCargoPendiente as flag")
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

    public function dt_busqueda_usuariogr() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($ccl,$cno,$est)) {
                $estados = isset($est) ? $est : [];
                $ciclos = isset($ccl) ? $ccl : [];
                $cnos = isset($cno) ? $cno : [];
                $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
                $busqueda = DB::table("atc_cab as cab")
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
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("gi.CiCloCorteFactuCliente as ciclo", "da.IdeDestinatario as consultora", "cab.fEnvio as fechareclamo", DB::raw("CONCAT(usr.v_Apellidos,' ', usr.v_Nombres) as usuario"), "iNroDiasGestion as dresol", "ast.vestado as estainicial", "ast.vConclusion as estafinal", "da.CodAutogen as agn", "da.NroProceso as npr", "da.NroControl as nct");
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

    public function dt_reclamo() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($agn)) {
                $dato = DB::table("atc_cab as atc")
                    ->join("atc_motivos_gestion as atc_moti", "atc.iMotivoGestionAtc", "=", "atc_moti.iMotivoGestionAtc")
                    ->join("atc_estados_gestion as atc_esta", "atc_esta.iCodConclusion", "=", "atc.iCodConclusion")
                    ->where("atc.iCodAutogenAtc", $agn)
                    ->select("atc.iMotivoGestionAtc as tipo","atc.vAsunto as asunto","atc_esta.vConclusion as efinal",
                        "atc_esta.vEstado as einicial", "atc.vComentario as comentario", "atc.vDescripcion as descripcion");
                if($dato->count() > 0) return Response::json([
                    "success" => true,
                    "data" => $dato->first()
                ]);
                else return Response::json([
                    "success" => false,
                    "message" => "No se encontró datos para el reclamo"
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

    public function actualiza_reclamo() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($agn,$tpo,$asn,$com,$msg)) {
                DB::table("atc_cab")->where("iCodAutogenAtc", $agn)->update([
                    "iMotivoGestionAtc" => $tpo,
                    "vAsunto" => $asn,
                    "vComentario" => $com,
                    "vDescripcion" => $msg
                ]);
                return Response::json([
                    "success" => true,
                    "message" => "Reclamo actualizado!"
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