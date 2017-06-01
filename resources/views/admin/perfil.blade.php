<!DOCTYPE html>
<html lang="es">
<head>
    <title>Perfil del usuario</title>
    @include("common.head")
    <style type="text/css">
        #table-footer,#tableCN{display:none;}
    </style>
</head>
<body class="drawer drawer--right">
    <div id="main">
        <div class="container">
            @include($user->tp_cliente . ".header")
            <div class="fluid-list cnt-container">
                <div class="fluid-list bread-n">
                    <ul class="reset-ul">
                        <li>
                            <a href="#">
                                < Volver</a>
                        </li>
                    </ul>
                </div>
                <div class="fluid-list cnt-form-n">
                    <h2>Mi Perfil</h2>
                    <form class="form-horizontal cnt-form-p" action="{{ url('ajax/usuarios/actualiza') }}" method="post" id="form-datos">
                        <input type="hidden" name="codigo" value="{{ $user->v_Codusuario }}" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="cno" value="{{ $user->v_IdPerCliente }}" />
                        <div class="fluid-list block-n">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="nombre" class="col-md-5 col-sm-4 control-label">Nombres</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $user->v_Nombres }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 custom-space">
                                    <div class="form-group">
                                        <label for="apellidos" class="col-md-5 col-sm-4 control-label">Apellidos</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="text" class="form-control" name="apellidos" id="apellidos" value="{{ $user->v_Apellidos }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="dni" class="col-md-5 col-sm-4 control-label">Documento de identidad</label>
                                        <div class="col-md-7 col-sm-8">
                                            <div class="row custom-b">
                                                <div class="col-xs-4 col-sm-4">
                                                    <select id="tDni" name="tpdoc" class="form-control" disabled>
                                                        @if($user->c_TipoDocide == 1)
                                                        <option value="01" selected="true">DNI</option>
                                                        <option value="02">RUC</option>
                                                        @else
                                                        <option value="01">DNI</option>
                                                        <option value="02" selected="true">RUC</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-xs-8 col-sm-8">
                                                    <input type="text" class="form-control" name="nrdoc" id="dni" value="{{ $user->v_NroDocide }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 custom-space">
                                    <div class="form-group">
                                        <label for="telf" class="col-md-5 col-sm-4 control-label">Teléfono contacto</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="text" class="form-control" name="telf" id="telf" value="{{ $user->v_Telefonos }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-md-5 col-sm-4 control-label">Correo electrónico</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="email" class="form-control" name="email" id="email" value="{{ $user->v_Email }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fluid-list block-n">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="tPerfil" class="col-md-5 col-sm-4 control-label">Tipo de Perfil</label>
                                        <div class="col-md-7 col-sm-8">
                                            <select id="tPerfil" class="form-control" disabled>
                                            @foreach($perfiles as $perfil)
                                                @if(strcmp($perfil->value,$user->i_CodTipoPerfil) == 0)
                                                <option value="{{ $perfil->value }}" selected="true">{{ $perfil->text }}</option>
                                                @else
                                                <option value="{{ $perfil->value }}">{{ $perfil->text }}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 custom-space">
                                    <div class="form-group">
                                        <label for="cno" class="col-md-5 col-sm-4 control-label">Código CNO</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="text" class="form-control" id="cno" value="{{ $user->v_IdPerCliente }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fluid-list block-n block-b-n">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="pass" class="col-md-5 col-sm-4 control-label">Contraseña</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="password" class="form-control" name="pass" id="pass">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 custom-space">
                                    <div class="form-group">
                                        <label for="rpass" class="col-md-5 col-sm-4 control-label">Repetir Contraseña</label>
                                        <div class="col-md-7 col-sm-8">
                                            <input type="password" class="form-control" name="rpass" id="rpass">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fluid-list center">
                            <button id="bt-post" type="submit" class="btn btn-save-f">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- -->
    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Operación completada</h4>
                </div>
                <div class="modal-body">
                    <p>Las contraseñas ingresadas deben ser iguales.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    @include("common.scripts")
    @if(strcmp($message, "") != 0)
    <!-- -->
    <div class="modal fade" id="modal-success" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Operación completada</h4>
                </div>
                <div class="modal-body">
                    <p>{{ $message }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    <script type="text/javascript">
        $("#modal-success").modal("show");
    </script>
    @endif
    <script type="text/javascript">
        function btPostOnClick(event) {
            event.preventDefault();
            var nombre = document.getElementById("nombre").value;
            var apellidos = document.getElementById("apellidos").value;
            var telf = document.getElementById("telf").value;
            var email = document.getElementById("email").value;
            var pass = document.getElementById("pass").value;
            var rpass = document.getElementById("rpass").value;
            if(nombre != "" && apellidos != "" && telf != "" && email != "") {
                if(pass == rpass) document.getElementById("form-datos").submit();
                else $("#modal-error").modal("show");
            }
            $("#bt-post").attr("disabled","disabled").fadeOut(150);
        }
        function init() {
            $("#bt-post").on("click", btPostOnClick);
        }
        $(init);
    </script>
</body>
</html>
