<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - Mis indicadores</title>
    @include("common.head")
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/bootstrap-multiselect.css') }}">
</head>

<body>
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
                                <select id="adCiclo" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adGerencia" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adSector" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adCNO" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <select id="adSituacion" class="form-control" multiple="multiple"></select>
                            </li>
                            <li>
                                <button class="btn btn-search">Buscar</button>
                            </li>
                        </ul>
                    </div>
                    <div class="fluid-list cnt-graficas-n">
                        <p>Graficas</p>
                    </div>
                    <div class="fluid-list cnt-table-f">
                        <div class="fluid-list b-list-pag">
                            <div class="left">
                                <ul class="reset-ul">
                                    <li><a href="#" class="e-excel"><span></span> Exportar a Excel</a></li>
                                    <li><a href="#" class="e-mail" data-toggle="modal" data-target="#tModalE"><span></span> Enviar a mail</a></li>
                                </ul>
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
                    </div>
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
    <script src="{{ url('asset/js/jquery.min.js') }}"></script>
    <script src="{{ url('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('asset/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ url('asset/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ url('asset/js/main.js?v2') }}"></script>
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
        function postOnLoad(response) {
            if(response.success) {
                populate_combo("#adCiclo", response.ciclos);
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
