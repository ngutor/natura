<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - Mis revistas</title>
    @include("common.head")
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/magnific-popup.css?v1') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css?v1') }}">
</head>

<body>
    <div id="main">
        <div class="container">
            @include($user->tp_cliente . ".header")
            <div class="fluid-list cnt-container">
                <div class="fluid-list bread-n">
                    <ul class="reset-ul">
                        <li>
                            <a href="#">&lt; Volver</a>
                        </li>
                    </ul>
                </div>
                <div class="fluid-list cnt-table-filter">
                    <h2>Listado de Reparto <span class="right">15 de febrero del 2017</span></h2>
                    <div class="fluid-list filter-b" id="filterS">
                        <ul class="reset-ul">
                            <li>Filtrar por:</li>
                            <li>
                                <select id="adCodigo" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adCNO" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adGerencia" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adSector" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <input id="gvCodCons" type="text" name="gvCodCons" class="form-control gvCod" placeholder="Ingresa código.">
                            </li>
                            <li>
                                <button id="btBuscar" class="btn btn-search">Buscar</button>
                            </li>
                        </ul>
                    </div>
                    <div class="fluid-list cnt-table-n">
                        <table id="tableCN" class="table table-condensed table-custom-n" style="border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th>Consultora</th>
                                    <th>Ciclo</th>
                                    <th>Situación</th>
                                    <th class="center">Reclamo</th>
                                    <th class="center">Auditoría</th>
                                </tr>
                            </thead>
                            <tbody id="tableCN-body">
                        </table>
                    </div>
                    <div id="table-footer" class="fluid-list cnt-table-f">
                        <div class="fluid-list b-list-pag">
                            <div class="left">
                                <ul class="reset-ul">
                                    <li><a href="#" class="e-excel"><span></span> Exportar a Excel</a></li>
                                    <li><a href="#" class="e-mail" data-toggle="modal" data-target="#tModalE"><span></span> Enviar a mail</a></li>
                                </ul>
                            </div>
                            <div class="right">
                                <ul class="pagination">
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
    <div class="modal fade modal-custom-n" id="tModalR" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n" action="">
                        <h2>Escribir un reclamo</h2>
                        <div class="form-group">
                            <label for="tRecla" class="col-sm-2 control-label">Tipo de reclamo:</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="tRecla">
                                    <option>Dirección de Email</option>
                                    <option>Dirección de Email 1</option>
                                    <option>Dirección de Email 2</option>
                                    <option>Dirección de Email 3</option>
                                    <option>Dirección de Email 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" name="titulo" class="form-control" id="titulo">
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
                                <button type="submit" class="btn btn-mC btn-grabar">GRABAR</button>
                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </form>
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
                        <p>Si deseas enviar a varios destinatarios, separe las direcciones de emailutilizando punto y coma (;).</p>
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
    <!--End-->
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
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('asset/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('asset/js/main.js?v1') }}"></script>
    <script type="text/javascript">
        function populate_combo(id,data) {
            $(id).empty();
            for(var i in data) {
                var it = data[i];
                $(id).append(
                    $("<option/>").val(it.value).html(it.text)
                );
            }
            $(id).multiselect("rebuild");
        }
        function cargaTabs() {
            var tr = $(this).parent();
            if(tr.data("load") == "N") {
                var idx = tr.data("idx");
                tr.data("load","S");
                $("#n" + idx).empty().append(
                    $("<p/>").css("padding","5px").append(
                        $("<img/>").attr("src","{{ asset('asset/img/loader.gif') }}").css({height:50,margin:"5px"})
                    ).append(
                        $("<span/>").css({color:"#808080",position:"relative",top:"15px"}).html("Cargando datos. Por favor, espere...")
                    )
                );
                var p = {
                    cod: "{{ $user->cod_usuario }}",
                    tkn: "{{ $user->token }}",
                    agn: tr.data("autogen"),
                    npr: tr.data("nrproc"),
                    ncn: tr.data("nrcont")
                };
                $.post("{{ $route_det }}", p, function(response) {
                    $("#n" + idx).empty();
                    if(response.success) {
                        var info = response.info;
                        var tracking = response.tracking;
                        var tbody = $("<tbody/>");
                        if(tracking.length > 0) {
                            for(var k in tracking) {
                                var row = tracking[k];
                                tbody.append(
                                    $("<tr/>").append(
                                        $("<td/>").html(row.fecha)
                                    ).append(
                                        $("<td/>").html(row.estado)
                                    ).append(
                                        $("<td/>").html(row.observ)
                                    )
                                )
                            }
                        }
                        else {
                            tbody.append(
                                $("<tr/>").append(
                                    $("<td/>").attr("colspan",3).html("No se encontraron registros")
                                )
                            )
                        }
                        $("#n" + idx).append(
                            $("<ul/>").attr("role","tablist").append(
                                $("<li/>").attr("role","presentation").append(
                                    $("<a/>").attr({
                                        "href":"#datoB" + idx,
                                        "aria-controls":"home",
                                        "role":"tab",
                                        "data-toggle":"tab"
                                    }).html("Datos básicos")
                                ).addClass("active")
                            ).append(
                                $("<li/>").attr("role","presentation").append(
                                    $("<a/>").attr({
                                        "href":"#traking" + idx,
                                        "aria-controls":"profile",
                                        "role":"tab",
                                        "data-toggle":"tab"
                                    }).html("Tracking del envío")
                                )
                            ).addClass("nav nav-tabs")
                        ).append(
                            $("<div/>").append(
                                $("<div/>").attr({id:"datoB" + idx,role:"tabpanel"}).append(
                                    $("<table/>").append(
                                        $("<tbody/>").append(
                                            $("<tr/>").append(
                                                $("<td/>").html("Nombre")
                                            ).append(
                                                $("<td/>").html(info.nombre)
                                            )
                                        ).append(
                                            $("<tr/>").append(
                                                $("<td/>").html("Inactividad")
                                            ).append(
                                                $("<td/>").html(info.situac)
                                            )
                                        ).append(
                                            $("<tr/>").append(
                                                $("<td/>").html("Dirección")
                                            ).append(
                                                $("<td/>").html(info.direccion)
                                            )
                                        ).append(
                                            $("<tr/>").append(
                                                $("<td/>").html("Distrito")
                                            ).append(
                                                $("<td/>").html(info.distrito)
                                            )
                                        ).append(
                                            $("<tr/>").append(
                                                $("<td/>").html("Teléfono")
                                            ).append(
                                                $("<td/>").html(info.telefono)
                                            )
                                        )
                                    ).addClass("table table-tab-b")
                                ).addClass("tab-pane active")
                            ).append(
                                $("<div/>").attr({id:"traking" + idx,role:"tabpanel"}).append(
                                    $("<table/>").append(
                                        $("<thead/>").append(
                                            $("<tr/>").append(
                                                $("<th/>").html("Fecha")
                                            ).append(
                                                $("<th/>").html("Estado")
                                            ).append(
                                                $("<th/>").html("Observación")
                                            )
                                        )
                                    ).append(tbody).addClass("table table-tab-f")
                                ).addClass("tab-pane")
                            ).addClass("tab-content")
                        ).addClass("collapse cnt-int-c")
                    }
                    else {
                        $("#n" + idx).empty().append($("<p/>").css("padding","5px").html(response.message));
                    }
                }, "json");
            }
        }
        function buscarOnLoad(response) {
            $("#tableCN-body").empty();
            if(response.success) {
                var data = response.data;
                for(var i in data) {
                    var fila = data[i];
                    $("#tableCN-body").append(
                        $("<tr/>").data({ autogen: fila.agn, nrproc: fila.npr, nrcont: fila.nct, idx:i, load:"N" }).append(
                            $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.codcn)
                        ).append(
                            $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.ciclo)
                        ).append(
                            $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.situacion)
                        ).append(
                            $("<td/>").addClass("center").append(
                                $("<a/>").attr("href","#").attr({"data-toggle":"modal","data-target":"#tModalR"}).append(
                                    $("<span/>").addClass("glyphicon glyphicon-new-window").attr("aria-hidden",true)
                                )
                            )
                        ).append(
                            $("<td/>").addClass("center").append(
                                $("<div/>").addClass("checkbox").append(
                                    $("<label/>").append(
                                        $("<input/>").attr("type","checkbox")
                                    )
                                )
                            )
                        )
                    ).append(
                        $("<tr/>").append(
                            $("<td/>").attr("colspan",5).append(
                                $("<div/>").attr("id","n" + i)
                            ).addClass("hiddenRow")
                        ).addClass("tableHidden")
                    );
                }
                $("#tableCN").fadeIn();
                $("#table-footer").fadeIn();
            }
            else {
                $("#modal-error-msg").innerHTML = response.message;
                $("#modal-error").modal("show");
            }
            $("#btBuscar").html("Buscar").removeAttr("disabled");
        }
        function buscarError(xhr, status, error) {
            $("#modal-error-msg").innerHTML = err.responseText;
            $("#modal-error").modal("show");
        }
        function btBuscarOnClick() {
            $("#btBuscar").html("Espere...").attr("disabled", true);
            var p = {
                cod: "{{ $user->cod_usuario }}",
                tkn: "{{ $user->token }}",
                ccl: $("#adCodigo").val(),
                cno: $("#adCNO").val(),
                grn: $("#adGerencia").val(),
                str: $("#adSector").val(),
                ccn: $("#gvCodCons").val()
            };
            if(p.ccl && p.cno && p.grn && p.str) $.post("{{ $route_d }}", p, buscarOnLoad, "json");
            else {
                document.getElementById("modal-error-msg").innerHTML = "Elija correctamente los filtros para realizar la búsqueda";
                $("#modal-error").modal("show");
                $("#btBuscar").html("Buscar").removeAttr("disabled");
            }
        }
        function postOnLoad(response) {
            if(response.success) {
                populate_combo("#adCodigo", response.ciclos);
                populate_combo("#adGerencia", response.gerencias);
                populate_combo("#adSector", response.sectores);
                populate_combo("#adCNO", response.cnos);
            }
            else {
                document.getElementById("modal-error-msg").innerHTML = response.message;
                $("#modal-error").modal("show");
            }
        }
        function init() {
            $("#tableCN").hide();
            $("#table-footer").hide();
            $("#btBuscar").on("click", btBuscarOnClick);
            var p = {
                cod: "{{ $user->cod_usuario }}",
                tkn: "{{ $user->token }}"
            };
            $.post("{{ $route_i }}", p, postOnLoad, "json");
        }
        $(init);
    </script>
</body>
</html>
