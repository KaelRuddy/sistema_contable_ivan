<?php
require_once("../../conexion.php");
require_once '../../lib/rb.php';
R::setup('mysql:host=localhost;dbname='.DB_NAME,DB_USER,DB_PASS);
const TABLA="transaccion";

// aca se define este api

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header("Content-Type: application/json; charset=UTF-8");

switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["id"])){
			$comprobante=Comprobante::getPorId($_GET["id"]);
			print json_encode($comprobante);
		}else{
			$cuentas = R::findAll('cuentas');
			$result["cuentas"]=$cuentas;
			$result["tipos_transaccion"]=R::findAll('tipo_trans');
			//print_r($comprobantes);
			print json_encode($result);
		}
		break;
	case 'POST':
		// guardar
		$data = json_decode(file_get_contents('php://input'), true);
		$usuario=new Usuario();
		$usuario->nombre=$data["nombre"];
		$usuario->ci=$data["ci"];
		$usuario->cel=$data["cel"];
		$usuario->usuario=$data["usuario"];
		$usuario->pass=$data["password"];
		if ($usuario->guardar()){
			http_response_code(201);
			print json_encode($usuario);
		}else{
			http_response_code(405);
			$respuesta=array();
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar el usuario";
			print json_encode($respuesta);
		}
		break;
	case 'PUT':
		// actualizar
		$data = json_decode(file_get_contents('php://input'), true);
		$usuario=Usuario::getUsuarioDeId($data["id"]);
		$usuario->nombre=$data["nombre"];
		$usuario->ci=$data["ci"];
		$usuario->cel=$data["cel"];
		$usuario->usuario=$data["usuario"];
		$usuario->pass=$data["password"];
		$usuario->tipo=$data["tipo"];
		if ($usuario->guardar()){
			http_response_code(200);
			print json_encode($usuario);
		}else{
			http_response_code(405);
			$respuesta=array();
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar el usuario";
			print json_encode($respuesta);
		}
		break;
		break;
	case 'HEAD':
		do_something_with_head($request);
		break;
	case 'DELETE':
		$data = json_decode(file_get_contents('php://input'), true);
		$usuario=Usuario::getUsuarioDeId($data["id"]);
		$usuario->eliminar();
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
