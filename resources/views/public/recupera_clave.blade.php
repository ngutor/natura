<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña</title>
    <!-- Bootstrap -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-login">
    <div class="bg-natura">
        <img src="{{ asset('asset/img/bg-natura.jpg') }}" alt="">
    </div>
    <div id="main">
        <div class="cnt-login">
            <div class="fluid-list logo-l center">
                <img src="{{ asset('asset/img/logo-natura.png') }}">
            </div>
            <div class="fluid-list cnt-inf-c">
                <p>Ingresa tu usuario o e-mail. Luego de verificarlos, te anviaremos un mensaje a tu dirección de correo electrónico</p>
            </div>
            <div class="fluid-list cnt-form-l">
                <form class="form-recup" action="{{ url('recupera') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon addon-n"><i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Ingresar usuario o correo">
                        </div>
                    </div>
                    <br/><br/>
                    <button type="submit" class="btn btn-login">RECUPERAR CONTRASEÑA</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset/js/main.js') }}"></script>
    @if(isset($err))
    <div class="modal fade" id="modal-div" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Usuario encontrado</h4>
                </div>
                <div class="modal-body">
                    <p id="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById("modal-msg").innerHTML = "{{ $err }}";
        $("#modal-div").modal("show");
    </script>
    @endif
</body>
</html>
