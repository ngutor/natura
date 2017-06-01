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
        $user = Auth::user();
        $arrData = ["user" => $user, "idx" => 0];
        return view($user->tp_cliente . ".index")->with($arrData);
    }

    public function revistas() {
        $user = Auth::user();
        $ciclos = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "gi.codareaclie", "=", "ac.codareacliente")
            ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
            ->where("ac.codcliente", $user->i_CodCliente)
            ->where("gi.dtmguia", ">=", "2017-01-01")
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->where("gi.CiCloCorteFactuCliente", "<>", "")
            ->select("gi.CiCloCorteFactuCliente as value", DB::raw("concat('Ciclo ',gi.CiCloCorteFactuCliente) as text"))
            ->distinct()
            ->get();
        $arr_ciclos = [];
        foreach ($ciclos as $ciclo) {
            $arr_ciclos[] = $ciclo->value;
        }
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.NroDocuCliente as value", DB::raw("concat('Gerencia ', da.NroDocuCliente) as text"))
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.Sector as value", DB::raw("concat('Sector ', da.Sector) as text"))
            ->distinct()
            ->get();
        $cno = DB::table("guias_ing_procesos as gip")
            ->join("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                    ->on("da.NroProceso", "=", "gip.NroProceso");
            })
            ->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->whereRaw("gi.DtmGuia >= '2017-01-01'")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->where("gip.FlgIngresosReclamo", "N")
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.GrupoCliente as value", "da.GrupoCliente as text")
            ->distinct()
            ->get();
        $tpreclamo = DB::table("atc_motivos_gestion")
            ->where("cTipoGestionAtc", "R")
            ->select("iMotivoGestionAtc as value","VDesMotivo as text")
            ->orderBy("iOrdenLista","asc")
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 1,
            "ciclos" => $ciclos,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno,
            "tipos" => $tpreclamo
        ];
        if(strcmp($user->tp_cliente, "clientes") == 0) {
            $consultoras = $busqueda = DB::table("guias_ingreso as gi")
                ->join("datos_adicionales as da", "gi.codautogen", "=", "da.codautogen")
                ->where("da.IdeDestinatario", "<>", "")
                ->whereRaw("da.IdeDestinatario is not null")
                ->select("da.IdeDestinatario as value")
                ->orderBy("da.IdeDestinatario", "asc")
                ->distinct()
                ->get();
            $arrData["cons"] = $consultoras;
        }
        return view($user->tp_cliente . ".revistas")->with($arrData);
    }

    public function auditoria() {
        $user = Auth::user();
        $ciclos = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "gi.codareaclie", "=", "ac.codareacliente")
            ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
            ->where("ac.codcliente", $user->i_CodCliente)
            ->where("gi.dtmguia", ">=", "2017-01-01")
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->where("gi.CiCloCorteFactuCliente", "<>", "")
            ->select("gi.CiCloCorteFactuCliente as value", DB::raw("concat('Ciclo ',gi.CiCloCorteFactuCliente) as text"))
            ->distinct()
            ->get();
        $arr_ciclos = [];
        foreach ($ciclos as $ciclo) {
            $arr_ciclos[] = $ciclo->value;
        }
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.NroDocuCliente as value", DB::raw("concat('Gerencia ', da.NroDocuCliente) as text"))
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.Sector as value", DB::raw("concat('Sector ', da.Sector) as text"))
            ->distinct()
            ->get();
        $cno = DB::table("guias_ing_procesos as gip")
            ->join("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                    ->on("da.NroProceso", "=", "gip.NroProceso");
            })
            ->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->whereRaw("gi.DtmGuia >= '2017-01-01'")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->where("gip.FlgIngresosReclamo", "N")
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.GrupoCliente as value", "da.GrupoCliente as text")
            ->distinct()
            ->get();
        $tpreclamo = DB::table("atc_motivos_gestion")
            ->where("cTipoGestionAtc", "R")
            ->select("iMotivoGestionAtc as value","VDesMotivo as text")
            ->orderBy("iOrdenLista","asc")
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 2,
            "ciclos" => $ciclos,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno,
            "tipos" => $tpreclamo
        ];
        return view($user->tp_cliente . ".auditoria")->with($arrData);
    }

    public function reclamos() {
        $user = Auth::user();
        $estados = DB::table("atc_estados_gestion")
            ->where("cTipoGestionAtc", "R")
            ->select("vConclusion as value","vConclusion as text")
            ->orderBy("iOrdenLista", "asc")
            ->distinct()
            ->get();
        $ciclos = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "gi.codareaclie", "=", "ac.codareacliente")
            ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
            ->where("ac.codcliente", $user->i_CodCliente)
            ->where("gi.dtmguia", ">=", "2017-01-01")
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->where("gi.CiCloCorteFactuCliente", "<>", "")
            ->select("gi.CiCloCorteFactuCliente as value", DB::raw("concat('Ciclo ',gi.CiCloCorteFactuCliente) as text"))
            ->distinct()
            ->get();
        $arr_ciclos = [];
        foreach ($ciclos as $ciclo) {
            $arr_ciclos[] = $ciclo->value;
        }
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.NroDocuCliente as value", DB::raw("concat('Gerencia ', da.NroDocuCliente) as text"))
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.Sector as value", DB::raw("concat('Sector ', da.Sector) as text"))
            ->distinct()
            ->get();
        $cno = DB::table("guias_ing_procesos as gip")
            ->join("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                    ->on("da.NroProceso", "=", "gip.NroProceso");
            })
            ->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->whereRaw("gi.DtmGuia >= '2017-01-01'")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->where("gip.FlgIngresosReclamo", "N")
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.GrupoCliente as value", "da.GrupoCliente as text")
            ->distinct()
            ->get();
        $tipos = DB::table("atc_motivos_gestion")
            ->where("cTipoGestionAtc","R")
            ->select("iMotivoGestionAtc as value","VDesMotivo as text")
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 3,
            "estados" => $estados,
            "ciclos" => $ciclos,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno,
            "tipos" => $tipos
        ];
        return view($user->tp_cliente . ".reclamos")->with($arrData);
    }

    public function indicadores_entrega() {
        $user = Auth::user();
        $ciclos = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "gi.codareaclie", "=", "ac.codareacliente")
            ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
            ->where("ac.codcliente", $user->i_CodCliente)
            ->where("gi.dtmguia", ">=", "2017-01-01")
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->where("gi.CiCloCorteFactuCliente", "<>", "")
            ->select("gi.CiCloCorteFactuCliente as value", DB::raw("concat('Ciclo ',gi.CiCloCorteFactuCliente) as text"))
            ->distinct()
            ->get();
        $arr_ciclos = [];
        foreach ($ciclos as $ciclo) {
            $arr_ciclos[] = $ciclo->value;
        }
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.NroDocuCliente as value", DB::raw("concat('Gerencia ', da.NroDocuCliente) as text"))
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.Sector as value", DB::raw("concat('Sector ', da.Sector) as text"))
            ->distinct()
            ->get();
        $cno = DB::table("guias_ing_procesos as gip")
            ->join("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                    ->on("da.NroProceso", "=", "gip.NroProceso");
            })
            ->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->whereRaw("gi.DtmGuia >= '2017-01-01'")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->where("gip.FlgIngresosReclamo", "N")
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.GrupoCliente as value", "da.GrupoCliente as text")
            ->distinct()
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 4,
            "ciclos" => $ciclos,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno
        ];
        return view($user->tp_cliente . ".indicadores-entrega")->with($arrData);
    }

    public function indicadores_reclamos() {
        $user = Auth::user();
        $estados = DB::table("atc_estados_gestion")
            ->where("cTipoGestionAtc", "R")
            ->select("vConclusion as value","vConclusion as text")
            ->orderBy("iOrdenLista", "asc")
            ->distinct()
            ->get();
        $estadosf = DB::table("atc_estados_gestion")
            ->where("cTipoGestionAtc", "R")
            ->select("vEstado as value","vEstado as text")
            ->distinct()
            ->get();
        $ciclos = DB::table("guias_ingreso as gi")
            ->join("areas_clientes as ac", "gi.codareaclie", "=", "ac.codareacliente")
            ->join("guias_ing_procesos as gip", "gip.codautogen", "=", "gi.codautogen")
            ->where("ac.codcliente", $user->i_CodCliente)
            ->where("gi.dtmguia", ">=", "2017-01-01")
            ->whereRaw("gi.CiCloCorteFactuCliente is not null")
            ->where("gi.CiCloCorteFactuCliente", "<>", "")
            ->select("gi.CiCloCorteFactuCliente as value", DB::raw("concat('Ciclo ',gi.CiCloCorteFactuCliente) as text"))
            ->distinct()
            ->get();
        $arr_ciclos = [];
        foreach ($ciclos as $ciclo) {
            $arr_ciclos[] = $ciclo->value;
        }
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.NroDocuCliente as value", DB::raw("concat('Gerencia ', da.NroDocuCliente) as text"))
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
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.Sector as value", DB::raw("concat('Sector ', da.Sector) as text"))
            ->distinct()
            ->get();
        $cno = DB::table("guias_ing_procesos as gip")
            ->join("datos_adicionales as da", function($join_da) {
                $join_da->on("da.CodAutogen", "=", "gip.CodAutogen")
                    ->on("da.NroProceso", "=", "gip.NroProceso");
            })
            ->join("guias_ingreso as gi", "gip.CodAutogen", "=", "gi.CodAutogen")
            ->join("areas_clientes as ac", "ac.CodAreaCliente", "=", "gi.CodAreaClie")
            ->whereRaw("gi.DtmGuia >= '2017-01-01'")
            ->where("ac.CodCliente", $user->i_CodCliente)
            ->where("gip.FlgIngresosReclamo", "N")
            ->whereIn("gi.CiCloCorteFactuCliente", $arr_ciclos)
            ->select("da.GrupoCliente as value", "da.GrupoCliente as text")
            ->distinct()
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 4,
            "ciclos" => $ciclos,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno,
            "estados" => $estados,
            "estadosf" => $estadosf
        ];
        return view($user->tp_cliente . ".indicadores-reclamo")->with($arrData);
    }

    public function usuarios() {
        $user = Auth::user();
        $gerencias = DB::table("clientes_tablas")
            ->where("icodcliente", $user->i_CodCliente)
            ->where("vcodtablacliente","GR")
            ->select("vCodDato as value",DB::raw("concat('Gerencia ',vCodDato) as text"))
            ->get();
        $sectores = DB::table("clientes_tablas")
            ->where("icodcliente", $user->i_CodCliente)
            ->where("vcodtablacliente","SEC")
            ->select("vCodDato as value",DB::raw("concat('Sector ',vCodDato) as text"))
            ->get();
        $cno = DB::table("clientes_tablas")
            ->where("icodcliente", $user->i_CodCliente)
            ->where("vcodtablacliente","CNO")
            ->select("vCodDato as value","vCodDato as text")
            ->get();
        $perfiles = DB::table("seg_perfiles")
            ->where("v_CodEstado", "Vigente")
            ->select("i_CodTipoPerfil as value", "v_NombrePerfil as text")
            ->orderBy("i_CodTipoPerfil","desc")
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 5,
            "gerencias" => $gerencias,
            "sectores" => $sectores,
            "cnos" => $cno,
            "perfiles" => $perfiles
        ];
        return view($user->tp_cliente . ".usuarios")->with($arrData);
    }

    public function perfil() {
        $user = Auth::user();
        $perfiles = DB::table("seg_perfiles")
            ->where("v_CodEstado", "Vigente")
            ->select("i_CodTipoPerfil as value","v_NombrePerfil as text")
            ->get();
        $arrData = [
            "user" => $user,
            "idx" => 6,
            "perfiles" => $perfiles,
            "message" => \Session::get("message")
        ];
        return view($user->tp_cliente . ".perfil")->with($arrData);
    }

}