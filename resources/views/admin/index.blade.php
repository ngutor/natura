<!DOCTYPE html>
<html lang="es">
<head>
    <title>Index - GV</title>
    @include("common.head")
</head>
<body class="drawer drawer--right">
    <div id="main">
        <div class="container">
            @include($user->tp_cliente . '.header')
        	<div class="fluid-list cnt-container">
        		<div class="info-t">
        			<h2>Bienvenido a nuestro Sitio Web</h2>
	        		<p>En nombre de Union Star EIRL, nos complace saludarlo y darle la más cordial bienvenida a nuestra página web, en la cual esperamos que encuentre toda la información que busca acerca de nosotros.</p>
	        		<p>El principal objetivo de este medio es darle a conocer lo que somos y hacemos. Ud podrá encontrar aquí de manera directa, una amplia descripción de nuestros servicios, así como el alcance de los mismos. En esta página también podrá contactarse con nosotros y hacernos llegar sus consultas y comentarios, asegurando su atención de manera directa y oportuna.</p>
	        		<p>Para lograr la satisfacción de nuestros clientes, la calidad de nuestros servicios es prioridad número uno, es por ello que nos esforzamos en asegurarte:</p>
	        		<ul class="reset-ul">
	        			<li><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Rapidez</li>
	        			<li><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Seguridad</li>
	        			<li><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Puntualidad</li>
	        			<li><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Economía</li>
	        		</ul>
        		</div>
        		<div class="fluid-list cnt-indicadores">
        			<ul class="reset-ul">
                        <li>
                            <div class="list-indicador l1">
                                <span></span>
                                <div class="text-f">
                                    <span>Mis Revistas</span>
                                    <p>Listado de repartos, fechas de entrega</p>
                                </div>
                            </div>
                        </li>
        				<li>
        					<div class="list-indicador l2">
        						<span></span>
        						<div class="text-f">
        							<span>Mis Indicadores</span>
        							<p>Porcentajes de entregas cumplidas</p>
        						</div>
        					</div>
        				</li>
        			</ul>
        		</div>
        		<div class="fluid-list r-digital">
                @if(strcmp($user->tp_cliente,"admin") == 0)
                    <input type="button" id="bt-cambia-img" value="Cambiar" />
                    <style type="text/css">
                        #bt-cambia-img{position:absolute;margin-top:5px;margin-left:5px;}
                    </style>
        			<!--Modal de Observaciones-->
                    <div class="modal fade modal-custom-n" id="tModalBanner" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form class="form-horizontal m-form-n" action="{{ url('ajax/upd_banner') }}" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <h2>Cambiar banner</h2>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <p><b>ATENCION: </b>Una vez que cambie el banner, el anterior será borrado.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label for="tRecla" class="col-sm-2 control-label">Archivo:</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" class="form-control" name="banner" />
                                                    </div>
                                                </div>
                                                <div class="row"><br/></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-mC btn-grabar" id="btn-grabar">GRABAR</button>
                                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End-->
                @endif
                    <img src="{{ asset('asset/img/r-digital.jpg') }}?t={{ rand() }}">
        		</div>
        	</div>
        </div>
    </div>
    @include("common.scripts")
    <script type="text/javascript">
        $("#bt-cambia-img").on("click", function() {
            $("#tModalBanner").modal("show");
        });
    </script>
</body>
</html>
