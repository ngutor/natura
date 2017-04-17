<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingreso al sistema</title>
    <!-- Bootstrap -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,700i" rel="stylesheet">
    <link href="asset/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="asset/css/font-awesome.min.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-login">
    <div class="bg-natura">
        <img src="{{ url('asset/img/bg-natura.jpg') }}" alt="">
    </div>
    <div id="main">
        <div class="cnt-login">
            <div class="fluid-list logo-l center">
                <img src="{{ asset('asset/img/logo-natura.png') }}">
            </div>
            <div class="fluid-list cnt-form-l">
                <form id="form-login">
                    <div class="form-group one-form">
                        <div class="input-group">
                            <span class="input-group-addon addon-n"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input type="text" class="form-control disabled" id="name" placeholder="Nombre de Usuario">
                        </div>
                    </div>
                    <div class="form-group two-form">
                        <div class="input-group">
                            <span class="input-group-addon addon-n"><i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
                            <input type="password" class="form-control disabled" id="pass" placeholder="Contraseña">
                        </div>
                    </div>
                    <input type="hidden" id="token" value="{{ csrf_token() }}" />
                    <button id="btn-login" type="submit" class="btn btn-login">Entrar</button>
                </form>
            </div>
            <div class="fluid-list center">
                <a href="#" class="link-a">Recuperar contraseña</a>
            </div>
        </div>
    </div>
    <!-- -->
    <div class="modal fade" id="modal-div" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Operación fallida</h4>
                </div>
                <div class="modal-body">
                    <p id="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script type="text/javascript">
        function postLogin(response) {
            if(response.success) {
                location.href = "{{ url('/') }}";
            }
            else {
                document.getElementById("modal-msg").innerHTML = response.message;
                $("#modal-div").modal("show");
            }
            $("#btn-login").removeAttr("disabled");
        }
        function formLoginOnSubmit(event) {
            event.preventDefault();
            $("#btn-login").attr("disabled", true);
            var p = {usr:document.getElementById("name").value,psw:document.getElementById("pass").value,_token:document.getElementById("token").value};
            if(p.usr != "" && p.psw != "") $.post("{{ url('ajax/login') }}", p, postLogin, "json");
            else {
                document.getElementById("modal-msg").innerHTML = "Los campos 'Nombre de Usuario' y 'Contraseña' son obligatorios.";
                $("#modal-div").modal("show");
                $("#btn-login").removeAttr("disabled");
            }
        }
        $("#form-login").on("submit", formLoginOnSubmit);
    </script>
</body>
</html>
