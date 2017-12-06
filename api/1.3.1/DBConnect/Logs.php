<?php
function AddLog($accion){
	$link = mysql_connect('127.0.0.1', 'extremen', 't5#Kpy(Q1i*98') or die('No se pudo conectar: ' . mysql_error());

	mysql_select_db('extremen_inventario') or die('No se pudo seleccionar la base de datos');
	$query = sprintf("SELECT * FROM Sesiones WHERE Token='%s' AND ;", safe($token));
	$result = mysql_query($query) or die('Consulta fallida');
	$res = mysql_fetch_array($result, MYSQL_ASSOC);
	if (count($res) == 1 )
		return 1;
	else
		return 0;
}
?>