<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin Usuarios</title>
    @include("common.head")
    <style type="text/css">
        .table-footer,#tableCN{display:none;}
    </style>
</head>
<body class="drawer drawer--right">
    <div id="main">
        <div class="container">
            @include($user->tp_cliente . ".header")
            <div class="fluid-list cnt-container">
                <div class="fluid-list bread-n">
                    <ul class="reset-ul">
                        <a href="{{ url('/') }}">&lt; Volver</a>
                    </ul>
                </div>
                <div class="fluid-list cnt-table-filter">
                    <form id="form-xls" action="{{ url('export/usuarios') }}" method="post">
                        <h2>Administración de Usuarios <span class="right"></span></h2>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="fluid-list filter-b" id="filterS">
                            <ul class="reset-ul">
                                <li>Filtrar por:</li>
                                <li>
                                    <select id="adCodigo" name="prf[]" class="form-control" multiple="multiple">
                                        @foreach($perfiles as $perfil)
                                        <option value="{{ $perfil->value }}" selected="true">{{ $perfil->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <select id="adGerencia" name="grn[]" class="form-control" multiple="multiple">
                                        @foreach($gerencias as $gerencia)
                                        <option value="{{ $gerencia->value }}" selected="true">{{ $gerencia->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <select id="adSector" name="str[]" class="form-control" multiple="multiple">
                                        @foreach($sectores as $sector)
                                        <option value="{{ $sector->value }}" selected="true">{{ $sector->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <select id="adCNO" name="cno[]" class="form-control" multiple="multiple">
                                        @foreach($cnos as $cno)
                                        <option value="{{ $cno->value }}" selected="true">{{ $cno->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <input type="text" id="gvCodCons" name="ccn" class="form-control gvCod" placeholder="Ingresa código.">
                                </li>
                                <li>
                                    <button id="bt-buscar" class="btn btn-search">Buscar</button>
                                </li>
                            </ul>
                        </div>
                    </form>
                    <div class="fluid-list cnt-table-n">
                        <table id="tableCN" class="table table-condensed table-custom-n" style="border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th>Consultora</th>
                                    <th>Nombre</th>
                                    <th>Perfil</th>
                                    <th>Estado</th>
                                    <th class="center">Editar</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                            </tbody>
                        </table>
                    </div>
                    <div id="table-footer" class="fluid-list cnt-table-f">
                        <div class="fluid-list b-list-pag">
                            <div class="left">
                                <ul class="reset-ul">
                                    <li><a href="javascript:mode(0)" class="crear-usuario"><span></span> Crear usuario</a></li>
                                    <li class=" table-footer"><a href="javascript:toXls()" class="e-excel"><span></span> Exportar a Excel</a></li>
                                </ul>
                            </div>
                            <div class="right table-footer">
                                <ul id="pg-container" class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">< Anterior</span></a></li>
                                    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#" aria-label="Next"><span aria-hidden="true">Siguiente ></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modal de Reclamo-->
    <div class="modal fade modal-custom-n" id="tModalNuevo" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n" id="form-nuevo">
                        <h2>Registrar usuario</h2>
                        <input type="hidden" id="tbModo" value="ins" />
                        <div class="form-group">
                            <label id="nuevo-nombre" for="nombre" class="col-sm-2 control-label">Nombres:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbNombres" class="form-control" placeholder="Ingrese nombres">
                            </div>
                            <label id="nuevo-nombre" for="nombre" class="col-sm-2 control-label">Apellidos:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbApellidos" class="form-control" placeholder="Ingrese apellidos">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">Perfil:</label>
                            <div class="col-sm-4">
                                <select id="cbPerfil" class="form-control">
                                    @foreach($perfiles as $perfil)
                                    <option value="{{ $perfil->value }}">{{ $perfil->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Cod.Consult.:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbCodcons" class="form-control" placeholder="Codigo Consultora">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">Tp.Documento:</label>
                            <div class="col-sm-4">
                                <select id="cbTipodoc" class="form-control">
                                    <option value="1">DNI</option>
                                    <option value="2">RUC</option>
                                </select>
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Nro.Documento:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbNrodoc" class="form-control" placeholder="Documento de Identidad">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">Email:</label>
                            <div class="col-sm-4">
                                <input type="email" id="tbEmail" class="form-control" placeholder="Ingrese e-mail">
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Telefono:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbTelefono" class="form-control" placeholder="Ingrese teléfono">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">Gerencia:</label>
                            <div class="col-sm-4">
                                <select id="cbGerencia" class="form-control">
                                    <option value="0">Ninguna</option>
                                    @foreach($gerencias as $gerencia)
                                    <option value="{{ $gerencia->value }}">{{ $gerencia->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Sector:</label>
                            <div class="col-sm-4">
                                <select id="cbSector" class="form-control">
                                    <option value="0">Ninguno</option>
                                    @foreach($sectores as $sector)
                                    <option value="{{ $sector->value }}">{{ $sector->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">CNO:</label>
                            <div class="col-sm-4">
                                <select id="cbCno" class="form-control">
                                    <option value="0">Ninguna</option>
                                    @foreach($cnos as $nco)
                                    <option value="{{ $nco->value }}">{{ $nco->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Login:</label>
                            <div class="col-sm-4">
                                <input type="text" id="tbAlias" class="form-control" placeholder="Usuario">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-2 control-label">Clave:</label>
                            <div class="col-sm-4">
                                <input type="password" id="tbPassword" class="form-control" placeholder="Contraseña">
                            </div>
                            <label for="perfil" class="col-sm-2 control-label">Rep. clave:</label>
                            <div class="col-sm-4">
                                <input type="password" id="tbPassword2" class="form-control" placeholder="Confirmar contraseña">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-mC btn-grabar" id="btn-submit">GRABAR</button>
                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    <!--Modal de Reclamo-->
    <div class="modal fade modal-custom-n" id="modal-loader" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Cargando datos</h2>
                    <div class="form-group">
                        <p>Por favor, espere...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    <!--Modal de email-->
    <div class="modal fade modal-custom-n" id="tModalE" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n" action="">
                        <h2>Enviar listado por mail</h2>
                        <p>Si deseas enviar a varios destinatarios, separe las direcciones de email utilizando punto y coma (;).</p>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Enviar a :</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="titulo" placeholder="dirección de email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="msn" class="col-sm-2 control-label">Mensaje:</label>
                            <div class="col-sm-10">
                                <textarea name="msn" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-mC btn-grabar">ENVIAR</button>
                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                            </div>
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
                    <h4 class="modal-title">Operación fallida</h4>
                </div>
                <div class="modal-body">
                    <p id="modal-error-msg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- -->
    <div class="modal fade" id="modal-success" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Operación completada</h4>
                </div>
                <div class="modal-body">
                    <p id="modal-success-msg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    @include("common.scripts")
    <script type="text/javascript">
        var rowsPerPage = 5, numberOfPages, currentPage;
        function formNuevoOnSubmit(event) {
            event.preventDefault();
            var p = {
                _token: "{{ csrf_token() }}",
                nom: document.getElementById("tbNombres").value,
                ape: document.getElementById("tbApellidos").value,
                prf: document.getElementById("cbPerfil").value,
                tpd: document.getElementById("cbTipodoc").value,
                nrd: document.getElementById("tbNrodoc").value,
                eml: document.getElementById("tbEmail").value,
                tlf: document.getElementById("tbTelefono").value,
                grn: document.getElementById("cbGerencia").value,
                str: document.getElementById("cbSector").value,
                cno: document.getElementById("cbCno").value,
                als: document.getElementById("tbAlias").value,
                psw: document.getElementById("tbPassword").value,
                rpw: document.getElementById("tbPassword2").value,
                mod: document.getElementById("tbModo").value,
                cod: document.getElementById("tbCodcons").value
            };
            document.getElementById("btn-submit").innerHTML = "Espere...";
            $.post("{{ url('ajax/usuarios/registro') }}", p, function(response) {
                if(response.success) {
                    $("#bt-buscar").trigger("click");
                    alert("Usuario registrado!");
                    $("#tModalNuevo").modal("hide");
                    document.getElementById("form-nuevo").reset();
                }
                else alert(response,message);
                document.getElementById("btn-submit").innerHTML = "GRABAR";
            }, "json");
        }
        function mode(valor) {
            document.getElementById("tbNombres").value = "";
            document.getElementById("tbApellidos").value = "";
            document.getElementById("tbNrodoc").value = "";
            document.getElementById("tbEmail").value = "";
            document.getElementById("tbTelefono").value = "";
            document.getElementById("tbAlias").value = "";
            document.getElementById("tbPassword").value = "";
            document.getElementById("tbPassword2").value = "";
            document.getElementById("tbModo").value = "";
            document.getElementById("tbCodcons").value = "";
            if(valor == 0) {
                document.getElementById("tbModo").value = "ins";
                $("#tbAlias").removeAttr("disabled");
                $("#tbPassword").removeAttr("disabled");
                $("#tbPassword2").removeAttr("disabled");
            }
            else document.getElementById("tbModo").value = "upd";
            $("#tModalNuevo").modal("show");
        }
        function goto(page) {
            $(".cnt-int-c").removeClass("in");
            $("#pg-container > .active").removeClass("active");
            $(".tr-row").hide(150);
            switch(page) {
                case "prev":
                    goto(currentPage > 0 ? (currentPage - 1) : 0);
                    break;
                case "next":
                    goto(currentPage < (numberOfPages - 1) ? (currentPage + 1) : 0);
                    break;
                default:
                    currentPage = parseInt(page);
                    $(".tr-" + page).show();
                    $("#pg-container").children("li").eq(parseInt(page) + 1).addClass("active");
                    if(page == 0) $("#pg-prev").addClass("disabled");
                    else $("#pg-prev").removeClass("disabled");
                    if(page == (numberOfPages - 1)) $("#pg-next").addClass("disabled");
                    else $("#pg-next").removeClass("disabled");
                    break;
            }
        }
        function editaUsuario() {
            var a = $(this);
            var codusr = a.parent().parent().data("cod");
            $("#modal-loader").modal("show");
            var p = {
                _token: "{{ csrf_token() }}",
                cod: codusr
            };
            $.post("{{ url('ajax/usuarios/edicion') }}", p, function(response) {
                $("#modal-loader").modal("hide");
                if(response.success) {
                    $("#tModalNuevo").modal("show");
                    var usuario = response.user;
                    document.getElementById("tbModo").value = "upd";
                    document.getElementById("tbNombres").value = usuario.nom;
                    document.getElementById("tbApellidos").value = usuario.ape;
                    $("#cbPerfil option[value=" + usuario.prf + "]").attr('selected','selected');
                    $("#cbTipodoc option[value=" + usuario.tpd + "]").attr('selected','selected');
                    document.getElementById("tbNrodoc").value = usuario.nrd;
                    document.getElementById("tbEmail").value = usuario.eml;
                    document.getElementById("tbTelefono").value = usuario.tlf;
                    $("#cbGerencia option[value=" + usuario.grn + "]").attr('selected','selected');
                    $("#cbSector option[value=" + usuario.str + "]").attr('selected','selected');
                    $("#cbCno option[value=" + usuario.cno + "]").attr('selected','selected');
                    document.getElementById("tbAlias").value = usuario.als;
                    document.getElementById("tbPassword").value = "NoSeasSapo";
                    document.getElementById("tbPassword2").value = "NoSeasSapo";
                    document.getElementById("tbModo").value = usuario.mod;
                    document.getElementById("tbCodcons").value = usuario.cod;
                    $("#tbAlias").attr("disabled", "disabled");
                    $("#tbPassword").attr("disabled", "disabled");
                    $("#tbPassword2").attr("disabled", "disabled");
                }
                else {
                    alert(response.message);
                }
            }, "json");
        }
        function buscarOnLoad(response) {
            $("#tbody").empty();
            if(response.success) {
                var filas = response.data;
                for(var i in filas) {
                    var fila = filas[i];
                    $("#tbody").append(
                        $("<tr/>").data("cod",fila.code).append(
                            $("<td/>").html(fila.codigo)
                        ).append(
                            $("<td/>").html(fila.nombre)
                        ).append(
                            $("<td/>").html(fila.perfil)
                        ).append(
                            $("<td/>").html(fila.estado)
                        ).append(
                            $("<td/>").addClass("center").append(
                                $("<a/>").attr("href","javascript:void(0)").append(
                                    $("<span/>").addClass("glyphicon glyphicon-new-window").attr("aria-hidden","true")
                                ).on("click", editaUsuario)
                            )
                        )
                    );
                }
                $("#pg-container").empty().append(
                    $("<li/>").addClass("disabled").attr("id","pg-prev").append(
                        $("<a/>").attr({
                            href: "javascript:goto('prev')",
                            "aria-label": "Anterior"
                        }).append(
                            $("<span/>").attr("aria-hidden","true").html("< Anterior")
                        )
                    )
                );
                numberOfPages = response.pages;
                for(var i = 0; i < numberOfPages; i++) {
                    $("#pg-container").append(
                        $("<li/>").addClass(i == 0 ? "active" : "").append(
                            $("<a/>").attr("href","javascript:goto(" + i + ")").html(i + 1)
                        )
                    )
                }
                $("#pg-container").append(
                    $("<li/>").attr("id","pg-next").append(
                        $("<a/>").attr({
                            href: "javascript:goto('next')",
                            "aria-label": "Siguiente"
                        }).append(
                            $("<span/>").attr("aria-hidden","true").html("Siguiente >")
                        )
                    )
                );
                $("#tableCN").fadeIn();
                $(".table-footer").fadeIn();
                goto(0);
            }
            else {
                document.getElementById("modal-error-msg").innerHTML = response.message;
                $("#modal-error").modal("show");
            }
            $("#bt-buscar").html("Buscar").removeAttr("disabled");
        }
        function btBuscarOnClick(event) {
            event.preventDefault();
            $("#bt-buscar").html("Espere...").attr("disabled", true);
            var p = {
                _token: "{{ csrf_token() }}",
                cno: $("#adCNO").val(),
                grn: $("#adGerencia").val(),
                str: $("#adSector").val(),
                prf: $("#adCodigo").val(),
                ccn: $("#gvCodCons").val()
            };
            if(p.cno && p.grn && p.str && p.prf) {
                $("#tableCN").hide();
                $(".table-footer").hide();
                $.post("{{ url('ajax/usuarios/busca') }}", p, buscarOnLoad, "json");
            }
            else {
                document.getElementById("modal-error-msg").innerHTML = "Elija correctamente los filtros para realizar la búsqueda";
                $("#modal-error").modal("show");
                $("#bt-buscar").html("Buscar").removeAttr("disabled");
            }
        }
        function toXls() {
            document.getElementById("form-xls").submit();
        }
        function populate_combo(idCombo, data) {
            var combo = $(idCombo);
            combo.empty();
            for(var i in data) {
                var idata = data[i];
                combo.append(
                    $("<option/>").attr("selected","selected").val(idata.value).html(idata.value)
                );
            }
            $(idCombo).multiselect("rebuild");
        }
        function adCodigoOnChange() {
            $("input[type=checkbox]").attr("disabled", "disabled");
            var p = {
                _token: "{{ csrf_token() }}",
                ccl: $("#adCodigo").val()
            };
            $.post("{{ url('ajax/combos/ls_gerencias') }}", p, function(response) {
                if(response.success) {
                    populate_combo("#adGerencia", response.gerencias);
                    populate_combo("#adSector", response.sectores);
                    populate_combo("#adCNO", response.cnos);
                }
                $("input[type=checkbox]").removeAttr("disabled");
            }, "json");
        }
        function adGerenciaOnChange() {
            $("input[type=checkbox]").attr("disabled", "disabled");
            var p = {
                _token: "{{ csrf_token() }}",
                ccl: $("#adCodigo").val(),
                grn: $("#adGerencia").val()
            };
            $.post("{{ url('ajax/combos/ls_sectores') }}", p, function(response) {
                if(response.success) {
                    populate_combo("#adSector", response.sectores);
                    populate_combo("#adCNO", response.cnos);
                }
                $("input[type=checkbox]").removeAttr("disabled");
            }, "json");
        }
        function adSectorOnChange() {
            $("input[type=checkbox]").attr("disabled", "disabled");
            var p = {
                _token: "{{ csrf_token() }}",
                ccl: $("#adCodigo").val(),
                grn: $("#adGerencia").val(),
                sec: $("#adSector").val()
            };
            $.post("{{ url('ajax/combos/ls_cnos') }}", p, function(response) {
                if(response.success) {
                    populate_combo("#adCNO", response.cnos);
                }
                $("input[type=checkbox]").removeAttr("disabled");
            }, "json");
        }
        function set_combos() {
            $("#adCodigo").on("change", adCodigoOnChange);
            $("#adGerencia").on("change", adGerenciaOnChange);
            $("#adSector").on("change", adSectorOnChange);
        }
        function init() {
            $("#bt-buscar").on("click", btBuscarOnClick);
            $("#form-nuevo").on("submit", formNuevoOnSubmit);
        }
        $(init);
    </script>
</body>
</html>
