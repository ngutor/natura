<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - Reclamos</title>
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
                            <a href="{{ url('/') }}">&lt; Volver</a>
                        </li>
                    </ul>
                </div>
                <div class="fluid-list cnt-table-filter cnt-table-reclamos">
                    <h2>Reclamos <span class="right"></span></h2>
                    <form id="form-xls" action="{{ url('export/reclamos') }}" method="post">
                        <div class="fluid-list filter-b" id="filterS">
                            <ul class="reset-ul">
                                <li>Filtrar por:</li>
                                <li>
                                    <select id="adEIn" name="est[]" class="form-control" multiple="multiple">
                                        @foreach($estados as $estado)
                                        <option value="{{ $estado->value }}" selected="true">{{ $estado->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <select id="adCodigo" name="ccl[]" class="form-control" multiple="multiple">
                                        @foreach($ciclos as $ciclo)
                                        <option value="{{ $ciclo->value }}" selected="true">{{ $ciclo->text }}</option>
                                        @endforeach
                                    </select>
                                    <input id="tbGerencia" name="grn[]" type="hidden" value="{{ $user->v_PerClienteAgrupa1 }}" />
                                    <input id="tbSector" name="str[]" type="hidden" value="{{ $user->v_PerClienteAgrupa2 }}" >
                                </li>
                                <li>
                                    <select id="adCNO" name="cno[]" class="form-control" multiple="multiple">
                                        @foreach($cnos as $cno)
                                        <option value="{{ $cno->value }}" selected="true">{{ $cno->text }}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <input type="text" name="gvCodCons" name="ccn" class="form-control gvCod adCconsulta" placeholder="Ingresa código.">
                                </li>
                                <li>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button id="btBuscar" class="btn btn-search">Buscar</button>
                                </li>
                            </ul>
                        </div>
                    </form>
                    <div class="fluid-list cnt-table-n">
                        <form id="form-single-download" method="post" action="{{ url('export/reclamo') }}">
                            <input type="hidden" id="autogenatc" name="autogenatc" value="" />
                            <input type="hidden" id="rautogen" name="rautogen" value="" />
                            <input type="hidden" id="rnroproceso" name="rnroproceso" value="" />
                            <input type="hidden" id="rnrocontrol" name="rnrocontrol" value="" />
                            <input type="hidden" id="ciclo" name="ciclo" value="" />
                            <input type="hidden" id="fecha" name="fecha" value="" />
                            <input type="hidden" id="eini" name="eini" value="" />
                            <input type="hidden" id="efin" name="efin" value="" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        </form>
                        <table id="tableCN" class="table table-condensed table-custom-n" style="border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th>Ciclo</th>
                                    <th>Consultora</th>
                                    <th>F. Reclamo</th>
                                    <th>Generado por</th>
                                    <th class="center">D. Resol.</th>
                                    <th>Est. Inicial</th>
                                    <th>Est. Final</th>
                                    <th class="center">Reclamo</th>
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
                                </ul>
                            </div>
                            <div class="right">
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
    <!--Modal de Observaciones-->
    <div class="modal fade modal-custom-n" id="tModalR" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n" action="">
                        <h2>Actualizar reclamo</h2>
                        <div class="form-group">
                            <label for="tReclamo" class="col-sm-2 control-label">Tipo de reclamo:</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="tRecla">
                                    @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->value }}">{{ $tipo->text }}</option>
                                    @endforeach
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
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="esi" class="col-sm-4 control-label">Estado Inicial:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="esi" disabled="disabled" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="esf" class="col-sm-4 control-label">Estado Final:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="esf" disabled="disabled" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cmtGR" class="col-sm-2 control-label">Comentario GR:</label>
                            <div class="col-sm-10">
                                <textarea id="cmtGR" class="form-control" rows="2" style="resize:none;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="msn" class="col-sm-2 control-label">Mensaje:</label>
                            <div class="col-sm-10">
                                <textarea id="msn" class="form-control" rows="5" style="resize:none;"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="reclamos-footer">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-mC btn-grabar" id="bt-reclamo-post">GRABAR</button>
                                <button type="button" class="btn btn-mC btn-cancelar" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End-->
    <!--Modal de carga-->
    <div class="modal fade modal-custom-n" id="modal-loader" tabindex="-1" role="dialog" aria-labelledby="modalUploadLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n">
                        <h2>Procesando</h2>
                        <div class="form-group">
                            <p>Por favor, espere...</p>
                        </div>
                    </form>
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
    <!--End-->
    @include("common.scripts")
    <script type="text/javascript">
        var rowsPerPage = 5, numberOfPages, currentPage, recUpdt = false;
        function descargaReclamo() {
            document.getElementById("form-download").submit();
        }
        function formUploadOnSubmit(event) {
            event.preventDefault();
            $("#modal-upload").modal("hide");
            $("#modal-loader").modal("show");
            //form-upload
            var data = new FormData();
            $.each($("#in-reclamo")[0].files, function(i, file) {
                data.append("file-" + i, file);
            });
            data.append("_token", "{{ csrf_token() }}");
            $.ajax({
                url: "{{ url('import/upload') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data){
                    if(data.success) {
                        $("#in-reclamo").val(null);
                        $("#modal-loader").modal("hide");
                        $("#tModalDescargo").modal("show");
                    }
                    else {
                        $("#tModalNoSubio").modal("show");
                    }
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
            $("#modal-loader").modal("show");
            var p = $(this).parent().parent();
            document.getElementById("autogenatc").value = p.data("agnatc");
            var params = {
                _token: "{{ csrf_token() }}",
                agn: p.data("agnatc")
            };
            $.post("{{ url('ajax/reclamos/edicion') }}", params, function(response) {
                $("#modal-loader").modal("hide");
                if(response.success) {
                    var reclamo = response.data;
                    $("#tRecla option[value=" + reclamo.tipo + "]").attr("selected", "selected");
                    $("#titulo").val(reclamo.asunto);
                    $("#esi").val(reclamo.efinal);
                    $("#esf").val(reclamo.einicial);
                    $("#cmtGR").val(reclamo.comentario);
                    $("#msn").val(reclamo.descripcion);
                    $("#tModalR").modal("show");
                }
                else alert(response.message);
            }, "json");
        }
        function buscarOnLoad(response) {
            $("#tableCN-body").empty();
            if(response.success) {
                if(response.records > 0) {
                    var data = response.data;
                    for(var i in data) {
                        var fila = data[i];
                        $("#tableCN-body").append(
                            $("<tr/>").data({ autogen: fila.agn, nrproc: fila.npr, nrcont: fila.nct, idx:i, load:"N", agnatc: fila.autoatc }).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.ciclo)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.consultora)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.fechareclamo)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.usuario)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.dresol)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.estainicial)
                            ).append(
                                $("<td/>").on("click",cargaTabs).attr({"data-toggle":"collapse", "data-target":"#n" + i}).css("cursor","pointer").html(fila.estafinal)
                            ).append(
                                $("<td/>").addClass("center").append(
                                    $("<a/>").attr("href","javascript:void(0)").append(
                                        $("<span/>").addClass("glyphicon glyphicon-new-window").attr("aria-hidden",true)
                                    ).on("click",setReclamo)
                                )
                            ).addClass("tr-row tr-" + Math.floor(i / rowsPerPage))
                        ).append(
                            $("<tr/>").append(
                                $("<td/>").attr("colspan",8).append(
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
                est: $("#adEIn").val(),
                ccl: $("#adCodigo").val(),
                cno: $("#adCNO").val(),
                grn: [$("#tbGerencia").val()],
                str: [$("#tbSector").val()],
                dts: $("#adFecha").val(),
                ccn: $("#gvCodCons").val()
            };
            if(!recUpdt) currentPage = 0;
            else recUpdt = false;
            if(p.ccl && p.cno && p.grn && p.str) $.post("{{ url('ajax/reclamos/busca') }}", p, buscarOnLoad, "json");
            else {
                document.getElementById("modal-error-msg").innerHTML = "Elija correctamente los filtros para realizar la búsqueda";
                $("#modal-error").modal("show");
                $("#btBuscar").html("Buscar").removeAttr("disabled");
            }
        }
        function postReclamoOnLoad(response) {
            if(response.success) {
                $("#tModalR").modal("hide");
                $("#tRecla>option:eq(0)").attr('selected', true);
                document.getElementById("titulo").value = "";
                document.getElementById("esi").value = "";
                document.getElementById("esf").value = "";
                document.getElementById("cmtGR").value = "";
                document.getElementById("msn").value = "";
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
            $("#modal-loader").modal("show");
        }
        function btReclamoPostOnClick(event) {
            event.preventDefault();
            $("#reclamos-footer").hide();
            $("#modal-loader").modal("show");
            var p = {
                _token: "{{ csrf_token() }}",
                agn: document.getElementById("autogenatc").value,
                tpo: $("#tRecla").val(),
                asn: (document.getElementById("titulo").value == "" ? "-" : document.getElementById("titulo")).value,
                com: (document.getElementById("cmtGR").value == "" ? "-" : document.getElementById("cmtGR")).value,
                msg: (document.getElementById("msn").value == "" ? "-" : document.getElementById("msn")).value
            };
            $.post("{{ url('ajax/reclamos/upd_reclamo') }}", p, postReclamoOnLoad, "json");
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
                ccl: $("#adCodigo").val(),
                grn: [$("#tbGerencia").val()],
                sec: [$("#tbSector").val()]
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
        }
        function init() {
            $("#btBuscar").on("click", btBuscarOnClick);
            $("#bt-reclamo-post").on("click", btReclamoPostOnClick);
            $("#form-upload").on("submit",formUploadOnSubmit);
            set_combos();
        }
        $(init);
    </script>
</body>
</html>
