
        	<header class="header fluid-list">
        		<div class="fluid-list top-header">
        			<div class="left logo-header">
        				<a href="http://natura.com.pe" target="_blank"><img src="{{ asset('asset/img/logo-natura.png') }}"></a>
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
        				<li><a href="{{ url('/') }}" class="{{ $idx == 0 ? 'active' : '' }}">Inicio</a></li>
                        <li><a href="{{ url('revistas') }}" class="{{ $idx == 1 ? 'active' : '' }}">Mis revistas</a></li>
                        <li><a href="#" class="{{ $idx == 2 ? 'active' : '' }}">Auditoría</a></li>
                        <li><a href="#" class="{{ $idx == 3 ? 'active' : '' }}">Reclamos</a></li>
        				<li class="dropdown">
					        <a href="#" class="dropdown-toggle {{ $idx == 4 ? 'active' : '' }}" data-toggle="dropdown">Mis indicadores</a>
					        <ul class="dropdown-menu">
					          	<li><a href="{{ url('indicadores/entrega') }}">Efectividad de entrega</a></li>
					          	<li><a href="{{ url('indicadores/reclamos') }}">Gestión de reclamos</a></li>
					        </ul>
					    </li>
                        <li><a href="#" class="{{ $idx == 5 ? 'active' : '' }}">Administración de Usuarios</a></li>
        			</ul>
        		</div>
        	</header>