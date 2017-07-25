<?php
require 'Cuenta.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["CODIGO_CTA"])){
			$cuenta=Cuenta::getCuentaDeCODIGO_CTA($_GET["CODIGO_CTA"]);
			print json_encode($cuenta);
		}else{
			print json_encode(Cuenta::lista());
		}
		break;
	case 'POST':
		// guardar
		$data = json_decode(file_get_contents('php://input'), true);
		$cuenta=new Cuenta();
		$cuenta->CODIGO=$data["CODIGO"];
		$cuenta->GRUPO=$data["GRUPO"];
		$cuenta->NOMBRE_CTA=$data["NOMBRE_CTA"];
		$cuenta->TIPO=$data["TIPO"];
		$cuenta->MONEDA=$data["MONEDA"];
		if ($cuenta->guardar()){
			http_response_code(201);
			print json_encode($cuenta);
		}else{
			http_response_code(405);
			$respuesta=array();
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la cuenta";
			print json_encode($respuesta);
		}
		break;
	case 'PUT':
		// actualizar
		$data = json_decode(file_get_contents('php://input'), true);
		$cuenta=Cuenta::getCuentaDeCODIGO_CTA($data["CODIGO_CTA"]);
		$cuenta->CODIGO=$data["CODIGO"];
		$cuenta->GRUPO=$data["GRUPO"];
		$cuenta->NOMBRE_CTA=$data["NOMBRE_CTA"];
		$cuenta->TIPO=$data["TIPO"];
		$cuenta->MONEDA=$data["MONEDA"];
		if ($cuenta->guardar()){
			http_response_code(200);
			print json_encode($cuenta);
		}else{
			http_response_code(405);
			$respuesta=array();
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la cuenta";
			print json_encode($respuesta);
		}
		break;
	case 'HEAD':
		do_something_with_head($request);
		break;
	case 'DELETE':
		$data = json_decode(file_get_contents('php://input'), true);
		$cuenta=Cuenta::getCuentaDeCODIGO_CTA($data["CODIGO_CTA"]);
		$cuenta->eliminar();
		http_response_code(200);
		break;
	case 'OPTIONS':
		do_something_with_options($request);
		break;
	default:
		handle_error($request);
		break;
}

?>
