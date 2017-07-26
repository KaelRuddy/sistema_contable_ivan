<?php
// API DE CUENTAS
require_once("../../conexion.php");
require_once '../../lib/rb.php';
R::setup('mysql:host=localhost;dbname='.DB_NAME,DB_USER,DB_PASS);

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header("Content-Type: application/json; charset=UTF-8");	// respuestas en formato JSON

switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["id"])){
			$respuesta["cuenta"]=R::load('cuentas',$_GET["id"]);
		}else{
			$respuesta["cuentas"]=R::findAll('cuentas');
		}
		print json_encode($respuesta);
		break;
	case 'POST':
		// guardar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$cuenta = R::dispense('cuentas');
			$cuenta->codigo=$data["codigo"];
			$cuenta->grupo=$data["grupo"];
			$cuenta->nombre_cta=$data["nombre_cta"];
			$cuenta->tipo=$data["tipo"];
			$cuenta->moneda=$data["moneda"];
			$id=R::store($cuenta);
			http_response_code(201);
			$cuenta=R::load('cuentas',$id);
			$respuesta["cuenta"]=$cuenta;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la cuenta";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
		break;
	case 'PUT':
		// actualizar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$cuenta = R::load('cuentas',$data["id"]);
			$cuenta->codigo=$data["codigo"];
			$cuenta->grupo=$data["grupo"];
			$cuenta->nombre_cta=$data["nombre_cta"];
			$cuenta->tipo=$data["tipo"];
			$cuenta->moneda=$data["moneda"];
			$id=R::store($cuenta);
			http_response_code(200);
			$cuenta=R::load('cuentas',$id);
			$respuesta["cuenta"]=$cuenta;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo actualizar la cuenta";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
		break;
	case 'HEAD':
		do_something_with_head($request);
		break;
	case 'DELETE':
		// actualizar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$cuenta = R::load('cuentas',$data["id"]);
			R::trash($cuenta);
			http_response_code(204);
			//$respuesta["mensaje"]="Cuenta ".$cuenta->id." eliminada.";
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo actualizar la cuenta";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
		break;
	case 'OPTIONS':
		do_something_with_options($request);
		break;
	default:
		handle_error($request);
		break;
}

?>
