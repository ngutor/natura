<html>
<tr>
	<td style="font-size:14pt;font-family:Verdana;color:#b71c1c;font-style:italic;font-weight:bold;" colspan="3">Detalle del Reclamo</td>
</tr>
<tr>
	<td style="color:#0d47a1;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Ciclo</td>
	<td style="color:#202020;font-size:10pt;text-align:left;">{{ $ciclo }}</td>
</tr>
<tr>
	<td style="color:#0d47a1;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Fecha</td>
	<td style="color:#202020;font-size:10pt;text-align:left;">{{ date("d-m-Y") }}</td>
</tr>
<tr>
	<td style="color:#0d47a1;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Cod. Consultora</td>
	<td style="color:#202020;font-size:10pt;text-align:left;">{{ $fecha }}</td>
</tr>
<tr>
	<td style="color:#0d47a1;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Est. Inicial</td>
	<td style="color:#202020;font-size:10pt;text-align:left;">{{ $eini }}</td>
</tr>
<tr>
	<td style="color:#0d47a1;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Est. Final</td>
	<td style="color:#202020;font-size:10pt;text-align:left;">{{ $efin }}</td>
</tr>
<tr>
	<td colspan="2" style="color:#ffffff;"></td>
</tr>
<tr>
	<td colspan="2" style="color:#ff6f00;font-weight:bold;font-size:12pt;font-style:italic;">Datos básicos</td>
</tr>
<tr>
	<td style="font-family:Tahoma;font-size:10pt;color:#202020;font-weight:bold;">Nombre</td>
	<td colspan="3" style="font-family:Tahoma;font-size:10pt;color:#202020;">{{ $binfo->nombre }}</td>
</tr>
<tr>
	<td style="font-family:Tahoma;font-size:10pt;color:#202020;font-weight:bold;">Inactividad</td>
	<td colspan="3" style="font-family:Tahoma;font-size:10pt;color:#202020;">{{ $binfo->situac }}</td>
</tr>
<tr>
	<td style="font-family:Tahoma;font-size:10pt;color:#202020;font-weight:bold;">Dirección</td>
	<td colspan="3" style="font-family:Tahoma;font-size:10pt;color:#202020;">{{ $binfo->direccion }}</td>
</tr>
<tr>
	<td style="font-family:Tahoma;font-size:10pt;color:#202020;font-weight:bold;">Distrito</td>
	<td colspan="3" style="font-family:Tahoma;font-size:10pt;color:#202020;">{{ $binfo->distrito }}</td>
</tr>
<tr>
	<td style="font-family:Tahoma;font-size:10pt;color:#202020;font-weight:bold;">Teléfono</td>
	<td colspan="2" style="font-family:Tahoma;font-size:10pt;color:#202020;">{{ $binfo->telefono }}</td>
</tr>
<tr>
	<td colspan="2" style="color:#ffffff;"></td>
</tr>
<tr>
	<td colspan="2" style="color:#ff6f00;font-weight:bold;font-size:12pt;font-style:italic;">Tracking del envío</td>
</tr>
<tr>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Fecha</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Estado</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Observación</td>
</tr>
@foreach($tracking as $i => $fila)
<tr>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->fecha }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->estado }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->observ }}</td>
</tr>
@endforeach
</html>