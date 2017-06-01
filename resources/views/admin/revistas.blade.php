<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - Mis revistas</title>
    @include("common.head")
    <style type="text/css">
        .tr-row{display:none;}
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
                            <a href="{{ url('/') }}">&lt; Volver</a>
                        </li>
                    </ul>
                </div>
                <div class="fluid-list cnt-table-filter">
                    <form id="form-xls" action="{{ url('export/revistas') }}" method="post">
                        <h2>Listado de Reparto <span class="right"></span></h2>
                        <div class="fluid-list filter-b" id="filterS">
                            <ul class="reset-ul">
                                <li>Filtrar por:</li>
                                <li>
                                    <select id="adCodigo" name="ccl[]" class="form-control" multiple="multiple">
                                        @foreach($ciclos as $ciclo)
                                        <option value="{{ $ciclo->value }}" selected="true">{{ $ciclo->text }}</option>
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
                                    <input id="gvCodCons" type="text" name="ccn" class="form-control gvCod" placeholder="Ingresa código.">
                                </li>
                                <li>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <button id="btBuscar" class="btn btn-search">Buscar</button>
                                </li>
                            </ul>
                        </div>
                    </form>
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
                                    <li><a href="javascript:exportToXls()" class="e-excel"><span></span> Exportar a Excel</a></li>
                                    <li><a href="#" class="e-mail" data-toggle="modal" data-target="#tModalE"><span></span> Enviar a mail</a></li>
                                </ul>
                            </div>
                            <div class="right">
                                <ul id="pg-container" class="pagination">
                                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">< Anterior</span></a></li>
                                    <li class="active"><a href="#">1</a></li>
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
                                <select class="form-control" id="tRecla" name="tRecla">
                                    <option value="0" selected disabled>Seleccione</option>
                                    @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->value }}">{{ $tipo->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título:</label>
                            <div class="col-sm-10">
                                <input type="text" id="titulo" name="titulo" class="form-control" id="titulo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="msn" class="col-sm-2 control-label">Mensaje:</label>
                            <div class="col-sm-10">
                                <textarea id="msn" name="msn" class="form-control" rows="5" style="resize:none;"></textarea>
                            </div>
                        </div>
                        <div id="reclamos-footer" class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" id="rautogen" />
                                <input type="hidden" id="rnroproceso" />
                                <input type="hidden" id="rnrocontrol" />
                                <button type="submit" class="btn btn-mC btn-grabar" id="bt-reclamo-post">GRABAR</button>
                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                        <div id="reclamos-loader" class="form-group" style="display:none;">
                            <div class="col-sm-offset-2 col-sm-10">
                                <p>
                                    <img src="{{ asset('asset/img/loader.gif') }}" style="height:50px;margin:5px;" />
                                    <span style="color:#808080;position:relative;top:15px;">Por favor, espere mientras procesamos su solicitud.</span>
                                </p>
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
                    <form class="form-horizontal m-form-n" id="form-email">
                        <h2>Enviar listado por mail</h2>
                        <p>Si desea enviar a varios destinatarios, separe las direcciones de email utilizando punto y coma (";").</p>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Enviar a :</label>
                            <div class="col-sm-10">
                                <input type="text" id="email" class="form-control" id="titulo" placeholder="dirección de email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="msn" class="col-sm-2 control-label">Mensaje:</label>
                            <div class="col-sm-10">
                                <textarea id="msn" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-mC btn-grabar" id="form-email-button">ENVIAR</button>
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
    @include("common.scripts")
    <script type="text/javascript">
        var rowsPerPage = 5, numberOfPages, currentPage, recUpdt = false;
        function formEmailOnSubmit(event) {
            event.preventDefault();
            toEmail();
        }
        function toEmail() {
            $("#form-email-button").attr("disabled", "disabled");
            var dataToPost = $("#form-xls").serializeArray();
            dataToPost.push({name: "mail", value: document.getElementById("email").value});
            dataToPost.push({name: "msn", value: document.getElementById("msn").value});
            $.ajax({
                type: "POST",
                url: "{{ url('ajax/revistas/email') }}",
                data: dataToPost,
                dataType: "json",
                success: function(data) {
                    if(data.success) {
                        $("#tModalE").modal("hide");
                    }
                    alert(data.message);
                    $("#form-email-button").removeAttr("disabled");
                },
                error: function() {
                    alert("No se pudo enviar el mensaje.");
                    $("#form-email-button").removeAttr("disabled");
                }
            });
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
                    _token: "{{ csrf_token() }}",
                    agn: tr.data("autogen"),
                    npr: tr.data("nrproc"),
                    ncn: tr.data("nrcont")
                };
                $.post("{{ url('ajax/revistas/detalle') }}", p, function(response) {
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
                            if(info.flag == 'N') {
                                tbody.append(
                                    $("<tr/>").append(
                                        $("<td/>")
                                    ).append(
                                        $("<td/>")
                                    ).append(
                                        $("<td/>").append(
                                            $("<a/>").attr("target","_blank").attr("href","http://170.79.39.11/wstar/modulos/busquedas/ver_cargo.php?autogen=" + tr.data("autogen") + "&remito=" + tr.data("nrproc") + "&control=" + tr.data("nrcont")).html("Ver fotos")
                                        )
                                    )
                                );
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
        function setReclamo() {
            var p = $(this).parent().parent();
            document.getElementById("rautogen").value = p.data("autogen");
            document.getElementById("rnroproceso").value = p.data("nrproc");
            document.getElementById("rnrocontrol").value = p.data("nrcont");
            if(p.data("rec") > 0) alert("Ya tiene reclamo registrado");
            else $("#tModalR").modal("show");
        }
        function chbOnClick() {
            var img = $(this);
            if(img.data("enabled") == "S") {
                var state = img.data("state");
                if(state == "check") {
                    if(window.confirm("¿Desea levantar la auditoría a este resultado?")) {
                        img.data("enabled","N");
                        img.attr("src", "{{ url('asset/img/check_dis.png') }}");
                        var p = {
                            _token: "{{ csrf_token() }}",
                            agn: img.data("value")
                        };
                        $.post("{{ url('ajax/revistas/auditoria/quitar') }}", p, function(response) {
                            if(response.success) {
                                img.attr("src", "{{ url('asset/img/uncheck.png') }}");
                                img.data("state", "uncheck");
                            }
                            else {
                                img.attr("src", "{{ url('asset/img/check.png') }}");
                                $("#modal-error-msg").html(response.message);
                                $("#modal-error").modal("show");
                            }
                            img.data("enabled","S");
                        }, "json");
                    }
                }
                else {
                    if(window.confirm("¿Desea realizar una auditoría a este resultado?")) {
                        img.data("enabled","N");
                        img.attr("src", "{{ url('asset/img/check_dis.png') }}");
                        var tr = img.parent().parent().parent().parent();
                        var p = {
                            _token: "{{ csrf_token() }}",
                            agn: tr.data("autogen"),
                            npr: tr.data("nrproc"),
                            ncn: tr.data("nrcont")
                        };
                        $.post("{{ url('ajax/revistas/auditoria/agregar') }}", p, function(response) {
                            if(response.success) {
                                img.attr("src", "{{ url('asset/img/check.png') }}");
                                img.data("state", "check");
                                img.data("value", response.autogen)
                            }
                            else {
                                img.attr("src", "{{ url('asset/img/uncheck.png') }}");
                                $("#modal-error-msg").html(response.message);
                                $("#modal-error").modal("show");
                            }
                            img.data("enabled","S");
                        }, "json");
                    }
                }
            }
        }
        function buscarOnLoad(response) {
            $("#tableCN-body").empty();
            if(response.success) {
                if(response.records > 0) {
                    var data = response.data;
                    for(var i in data) {
                        var fila = data[i];
                        $("#tableCN-body").append(
                            $("<tr/>").data({ autogen: fila.agn, nrproc: fila.npr, nrcont: fila.nct, idx:i, load:"N", rec:fila.ereclamo }).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.codcn)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.ciclo)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.situacion)
                            ).append(
                                $("<td/>").addClass("center").append(
                                    $("<a/>").attr("href","javascript:void(0)").append(
                                        $("<span/>").addClass(parseInt(fila.ereclamo) == 0 ? "glyphicon glyphicon-new-window" : "glyphicon glyphicon-check").attr("aria-hidden",true)
                                    ).on("click",setReclamo)
                                )
                            ).append(
                                $("<td/>").addClass("center").append(
                                    $("<div/>").addClass("checkbox").append(
                                        $("<label/>").append(
                                            $("<img/>").attr("src","{{ url('asset/img') }}/" + (fila.enab == 'S' ? fila.auditoria : "check_dis") + ".png").attr("data-enabled",fila.enab).attr("data-value",fila.audit).attr("data-state",fila.auditoria).on("click",chbOnClick)
                                        )
                                    )
                                )
                            ).addClass("tr-row tr-" + Math.floor(i / rowsPerPage))
                        ).append(
                            $("<tr/>").append(
                                $("<td/>").attr("colspan",5).append(
                                    $("<div/>").attr("id","n" + i)
                                ).addClass("hiddenRow")
                            ).addClass("tableHidden")
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
                    $("#table-footer").fadeIn();
                    goto(currentPage);
                }
                else {
                    $("#tableCN-body").append(
                        $("<tr/>").append(
                            $("<td/>").attr("colspan","5").html("No existen resultados para su búsqueda")
                        )
                    );
                    $("#tableCN").fadeIn();
                    $("#table-footer").hide();
                }
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
        function btBuscarOnClick(event) {
            event.preventDefault();
            $("#tableCN-body").empty();
            $("#pg-container").empty();
            $("#btBuscar").html("Espere...").attr("disabled", true);
            var p = {
                _token: "{{ csrf_token() }}",
                ccl: $("#adCodigo").val(),
                cno: $("#adCNO").val(),
                grn: $("#adGerencia").val(),
                str: $("#adSector").val(),
                ccn: $("#gvCodCons").val()
            };
            if(!recUpdt) currentPage = 0;
            else recUpdt = false;
            if(p.ccl && p.cno && p.grn && p.str) $.post("{{ url('ajax/revistas/busca') }}", p, buscarOnLoad, "json");
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
        function postReclamoOnLoad(response) {
            if(response.success) {
                $("#tModalR").modal("hide");
                $("#tRecla>option:eq(0)").attr('selected', true);
                document.getElementById("titulo").value = "";
                document.getElementById("msn").value = "";
                document.getElementById("rautogen").value = "";
                document.getElementById("rnroproceso").value = "";
                document.getElementById("rnrocontrol").value = "";
                recUpdt = true;
                $("#btBuscar").trigger("click");
                document.getElementById("modal-success-msg").innerHTML = response.message;
                $("#modal-success").modal("show");
            }
            else {
                document.getElementById("modal-error-msg").innerHTML = response.success;
                $("#modal-error").modal("show");
            }
            $("#reclamos-footer").show();
            $("#reclamos-loader").hide();
        }
        function btReclamoPostOnClick(event) {
            event.preventDefault();
            $("#reclamos-footer").hide();
            $("#reclamos-loader").fadeIn(150);
            var p = {
                tpo: document.getElementById("tRecla").value,
                ttl: document.getElementById("titulo").value,
                msj: document.getElementById("msn").value,
                agn: document.getElementById("rautogen").value,
                npr: document.getElementById("rnroproceso").value,
                ncn: document.getElementById("rnrocontrol").value,
                _token: "{{ csrf_token() }}"
            };
            $.post("{{ url('ajax/revistas/reclamo') }}", p, postReclamoOnLoad, "json");
        }
        function exportToXls() {
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
            $("#btBuscar").on("click", btBuscarOnClick);
            $("#bt-reclamo-post").on("click", btReclamoPostOnClick);
            $("#form-email").on("submit", formEmailOnSubmit);
            set_combos();
        }
        $(init);
    </script>
</body>
</html>
