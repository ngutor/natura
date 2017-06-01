<!DOCTYPE html>
<html>
<head>
	<title>Contraseña almacenada</title>
</head>
<body style="margin:0;padding:0;">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
	<table style="width:100%;margin:0;padding:0;">
		<tr>
			<th style="vertical-align:middle;background-image:url('{{ url("asset/img/bg-natura.jpg") }}');background-position:center 85%;height:150px;background-size:100% auto;">
				<h1 style="color:#f57c00;text-shadow:1px 1px 1px #404040;font-family:'Open Sans';font-weight:normal;font-size:40px;">Se reestableció la contraseña</h1>
			</th>
		</tr>
		<tr>
			<td style="padding:25px 50px;">
				<p style="text-align:center;width:100%;"><img src="{{ asset('asset/img/confirm.png') }}" style="margin:10px auto;height:144px;"></p>
				<p style="text-align:center;font-family:'Open Sans';font-size:18px;color:#808080;">Su nueva contraseña ha sido configurada correctamente</p>
				<p style="text-align:center;font-family:'Open Sans';font-size:16px;color:#808080;"></p>
				<p style="text-align:center;font-family:'Open Sans';font-size:16px;color:#808080;">Pulse el siguiente <a href="{{ url('login') }}">enlace</a> para ir a la pantalla de login.</p>
			</td>
		</tr>
	</table>
</body>
</html>