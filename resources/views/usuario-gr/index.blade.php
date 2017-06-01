<!DOCTYPE html>
<html lang="es">
<head>
    <title>Bienvenido</title>
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
        			<img src="{{ asset('asset/img/r-digital.jpg') }}">
        		</div>
        	</div>
        </div>
    </div>
    @include("common.scripts")
</body>
</html>
