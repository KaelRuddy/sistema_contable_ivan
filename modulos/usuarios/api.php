<?php
require 'Usuario.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header("Content-Type: application/json; charset=UTF-8");

switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["id"])){
			$usuario=Usuario::getUsuarioDeId($_GET["id"]);
			print json_encode($usuario);
		}else{
			print json_encode(Usuario::lista());
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
