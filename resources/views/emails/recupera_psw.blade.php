<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="margin:0;padding:0;">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
	<table style="width:100%;margin:0;padding:0;">
		<tr>
			<th style="vertical-align:middle;background-image:url('{{ url("asset/img/bg-natura.jpg") }}');background-position:center 85%;height:150px;background-size:100% auto;">
				<h1 style="color:#f57c00;text-shadow:1px 1px 1px #404040;font-family:'Open Sans';font-weight:normal;font-size:40px;">Restablecer contraseña</h1>
			</th>
		</tr>
		<tr>
			<td style="padding:25px 50px;">
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;">Se ha recibido una solicitud de restablecimiento de la contraseña de su cuenta. Para configurar una nueva contraseña, pulse el siguiente enlace.</p>
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;"><a href="{{ url('generaclave', [$user, $token]) }}" target="_blank">{{ url('generaclave', [$user, $token]) }}</a></p>
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;"></p>
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;">O, si así lo prefiere, puede copiar y pegar el mismo enlace en su navegador.</p>
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;"><br/><br/></p>
				<p style="font-family:'Open Sans';font-size:16px;color:#808080;font-weight:bold;">¡Que tenga buen día!</p>
			</td>
		</tr>
	</table>
</body>
</html>