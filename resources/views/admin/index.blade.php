<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index - GV</title>
    <!-- Bootstrap -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,700i" rel="stylesheet">
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="main">
        <div class="container">
        	<header class="header fluid-list">
        		<div class="fluid-list top-header">
        			<div class="left logo-header">
        				<a href="#"><img src="{{ asset('asset/img/logo-natura.png') }}"></a>
        			</div>
        			<div class="right user-nav">
        				<span class="center"><i class="fa fa-user" aria-hidden="true"></i></span>
						<div class="user-list navbar-right">
							<span>Bienvenid@</span>
							<div class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->des_nombres . " " . $user->des_apellidos }}<span class="caret"></span></a>
						        <ul class="dropdown-menu">
						            <li><a href="{{ url('perfil') }}">Mi perfil</a></li>
						            <li><a href="{{ url('logout') }}">Cerrar Sesión</a></li>
						       	</ul>
							</div>
						</div>
        			</div>
        		</div>
        		<div class="fluid-list nav-header">
        			<ul class="reset-ul">
        				<li><a href="#" class="active">Inicio</a></li>
                        <li><a href="#">Mis revistas</a></li>
                        <li><a href="#">Auditoría</a></li>
                        <li><a href="#">Reclamos</a></li>
        				<li class="dropdown">
					        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mis indicadores</a>
					        <ul class="dropdown-menu">
					          	<li><a href="#">Efectividad de entrega</a></li>
					          	<li><a href="#">Gestión de reclamos</a></li>
					        </ul>
					    </li>
                        <li><a href="#">Administración de Usuarios</a></li>
        			</ul>
        		</div>
        	</header>
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
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/main.js?v1') }}"></script>
</body>
</html>
