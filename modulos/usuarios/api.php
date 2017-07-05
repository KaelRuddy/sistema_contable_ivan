<?php
include 'Usuario.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
	case 'GET':
		// listar
		print json_encode(Usuario::lista());
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
		$usuario->tipo=$data["tipo"];
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
		do_something_with_put($request);
		break;
	case 'HEAD':
		do_something_with_head($request);
		break;
	case 'DELETE':
		do_something_with_delete($request);
		break;
	case 'OPTIONS':
		do_something_with_options($request);
		break;
	default:
		handle_error($request);
		break;
}

?>
