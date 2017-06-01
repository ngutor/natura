<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use Session;
use App\User as User;

class Auditoria extends Controller {
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
                $busqueda = DB::table("atc_cab as atc")
                    ->leftJoin("seg_user as usr", "atc.vUsuRegistra", "=", "usr.v_Codusuario")
                    ->leftJoin("guias_ingreso as gi", "gi.CodAutogen", "=", "atc.CodAutogen")
                    ->leftJoin("envios_x_proceso as envxp", function($join_exp) {
                        $join_exp->on("envxp.CodAutogen", "=", "atc.CodAutogen")
                            ->on("envxp.NroProceso", "=", "atc.NroProceso")
                            ->on("envxp.NroControl", "=", "atc.NroControl");
                    })
                    ->leftJoin("datos_adicionales as da", function($join_da) {
                        $join_da->on("envxp.CodAutogen", "=", "da.CodAutogen")
                            ->on("envxp.NroProceso", "=", "da.NroProceso")
                            ->on("envxp.NroControl", "=", "da.NroControl");
                    })
                    ->leftJoin("atc_estados_gestion as ate", function($join_ate) {
                        $join_ate->on("ate.cTipoGestionAtc", "=", "atc.cTipoGestionAtc")
                            ->on("ate.iCodConclusion", "=", "atc.iCodConclusion");
                    })
                    ->where("atc.cTipoGestionAtc", "A")
                    //->whereRaw("atc.fEnvio")
                    ->whereIn("gi.CiCloCorteFactuCliente", $ciclos)
                    ->whereIn("da.GrupoCliente", $cnos)
                    ->whereIn("da.NroDocuCliente", $gerencias)
                    ->whereIn("da.Sector", $sectores)
                    ->whereRaw("da.IdeDestinatario like '" . $codigo . "'")
                    ->select("gi.CiCloCorteFactuCliente as ciclo", "da.IdeDestinatario as consultora", "atc.fEnvio as marca",
                        "ate.vConclusion as estado", "ate.vestado as subestado", "da.CodAutogen as agn", "da.NroProceso as npr",
                        "da.NroControl as nct", "envxp.iCodAutogenAudi_ult as ulti");
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

    public function ls_datos() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($agn,$npr,$ncn)) {
                $info = DB::table("atc_cab as cab")
                    ->leftJoin("atc_estados_gestion as aeg", "cab.iCodConclusion", "=", "aeg.iCodConclusion")
                    ->leftJoin("atc_motivos_gestion as amg", "amg.iMotivoGestionAtc", "=", "cab.iMotivoGestionAtc")
                    ->where("cab.CodAutogen", $agn)
                    ->where("cab.NroProceso", $npr)
                    ->where("cab.NroControl", $ncn)
                    ->where("cab.cTipoGestionAtc", "A")
                    ->select("aeg.vConclusion as estado", "cab.iCodConclusion as subestado", "cab.vAsunto as titulo",
                        "fEnvio as fecha", "cab.iMotivoGestionAtc as motivo", "cab.vDescripcion as descripcion");
                if($info->count() > 0) {
                    $info = $info->first();
                    $estados = DB::table("atc_estados_gestion")->where("cTipoGestionAtc", "A")->select("vConclusion as value", "vConclusion as text")->distinct()->get();
                    $motivos = DB::table("atc_motivos_gestion")->where("cTipoGestionAtc", "A")->select("iMotivoGestionAtc as value", "vDesMotivo as text")->get();
                    $subestados = DB::table("atc_estados_gestion")->where("cTipoGestionAtc", "A")->where("vConClusion",$info->estado)->select("iCodConclusion as value", "vEstado as text")->get();
                    return Response::json([
                        "success" => true,
                        "data" => $info,
                        "estados" => $estados,
                        "motivos" => $motivos,
                        "subest" => $subestados
                    ]);
                }
                else return Response::json([
                    "success" => false,
                    "message" => "No se encontró datos"
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

    public function cmb_subestados() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($std)) {
                $subestados = DB::table("atc_estados_gestion")
                    ->where("cTipoGestionAtc", "A")
                    ->where("vConclusion", $std)
                    ->select("iCodConclusion as value", "vestado as text")
                    ->get();
                return Response::json([
                    "success" => true,
                    "data" => $subestados
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

    public function upd_auditoria() {
        if(Request::ajax()) {
            extract(Request::input());
            if(isset($autogen,$subestado,$asunto,$motivo,$observaciones)) {
                DB::table("atc_cab")->where("iCodAutogenAtc", $autogen)->update([
                    "iCodConclusion" => $subestado,
                    "vAsunto" => $asunto,
                    "iMotivoGestionAtc" => $motivo,
                    "vDescripcion" => $observaciones

                ]);
                return Response::json([
                    "success" => true
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