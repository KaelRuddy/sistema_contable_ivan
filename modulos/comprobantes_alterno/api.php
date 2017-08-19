<?php
require_once("../../conexion.php");
require_once '../../lib/rb.php';
R::setup('mysql:host=localhost;dbname='.DB_NAME,DB_USER,DB_PASS);
const T_1transacciones="1transacciones";
const T_tipos_1transaccion="tipo_trans";
const T_cuentas="cuentas";
const T_1operaciones="1operaciones";

// aca se define este api

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header("Content-Type: application/json; charset=UTF-8");


switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["1transaccion"])){
			$1transaccion=R::findOne( T_1transacciones, ' id = ? ', [ $_GET["1transaccion"] ] );
			if(!is_null($1transaccion)){
				$1transaccion->tipo_1transaccion=R::findOne( T_tipos_1transaccion, ' id = ? ', [ $1transaccion->fk_tipo_1transaccion ] );
				unset($1transaccion->fk_tipo_1transaccion);	// borramos este campo inutil
				$1operaciones=R::find( T_1operaciones, ' fk_1transaccion = ? ', [ $1transaccion->id ] );
				$1operaciones_result=[];
				foreach ($1operaciones as $1operacion){
					$1operacion->cuenta=R::findOne( T_cuentas, ' id = ? ', [ $1operacion->fk_cuenta ] );
					unset($1operacion->fk_cuenta);	// borramos este campo inutil
					array_push($1operaciones_result,$1operacion);
				}
				$1transaccion->1operaciones=$1operaciones_result;
				$result["1transaccion"]=$1transaccion;
			}else{
				http_response_code(404);
				$result["codigo"]=404;
				$result["mensaje"]="No se encontro la 1transaccion de id: ".$_GET["1transaccion"];
			}
		}else{
			if(isset($_GET["sig_nro_tipo_comprobante"])){
				$sig_nro_comprobante=$_GET["sig_nro_tipo_comprobante"];
				$result["sig_nro_tipo_comprobante"]=R::getCell('SELECT max(nro_tipo_comprobante) FROM '.T_1transacciones.' WHERE fk_tipo_1transaccion = ?',[$sig_nro_comprobante])+1;
			}else{
				$cuentas = R::findAll(T_cuentas, 'tipo=?', ["S"]);
				$result["cuentas"]=$cuentas;
				$result["tipos_1transaccion"]=R::find(T_tipos_1transaccion);
				$result["sig_nro_comprobante"]=R::getCell('SELECT max(nro_comprobante) FROM '.T_1transacciones)+1;
				$result["1transacciones"]=R::findAll(T_1transacciones);
			}
		}
		print json_encode($result);
		break;
	case 'POST':
		// guardar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$1transaccion=R::dispense(T_1transacciones);
			$1transaccion->nro_comprobante=$data["nro_comprobante"];
			$1transaccion->nro_tipo_comprobante=$data["nro_tipo_comprobante"];
			$1transaccion->fk_tipo_1transaccion=$data["tipo_1transaccion"]["id"];
			$1transaccion->glosa=$data["glosa"];
			$1transaccion->fecha=$data["fecha"];
			$id_1transaccion=R::store($1transaccion);
			$1operaciones=$data["1operaciones"];
			foreach ($1operaciones as $ope){
				$1operacion=R::dispense(T_1operaciones);
				$1operacion->fk_cuenta=$ope["cuenta"]["id"];
				//$1operacion->descripcion=$ope["descripcion"];
				$1operacion->debe=$ope["debe"];
				$1operacion->haber=$ope["haber"];
				$1operacion->fk_1transaccion=$id_1transaccion;
				$id=R::store($1operacion);
			}
			http_response_code(201);
			$1transaccion=R::load(T_1transacciones,$id_1transaccion);
			$1transaccion->1operaciones=R::find( T_1operaciones, ' fk_1transaccion = ? ', [ $id_1transaccion ] );
			$respuesta["1transaccion"]=$1transaccion;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la 1transaccion";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
		break;
	case 'PUT':
		// actualizar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$1transaccion=R::findOne(T_1transacciones, ' id = ? ',[$data["id"]]);
			$1transaccion->nro_comprobante=$data["nro_comprobante"];
			$1transaccion->nro_tipo_comprobante=$data["nro_tipo_comprobante"];
			$1transaccion->fk_tipo_1transaccion=$data["tipo_1transaccion"]["id"];
			$1transaccion->glosa=$data["glosa"];
			//$1transaccion->fecha=$data["fecha"];
			$id_1transaccion=R::store($1transaccion);
			$1operaciones=$data["1operaciones"];
			$1operacionesantiguas=R::find( T_1operaciones, ' fk_1transaccion = ? ', [ $id_1transaccion ] );
			foreach ($1operaciones as $ope){
				if($ope["id"]==null)
					$1operacion=R::dispense(T_1operaciones);
				else
					$1operacion=R::findOne(T_1operaciones, ' id=? ',[$ope["id"]]);
				$1operacion->fk_cuenta=$ope["cuenta"]["id"];
				//$1operacion->descripcion=$ope["descripcion"];
				$1operacion->debe=$ope["debe"];
				$1operacion->haber=$ope["haber"];
				$1operacion->fk_1transaccion=$id_1transaccion;
				$id=R::store($1operacion);
			}
			//Borrando 1operaciones antiguas
			foreach ($1operacionesantiguas as $ope){
				$1operacion=R::findOne(T_1operaciones, ' id=? ',[$ope["id"]]);
				R::trash($1operacion);
			}
			http_response_code(200);
			$1transaccion=R::load(T_1transacciones,$id_1transaccion);
			$1transaccion->1operaciones=R::find( T_1operaciones, ' fk_1transaccion = ? ', [ $id_1transaccion ] );
			$respuesta["1transaccion"]=$1transaccion;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la 1transaccion";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
// 		$data = json_decode(file_get_contents('php://input'), true);
// 		$usuario=Usuario::getUsuarioDeId($data["id"]);
// 		$usuario->nombre=$data["nombre"];
// 		$usuario->ci=$data["ci"];
// 		$usuario->cel=$data["cel"];
// 		$usuario->usuario=$data["usuario"];
// 		$usuario->pass=$data["password"];
// 		$usuario->tipo=$data["tipo"];
// 		if ($usuario->guardar()){
// 			http_response_code(200);
// 			print json_encode($usuario);
// 		}else{
// 			http_response_code(405);
// 			$respuesta=array();
// 			$respuesta["codigo"]=405;
// 			$respuesta["mensaje"]="No se pudo guardar el usuario";
// 			print json_encode($respuesta);
// 		}
// 		break;
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
