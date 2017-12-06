<?php
$body = @file_get_contents('php://input');
$data = json_decode($body);
http_response_code(200); // Return 200 OK

if ($data->type == 'charge.paid'){
	$url = 'http://vag.mx/api/interfaz/oxxo-pago-realizado/?idTransaccion='.$data->data->object->order_id.'&tipo='.$data->data->object->payment_method->type;
	file_get_contents($url);
}

?>