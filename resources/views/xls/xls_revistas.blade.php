<html>
<tr>
	<td style="font-size:14pt;font-family:Verdana;color:#b71c1c;font-style:italic;font-weight:bold;" colspan="4">Mis revistas</td>
</tr>
<tr>
	<td style="color:#ff6f00;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Ciclos</td>
	<td colspan="3" style="color:#202020;font-size:10pt;">{{ implode(", ", $ciclos) }}</td>
</tr>
<tr>
	<td style="color:#ff6f00;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">CNOs</td>
	<td colspan="3" style="color:#202020;font-size:10pt;">{{ implode(", ", $cnos) }}</td>
</tr>
<tr>
	<td style="color:#ff6f00;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Gerencias</td>
	<td colspan="3" style="color:#202020;font-size:10pt;">{{ implode(", ", $gerencias) }}</td>
</tr>
<tr>
	<td style="color:#ff6f00;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Sectores</td>
	<td colspan="3" style="color:#202020;font-size:10pt;">{{ implode(", ", $sectores) }}</td>
</tr>
<tr>
	<td style="color:#ff6f00;font-size:10pt;font-weight:bold;font-family:Tahoma;font-weight:bold;">Código</td>
	<td colspan="3" style="color:#202020;font-size:10pt;">{{ strcmp($codigo,"") == 0 ? "-" : $codigo }}</td>
</tr>
<tr>
	<td colspan="4" style="color:#ffffff;"></td>
</tr>
<tr>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Consultora</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Ciclo</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Situación</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Auditoría</td>
	<td style="border:1px solid #404040;font-family:Tahoma;font-size:10pt;background:#ff6f00;color:#ffffff;font-weight:bold;">Reclamo</td>
</tr>
@foreach($filas as $i => $fila)
<tr>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->consultora }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->ciclo }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:left;">{{ $fila->situacion }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:center;">{{ $fila->auditoria }}</td>
	<td style="background:{{ $i % 2 == 0 ? '#f8f8f8' : '#e8e8e8' }};border:1px solid #404040;font-family:Tahoma;font-size:10pt;color:#404040;text-align:center;">{{ $fila->ereclamo == "0" ? "No" : "Si" }}</td>
</tr>
@endforeach
</html>