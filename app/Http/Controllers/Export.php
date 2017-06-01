<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use Request;
use App\User as User;

class Export extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("auth");
        ini_set('max_execution_time', 180);
    }

    public function revistas() {
    	extract(Request::input());
        if(isset($ccl,$cno,$grn,$str)) {
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
                ->select("da.IdeDestinatario as consultora", "gi.CiCloCorteFactuCliente as ciclo", "moti.DesMotivoEnvio as situacion", DB::raw("if(envxp.iCodAutogenAudi_ult > 0,'Si','No') as auditoria"))
                ->get();
            Excel::create("Reporte", function($excel) use($result, $ciclos, $cnos, $gerencias, $sectores, $ccn) {
			    $excel->sheet("Data", function($sheet) use($result, $ciclos, $cnos, $gerencias, $sectores, $ccn) {
			        $sheet->loadView("xls.xls_revistas", [
			        	"filas" => $result,
			        	"ciclos" => $ciclos,
			        	"cnos" => $cnos,
			        	"gerencias" => $gerencias,
			        	"sectores" => $sectores,
			        	"codigo" => $ccn
			        ]);
			    });
			})->download("xls");
        }
        else return "Parámetros incorrectos";
    }

    public function auditoria() {
        extract(Request::input());
        if(isset($ccl,$cno,$grn,$str)) {
            $ciclos = isset($ccl) ? $ccl : [];
            $cnos = isset($cno) ? $cno : [];
            $gerencias = isset($grn) ? $grn : [];
            $sectores = isset($str) ? $str : [];
            $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
            $result = DB::table("atc_cab as atc")
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
                ->select("gi.CiCloCorteFactuCliente as ciclo", "da.IdeDestinatario as consultora", DB::raw("ifnull(atc.fEnvio,'-') as marca"),
                    "ate.vConclusion as estado", "ate.vestado as subestado", "da.CodAutogen as agn", "da.NroProceso as npr",
                    "da.NroControl as nct")
                ->get();
            Excel::create("Reporte", function($excel) use($result, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                $excel->sheet("Data", function($sheet) use($result, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
                    $sheet->loadView("xls.xls_auditoria", [
                        "filas" => $result,
                        "ciclos" => $ciclos,
                        "cnos" => $cnos,
                        "gerencias" => $gerencias,
                        "sectores" => $sectores,
                        "codigo" => $codigo
                    ]);
                });
            })->download("xls");
        }
        else return "Parámetros incorrectos";
        //else return Request::input();
    }

    public function reclamos() {
    	extract(Request::input());
        if(isset($est,$ccl,$cno,$grn,$str)) {
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
            Excel::create("Reporte", function($excel) use($result, $estados, $ciclos, $cnos, $gerencias, $sectores, $codigo) {
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
			})->download("xls");
        }
        else return "Parámetros incorrectos";
    }

    public function usuarios() {
        extract(Request::input());
        if(isset($prf,$cno,$grn,$str)) {
            $perfiles = isset($prf) ? $prf : [];
            $cnos = isset($cno) ? $cno : [];
            $gerencias = isset($grn) ? $grn : [];
            $sectores = isset($str) ? $str : [];
            $codigo = isset($ccn) ? "%" . $ccn . "%" : "%%";
            $result = DB::table("seg_user as su")
                ->join("seg_perfiles as sp", "sp.i_CodTipoPerfil", "=", "su.i_CodTipoPerfil")
                ->whereIn("su.v_PerClienteAgrupa1", $gerencias)
                ->whereIn("su.v_PerClienteAgrupa2", $sectores)
                ->whereIn("su.v_PerClienteAgrupa3", $cnos)
                ->whereIn("su.i_CodTipoPerfil", $perfiles)
                ->whereRaw("su.v_IdPerCliente like '" . $codigo . "'")
                ->select("su.v_IdPerCliente as codigo", DB::raw("concat(su.v_Nombres,' ',su.v_Apellidos) as nombre"), "sp.v_NombrePerfil as perfil", "sp.v_CodEstado as estado")
                ->get();
            $arr_perfiles = DB::table("seg_perfiles")
                ->whereIn("i_CodTipoPerfil", $perfiles)
                ->select("v_NombrePerfil as nombre")
                ->get();
            $ls_perfiles = [];
            foreach ($arr_perfiles as $iperfil) {
                $ls_perfiles[] = $iperfil->nombre;
            }
            Excel::create("Reporte", function($excel) use($result, $ls_perfiles, $cnos, $gerencias, $sectores, $codigo) {
                $excel->sheet("Data", function($sheet) use($result, $ls_perfiles, $cnos, $gerencias, $sectores, $codigo) {
                    $sheet->loadView("xls.xls_usuarios", [
                        "filas" => $result,
                        "perfiles" => $ls_perfiles,
                        "cnos" => $cnos,
                        "gerencias" => $gerencias,
                        "sectores" => $sectores,
                        "codigo" => $codigo
                    ]);
                });
            })->download("xls");
        }
        else return "Parámetros incorrectos";
    }

    function descarga_reclamos() {
        $user = Auth::user();
        $ciclo = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->where("gi.DtmGuia", ">=", "2017-01-01")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->max("gi.CiCloCorteFactuCliente");
        $data = DB::table("guias_ingreso as gi")
            ->join("atc_cab as atc", "gi.CodAutogen", "=", "atc.CodAutogen")
            ->join("atc_estados_gestion as atc_estados", "atc_estados.iCodConclusion", "=", "atc.iCodConclusion")
            ->leftJoin("envios_x_proceso as exp", function($join_exp) {
                $join_exp->on("exp.CodAutogen", "=", "atc.CodAutogen")
                    ->on("exp.NroProceso", "=", "atc.NroProceso")
                    ->on("exp.NroControl", "=", "atc.NroControl");
            })
            ->leftJoin("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "exp.CodAutogen")
                    ->on("da.NroProceso", "=", "exp.NroProceso")
                    ->on("da.NroControl", "=", "exp.NroControl");
            })
            ->join("direcciones as dire", "dire.CodDestinatario", "=", "exp.CodDestinatario")
            ->where("gi.CiCloCorteFactuCliente", $ciclo)
            ->where("atc.cTipoGestionAtc", "R")
            ->select(DB::raw("atc.fRegistro as fecha_reclamo"),"da.IdeDestinatario as cod_cn","dire.NomDestinatario as nom_cn",
                "da.Sector as sector","da.NroDocuCliente as gerencia","da.NroTelefDesti as telefonos",
                "dire.Departamento as departamento","dire.Provincia as provincia","dire.Ciudad as ciudad",
                "dire.DirDestinatario as direccion","da.SituacionCliente as inactividad","da.GrupoCliente as grupo",
                "atc.vObsLLamadaTelef as estado_llamada","atc_estados.vEstado as subestado","atc_estados.vConclusion as estado",
                "atc.vDetMotivoNV as detalle","atc.dNuevaVisita as fec_recepcion","atc.vRecibidoPorNV as nom_entrega",
                "atc.vDniNV as dni_entrega","atc.vParentescoNV as parentesco_entrega","atc.vcaracteristicasNV as caracteristicas")
            ->get();
        Excel::create("Reporte", function($excel) use($ciclo, $data) {
            $excel->sheet("Data", function($sheet) use($ciclo, $data) {
                $sheet->loadView("xls.xls_descargo", [
                    "ciclo" => $ciclo,
                    "data" => $data
                ]);
            });
        })->download("xls");
    }

    public function reclamo_individual() {
        $user = Auth::user();
        extract(Request::input());
        $binfo = DB::table("datos_adicionales as da")
            ->join("envios_x_proceso as envxp", function($join) {
                $join->on("envxp.codautogen", "=", "da.Codautogen")
                    ->on("envxp.nroproceso", "=", "da.Nroproceso")
                    ->on("envxp.NroControl", "=", "da.NroControl");
            })
            ->join("direcciones as direc","envxp.CodDestinatario", "=", "direc.CodDestinatario")
            ->join("vcode as vcd", "vcd.CodVcodAuto", "=", "direc.CodVcodAuto")
            ->where("envxp.codautogen", $rautogen)
            ->where("envxp.Nroproceso", $rnroproceso)
            ->where("envxp.NroControl", $rnrocontrol)
            ->select("direc.nomdestinatario as nombre", "direc.dirdestinatario as direccion", "da.nrotelefdesti as telefono", "da.situacioncliente as situac", "vcd.abrvcode as distrito", DB::raw("ifnull(envxp.FlgCargoPendiente,'S') as flag"))
            ->first();
        $tracking = DB::table("envios_x_proceso_seg as seg")
            ->join("motivos_envios as moti", "seg.CodMotivoEnvio", "=", "moti.CodMotivoEnvio")
            ->join("pasos_procesos as pasos", "pasos.CodPaso", "=", "seg.CodPaso")
            ->where("seg.codautogen", $rautogen)
            ->where("seg.Nroproceso", $rnroproceso)
            ->where("seg.NroControl", $rnrocontrol)
            ->select(DB::raw("date_format(seg.DtmPaso,'%Y-%m-%d %H:%i') as fecha"), "pasos.DesPaso as estado", DB::raw("ifnull(seg.ObsResultado,'(ninguna)') as observ"))
            ->get();
        Excel::create("Reclamo", function($excel) use($ciclo, $fecha, $eini, $efin, $binfo, $tracking) {
            $excel->sheet("Data", function($sheet) use($ciclo, $fecha, $eini, $efin, $binfo, $tracking) {
                $sheet->loadView("xls.xls_reclamo_individual", [
                    "ciclo" => $ciclo,
                    "fecha" => $fecha,
                    "eini" => $eini,
                    "efin" => $efin,
                    "binfo" => $binfo,
                    "tracking" => $tracking
                ]);
            });
        })->download("xls");
    }

}