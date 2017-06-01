<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mis indicadores</title>
    @include("common.head")
    <style type="text/css">
        #chart-div{display:none;}
    </style>
</head>
<body class="drawer drawer--right">
    <div id="main">
        <div class="container">
            @include($user->tp_cliente . '.header')
            <div class="fluid-list cnt-container">
                <div class="fluid-list bread-n">
                    <ul class="reset-ul">
                        <li>
                            <a href="{{ url('/') }}">&lt; Volver</a>
                        </li>
                    </ul>
                </div>
                <div class="fluid-list cnt-table-filter">
                    <h2>Mis Indicadores - Efectividad de Entrega</h2>
                    <div class="fluid-list filter-b" id="filterS">
                        <ul class="reset-ul">
                            <li>Filtrar por:</li>
                            <li>
                                <select id="adCiclo" class="form-control" multiple="multiple">
                                    @foreach($ciclos as $ciclo)
                                    <option value="{{ $ciclo->value }}" selected="true">{{ $ciclo->text }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="tbGerencia" value="{{ $user->v_PerClienteAgrupa1 }}" />
                                <input type="hidden" id="tbSector" value="{{ $user->v_PerClienteAgrupa2 }}" />
                            </li>
                            <li>
                                <select id="adCNO" class="form-control" multiple="multiple">
                                    @foreach($cnos as $cno)
                                    <option value="{{ $cno->value }}" selected="true">{{ $cno->text }}</option>
                                    @endforeach
                                </select>
                            </li>
                            <!--li>
                                <select id="adSituacion" class="form-control" multiple="multiple"></select>
                            </li-->
                            <li>
                                <button id="btn-busca" class="btn btn-search">Buscar</button>
                            </li>
                        </ul>
                    </div>
                    <div id="chart-div" class="fluid-list cnt-graficas-n">
                        <div class="row"><!-- GRÁFICO 1 -->
                            <div class="col-xs-6 col-md-7">
                                <div id="chart-container-1"></div>
                            </div>
                            <div class="col-xs-5 col-md-4">
                                <br/><br/><br/><br/><br/>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="adFecha1" class="col-sm-4 control-label">Fecha(s)</label>
                                        <div class="col-sm-8">
                                            <select id="adFecha1" class="form-control adFecha" multiple="multiple"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="button" id="btRefreshCh1" class="btn btn-danger" value="Refrescar" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row"><!-- GRÁFICO 2 -->
                            <div class="col-xs-6 col-md-7">
                                <div id="chart-container-2"></div>
                            </div>
                            <div class="col-xs-5 col-md-4">
                                <br/><br/><br/><br/><br/>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="adFecha2" class="col-sm-4 control-label">Fecha(s)</label>
                                        <div class="col-sm-8">
                                            <select id="adFecha2" class="form-control adFecha" multiple="multiple"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="button" id="btRefreshCh2" class="btn btn-danger" value="Refrescar" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row"><!-- GRÁFICO 3 -->
                            <div class="col-xs-6 col-md-7">
                                <div id="chart-container-3"></div>
                            </div>
                            <div class="col-xs-5 col-md-4">
                                <br/><br/><br/><br/><br/>
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="adFecha3" class="col-sm-4 control-label">Fecha(s)</label>
                                        <div class="col-sm-8">
                                            <select id="adFecha3" class="form-control adFecha" multiple="multiple"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <input type="button" id="btRefreshCh3" class="btn btn-danger" value="Refrescar" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--div class="fluid-list cnt-table-f">
                        <div class="fluid-list b-list-pag">
                            <div class="left">
                                <ul class="reset-ul">
                                    <li><a href="#" class="e-excel"><span></span> Exportar a Excel</a></li>
                                    <li><a href="#" class="e-mail" data-toggle="modal" data-target="#tModalE"><span></span> Enviar a mail</a></li>
                                </ul>
                            </div>
                        </div>
                    </div-->
                </div>
            </div>
        </div>
    </div>
    <!--Modal de email-->
    <div class="modal fade modal-custom-n" id="tModalE" tabindex="-1" role="dialog" aria-labelledby="tModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="form-horizontal m-form-n" action="">
                        <h2>Enviar listado por mail</h2>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Enviar a :</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" id="titulo">
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
    @include("common.scripts")
    <script src="{{ url('asset/js/highcharts.js') }}"></script>
    <script src="{{ url('asset/js/highcharts-3d.js') }}"></script>
    <script type="text/javascript">
        var dataset, arr_fechas, numberOfItems, arr_estados;
        function get_index(v_array,v_key) {
            var indexOf = -1;
            var size = v_array.length;
            for(var i = 0; i < size; i++) {
                var v_array_i = v_array[i];
                if(v_array_i[0] == v_key) indexOf = i;
            }
            return indexOf;
        }
        function get_index_arr(v_array,v_key) {
            var indexOf = -1;
            var size = v_array.length;
            for(var i = 0; i < size; i++) {
                var v_array_i = v_array[i];
                if(v_array_i == v_key) indexOf = i;
            }
            return indexOf;
        }
        function get_index_obj(v_array,v_key) {
            var indexOf = -1;
            var size = v_array.length;
            for(var i = 0; i < size; i++) {
                var v_array_i = v_array[i];
                if(v_array_i.name == v_key) indexOf = i;
            }
            return indexOf;
        }
        function set_combos_fechas() {
            arr_fechas = [];
            numberOfItems = dataset.length;
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                var ifecha = arr_fechas.indexOf(dato.fechavisita);
                if(ifecha == -1) arr_fechas.push(dato.fechavisita);
            }
            var nFechas = arr_fechas.length;
            $("#adFecha1").multiselect("destroy");
            $("#adFecha2").multiselect("destroy");
            $("#adFecha3").multiselect("destroy");
            $("#adFecha1").empty();
            $("#adFecha2").empty();
            $("#adFecha3").empty();
            for(var j = 0; j < nFechas; j++) {
                if(arr_fechas[j] != "") {
                    $("#adFecha1").append(
                        $("<option/>").val(arr_fechas[j]).attr("selected",true).html(arr_fechas[j])
                    );
                    $("#adFecha2").append(
                        $("<option/>").val(arr_fechas[j]).attr("selected",true).html(arr_fechas[j])
                    );
                    $("#adFecha3").append(
                        $("<option/>").val(arr_fechas[j]).attr("selected",true).html(arr_fechas[j])
                    );
                }
            }
            $(".adFecha").multiselect({
                includeSelectAllOption: true,
                selectAllName: 'select-all-name'
            });
        }
        function draw_chart_1() {
            var sel_fechas = $("#adFecha1").val();
            var arr_data = [];
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                if(sel_fechas.indexOf(dato.fechavisita) > -1) {
                    var indice = get_index(arr_data, dato.estado);
                    if(indice > -1) { //existe
                        arr_data[indice][1] = arr_data[indice][1] + parseInt(dato.cant);
                    }
                    else {
                        arr_data.push([dato.estado, parseInt(dato.cant)]);
                    }
                }
            }
            arr_fechas.sort();
            $("#chart-container-1").height(480).highcharts({
                chart: {
                    type: 'pie',
                    options3d: { enabled: true,alpha: 45 }
                },
                title: { text: 'Efectividad de entrega' },
                plotOptions: {
                    pie: { innerSize: 100,depth: 45 }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                series: [{
                    name: 'Cantidad',
                    data: arr_data
                }]
            });
        }
        function draw_chart_2() {
            var arr_series = [];
            arr_estados = [];
            var sel_fechas = $("#adFecha2").val();
            //1. prepara array con estados
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                var iestado = get_index_arr(arr_estados, dato.estado);
                if(iestado == -1) arr_estados.push(dato.estado);
            }
            //2. prepara estructura de la data
            var nEstados = arr_estados.length;
            for(var j = 0; j < nEstados; j++) {
                var data = [];
                var numberOfDates = arr_fechas.length;
                for(var k = 0; k < numberOfDates; k++) {
                    data[k] = 0;
                }
                arr_series.push({
                    name: arr_estados[j],
                    data: data
                });
            }
            //3. ingresa los datos
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                if(sel_fechas.indexOf(dato.fechavisita) > -1) {
                    var iest = get_index_obj(arr_series, dato.estado);
                    if(iest > -1) {
                        var idata = arr_series[iest].data;
                        var ifecha = arr_fechas.indexOf(dato.fechavisita);
                        if(ifecha > -1) {
                            arr_series[iest].data[ifecha] = arr_series[iest].data[ifecha] + parseInt(dato.cant);
                        }
                    }
                }
            }
            //4. dibuja grafico
            $('#chart-container-2').highcharts({
                chart: { type: 'column' },
                title: { text: 'Entregas por fecha' },
                subtitle: { text: 'Source: WorldClimate.com' },
                xAxis: { categories: arr_fechas },
                yAxis: { min: 0, title: { text: 'Cantidad' } },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y} unids</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: { column: { pointPadding: 0.2, borderWidth: 0 } },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                series: arr_series
            });
        }
        function draw_chart_3() {
            var arr_series = [];
            var sel_fechas = $("#adFecha3").val();
            //1. prepara array con sectores
            var arr_sectores = [];
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                var isector = get_index_arr(arr_sectores, dato.sector);
                if(isector == -1) arr_sectores.push(dato.sector);
            }
            //2. prepara estructura de la data
            var nEstados = arr_estados.length;
            for(var j = 0; j < nEstados; j++) {
                var data = [];
                var numberOfSectors = arr_sectores.length;
                for(var k = 0; k < numberOfSectors; k++) data[k] = 0;
                arr_series.push({
                    name: arr_estados[j],
                    data: data
                });
            }
            //3. ingresa los datos
            for(var i = 0; i < numberOfItems; i++) {
                var dato = dataset[i];
                if(sel_fechas.indexOf(dato.fechavisita) > -1) {
                    var iest = get_index_obj(arr_series, dato.estado);
                    if(iest > -1) {
                        var idata = arr_series[iest].data;
                        var isector = arr_sectores.indexOf(dato.sector);
                        if(isector > -1) {
                            arr_series[iest].data[isector] = arr_series[iest].data[isector] + parseInt(dato.cant);
                        }
                    }
                }
            }
            //4. dibuja grafico
            $('#chart-container-3').highcharts({
                chart: { type: 'bar' },
                title: { text: 'Entregas por sector' },
                subtitle: { text: 'Mostrando gráfico' },
                xAxis: {
                    categories: arr_sectores,
                    title: { text: null }
                },
                yAxis: {
                    min: 0,
                    title: { text: 'Cantidad (unidades)', align: 'high' },
                    labels: { overflow: 'justify' }
                },
                tooltip: { valueSuffix: ' unids.' },
                plotOptions: { bar: { dataLabels: { enabled: true } } },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                credits: { enabled: false },
                series: arr_series
            });
        }
        function btnBuscaOnClick(event) {
            event.preventDefault();
            $("#chart-container-1").empty();
            $("#chart-container-2").empty();
            $("#chart-container-3").empty();
            var p = {
                ccl: $("#adCiclo").val(),
                grn: [$("#tbGerencia").val()],
                str: [$("#tbSector").val()],
                cno: $("#adCNO").val(),
                _token: "{{ csrf_token() }}"
            };
            $.post("{{ url('ajax/indicadores/reclamos') }}", p, function(response) {
                if(response.success) {
                    dataset = response.data;
                    set_combos_fechas();
                    draw_chart_1();
                    draw_chart_2();
                    draw_chart_3();
                    $("#chart-div").fadeIn(150);
                }
                else {
                    document.getElementById("modal-error-msg").innerHTML = response.message;
                    $("#modal-error").modal("show");
                }
            }, "json");
        }
        function init() {
            $("#btn-busca").on("click", btnBuscaOnClick);
            $("#btRefreshCh1").on("click", draw_chart_1);
            $("#btRefreshCh2").on("click", draw_chart_2);
            $("#btRefreshCh3").on("click", draw_chart_3);
        }
        $(init);
    </script>
</body>

</html>