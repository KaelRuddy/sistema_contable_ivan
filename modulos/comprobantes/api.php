<?php
require_once("../../conexion.php");
require_once '../../lib/rb.php';
R::setup('mysql:host=localhost;dbname='.DB_NAME,DB_USER,DB_PASS);
const T_transacciones="transacciones";
const T_tipos_transaccion="tipo_trans";
const T_cuentas="cuentas";
const T_operaciones="operaciones";

// aca se define este api

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
header("Content-Type: application/json; charset=UTF-8");


switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["transaccion"])){
			$transaccion=R::findOne( T_transacciones, ' id = ? ', [ $_GET["transaccion"] ] );
			if(!is_null($transaccion)){
				$transaccion->tipo_transaccion=R::findOne( T_tipos_transaccion, ' id = ? ', [ $transaccion->fk_tipo_transaccion ] );
				unset($transaccion->fk_tipo_transaccion);	// borramos este campo inutil
				$operaciones=R::find( T_operaciones, ' fk_transaccion = ? ', [ $transaccion->id ] );
				$operaciones_result=[];
				foreach ($operaciones as $operacion){
					$operacion->cuenta=R::findOne( T_cuentas, ' id = ? ', [ $operacion->fk_cuenta ] );
					unset($operacion->fk_cuenta);	// borramos este campo inutil
					array_push($operaciones_result,$operacion);
				}
				$transaccion->operaciones=$operaciones_result;
				$result["transaccion"]=$transaccion;
			}else{
				http_response_code(404);
				$result["codigo"]=404;
				$result["mensaje"]="No se encontro la transaccion de id: ".$_GET["transaccion"];
			}
		}else{
			if(isset($_GET["sig_nro_tipo_comprobante"])){
				$sig_nro_comprobante=$_GET["sig_nro_tipo_comprobante"];
				$result["sig_nro_tipo_comprobante"]=R::getCell('SELECT max(nro_tipo_comprobante) FROM '.T_transacciones.' WHERE fk_tipo_transaccion = ?',[$sig_nro_comprobante])+1;
			}else{
				$cuentas = R::findAll(T_cuentas, 'tipo=?', ["S"]);
				$result["cuentas"]=$cuentas;
				$result["tipos_transaccion"]=R::find(T_tipos_transaccion);
				$result["sig_nro_comprobante"]=R::getCell('SELECT max(nro_comprobante) FROM '.T_transacciones)+1;
				$result["transacciones"]=R::findAll(T_transacciones);
			}
		}
		print json_encode($result);
		break;
	case 'POST':
		// guardar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$transaccion=R::dispense(T_transacciones);
			$transaccion->nro_comprobante=$data["nro_comprobante"];
			$transaccion->nro_tipo_comprobante=$data["nro_tipo_comprobante"];
			$transaccion->fk_tipo_transaccion=$data["tipo_transaccion"]["id"];
			$transaccion->glosa=$data["glosa"];
			$transaccion->fecha=$data["fecha"];
			$id_transaccion=R::store($transaccion);
			$operaciones=$data["operaciones"];
			foreach ($operaciones as $ope){
				$operacion=R::dispense(T_operaciones);
				$operacion->fk_cuenta=$ope["cuenta"]["id"];
				//$operacion->descripcion=$ope["descripcion"];
				$operacion->debe=$ope["debe"];
				$operacion->haber=$ope["haber"];
				$operacion->fk_transaccion=$id_transaccion;
				$id=R::store($operacion);
			}
			http_response_code(201);
			$transaccion=R::load(T_transacciones,$id_transaccion);
			$transaccion->operaciones=R::find( T_operaciones, ' fk_transaccion = ? ', [ $id_transaccion ] );
			$respuesta["transaccion"]=$transaccion;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la transaccion";
			$respuesta["error"]=$e;
		}finally{
			print json_encode($respuesta);
		}
		break;
	case 'PUT':
		// actualizar
		try{
			$data = json_decode(file_get_contents('php://input'), true);
			$transaccion=R::findOne(T_transacciones, ' id = ? ',[$data["id"]]);
			$transaccion->nro_comprobante=$data["nro_comprobante"];
			$transaccion->nro_tipo_comprobante=$data["nro_tipo_comprobante"];
			$transaccion->fk_tipo_transaccion=$data["tipo_transaccion"]["id"];
			$transaccion->glosa=$data["glosa"];
			//$transaccion->fecha=$data["fecha"];
			$id_transaccion=R::store($transaccion);
			$operaciones=$data["operaciones"];
			foreach ($operaciones as $ope){
				if($ope["id"]==null)
					$operacion=R::dispense(T_operaciones);
				else
					$operacion=R::findOne(T_operaciones, ' id=? ',[$ope["id"]]);
				$operacion->fk_cuenta=$ope["cuenta"]["id"];
				//$operacion->descripcion=$ope["descripcion"];
				$operacion->debe=$ope["debe"];
				$operacion->haber=$ope["haber"];
				$operacion->fk_transaccion=$id_transaccion;
				$id=R::store($operacion);
			}
			http_response_code(200);
			$transaccion=R::load(T_transacciones,$id_transaccion);
			$transaccion->operaciones=R::find( T_operaciones, ' fk_transaccion = ? ', [ $id_transaccion ] );
			$respuesta["transaccion"]=$transaccion;
		} catch (Exception $e) {
			http_response_code(405);
			$respuesta["codigo"]=405;
			$respuesta["mensaje"]="No se pudo guardar la transaccion";
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
