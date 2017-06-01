
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
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->v_Nombres . " " . $user->v_Apellidos }}<br>{{ $user->v_PerClienteAgrupa1 }} <span class="caret"></span></a>
						        <ul class="dropdown-menu">
						            <li><a href="{{ url('perfil') }}">Mi perfil</a></li>
						            <li><a href="{{ url('logout') }}">Cerrar Sesión</a></li>
						       	</ul>
							</div>
						</div>
        			</div>
                    <button type="button" class="drawer-toggle drawer-hamburger">
                        <span class="sr-only">toggle navigation</span>
                        <span class="drawer-hamburger-icon"></span>
                    </button>
        		</div>
        		<div class="fluid-list nav-header">
        			<ul class="reset-ul">
        				<li><a href="{{ url('/') }}" class="{{ $idx == 0 ? 'active' : '' }}">Inicio</a></li>
        				<li class="dropdown">
					        <a href="#" class="dropdown-toggle {{ $idx == 4 ? 'active' : '' }}" data-toggle="dropdown">Mis indicadores</a>
					        <ul class="dropdown-menu">
					          	<li><a href="{{ url('indicadores/entrega') }}">Efectividad de entrega</a></li>
					          	<li><a href="{{ url('indicadores/reclamos') }}">Gestión de reclamos</a></li>
					        </ul>
					    </li>
        			</ul>
        		</div>
                <nav class="drawer-nav" role="navigation">
                    <div>
                        <div class="fluid-list cnt-menu-user">
                            <div class="user-nav">
                                <span class="center"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <div class="user-list navbar-right">
                                    <span>Bienvenid@</span>
                                    <h4>{{ $user->v_Nombres . " " . $user->v_Apellidos }}</h4>
                                </div>
                            </div>
                            <div class="jr-d">
                                <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                <div class="sector-n">
                                    <span>Sector: {{ $user->v_PerClienteAgrupa1 }}</span>
                                    <p>Nombre del Sector: {{ $user->v_PerClienteAgrupa2 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="fluid-list cnt-list-menu-d">
                            <ul class="reset-ul">
                                <li>
                                    <a href="{{ url('/') }}" class="{{ $idx == 0 ? 'active' : '' }}">Inicio</a>
                                </li>
                                <li>
                                    <a href="{{ url('perfil') }}">Mi perfil</a>
                                </li>
                                <li>
                                    <a href="#">Mis indicadores</a>
                                    <ul class="reset-ul">
                                        <li><a href="{{ url('indicadores/entrega') }}">Efectividad de entrega</a></li>
                                        <li><a href="{{ url('indicadores/reclamos') }}">Gestión de reclamos</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ url('usuarios') }}" class="{{ $idx == 5 ? 'active' : '' }}">Administración de Usuarios</a>
                                </li>
                                <li>
                                    <a href="{{ url('logout') }}">Cerrar sesión</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
        	</header>