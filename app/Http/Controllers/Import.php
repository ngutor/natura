<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Excel;
use Request;
use Response;
use App\User as User;

class Import extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        $this->middleware("auth");
    }
    //storage_path("exports") . DIRECTORY_SEPARATOR . $filename . ".xls"

    public function sube_reclamo() {
        $user = Auth::user();
    	$file = Request::file("file-0");
    	if($file->isValid()) {
    		//mover a la carpeta de excel
    		$filename = str_replace(" ", "", $file->getClientOriginalName());
            $motivo = DB::table("atc_motivos_gestion")->where("iMotivoGestionAtc","101")->select("vDesMotivo as motivo")->first();
    		$file->move(storage_path("exports"), $filename);
    		//leer con excel
    		Excel::load(storage_path("exports") . DIRECTORY_SEPARATOR . $filename, function($reader) use($user, $motivo) {
			    $reader->each(function($sheet) use($user, $motivo) {
                    $output = [];
                    foreach ($sheet->toArray() as $row) {
                        $output[] = [
                            "iCodContacto" => $user->i_CodContacto,
                            "fRegistro" => date("Y-m-d"),
                            "vUsuRegistra" => $user->v_Codusuario,
                            "cTipoGestionAtc" => "R",
                            "iMotivoGestionAtc" => 101,
                            "vEstado" => "PENDIENTE",
                            "iCodConclusion" => 5,
                            "vAsunto" => $motivo->motivo,
                            "vDescripcion" => $motivo->motivo,
                            "cFlgEnviado" => "N",
                            "IdeDestinatario" => $row["cod_cn"],
                            "NroTelefNuevo" => $row["telefono"]
                        ];
                    }
                    if(count($output) > 0) DB::table("atc_cab")->insert($output);
                });
			});
			//finish him!
    		return Response::json([
    			"success" => true
			]);
    	}
    	else return Response::json([
    		"success" => false,
    		"message" => "Ocurrió un problema al subir el archivo. Intente nuevamente o comuníquese con el administrador"
		]);
    }

}