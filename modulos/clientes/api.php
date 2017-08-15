<?php
require("../../conexion.php");

class Cliente{
	const TABLA="clientes";

	public $id=-1;	// ID entero
	public $nit="";	// NOMBRE cadena
	public $razon_social="";	// USUARIO
	public $direccion="";	// CI
	public $fono="";	// CI
	public $nombre_contacto="";	// CI
	public $cel="";	// CEL

	private $conexion=null;

	/**
	 * Constructor
	 * */
	//function construct() { La sintaxis para contructores en php es con dos guines bajos
	function __construct() {
		$this->id=-1;	// ID entero
		$this->nit="";	// NOMBRE cadena
		$this->razon_social="";	// CI
		$this->direccion="";	// USUARIO
		$this->fono="";	// USUARIO
		$this->nombre_contacto="";	// USUARIO
		$this->cel="";	// CEL
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	}

	/**
	 * Metodo que guarda un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function guardar(){
		if($this->id==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (nit,razon_social,direccion,fono,nombre_contacto,cel) values('".$this->nit."','".$this->razon_social."','".$this->direccion."','".$this->fono."','".$this->nombre_contacto."','".$this->cel."')";
		}else{// actualizar
			//$sql="update ".self::TABLA." set nit='".$this->nit."',razon_social='".$this->razon_Social."',direccion='".$this->direccion."',fono='".$this->fono."',nombre_contacto='".$this->nombre_contacto."',cel='".$this->cel."' where id='".$this->id."'";
			$sql="update ".self::TABLA." set nit='".$this->nit."',razon_social='".$this->razon_social."',direccion='".$this->direccion."',fono='".$this->fono."',nombre_contacto='".$this->nombre_contacto."',cel='".$this->cel."' where id='".$this->id."'";
		}
		if(mysqli_query($this->conexion,$sql)){
			if($this->id==-1){
				// nuevo
				$sql="select max(id) from ".self::TABLA;
				$result=mysqli_query($this->conexion,$sql);
				$fila=mysqli_fetch_array($result);
				$this->id=$fila[0];
			}
			return self::getClienteDeId($this->id);
		}
		return null;
	}

	/**
	 * Metodo que elimina un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function eliminar(){
		$sql="delete from ".self::TABLA." where ID='".$this->id."'";
		mysqli_query($this->conexion,$sql);
		return null;
	}

	/**
	 * Obtienen todos los registros de la tabla usuario
	 * */
	public static function lista(){
		$cliente=new self();
		$lista=array();	// vector de usuarios
		$sql="select * from ".self::TABLA." order by nit"; //lo agregrue yo
		//$result=mysqli_query($cliente->conexion,"select * from ".self::TABLA); // select * from id_cont
		$result=mysqli_query($cliente->conexion,$sql);
		//echo $cliente->conexion;
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		return $lista;
	}

	/**
	 * Obtienen el objeto usuario de id, null en caso de que no exista
	 * */
	public static function getClienteDeId($id){
		$cliente=new self();
		$sql="select * from ".self::TABLA." where id ='$id'";
		$result=mysqli_query($cliente->conexion,$sql); // select * from usuario_cont
		if($fila=mysqli_fetch_array($result)) {
			return self::mapper($fila);	// guardamos a cada usuario en el vector
		}
		return null;
	}

	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		$miCliente=new self();	// creamos un usuario con datos vacios
		$miCliente->id=$fila["id"];	// los campos de la tabla
		$miCliente->nit=$fila["nit"];
		$miCliente->razon_social=$fila["razon_social"];
		$miCliente->direccion=$fila["direccion"];
		$miCliente->fono=$fila["fono"];
		$miCliente->nombre_contacto=$fila["nombre_contacto"];
		$miCliente->cel=$fila["cel"];
		return $miCliente;
	}

	/**
	 * Función que convierte en Json este objeto
	 * */
	public function toJSON(){
		return json_encode($this);
	}
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
	case 'GET':
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($_GET["id"])){
			$cliente=Cliente::getClienteDeId($_GET["id"]);
			print json_encode($cliente);
		}else{
			print json_encode(Cliente::lista());
		}
		break;
	case 'POST':
		// guardar
		$data = json_decode(file_get_contents('php://input'), true);
		$cliente=new Cliente();
		$cliente->nit=$data["nit"];
		$cliente->razon_social=strtoupper($data["razon_social"]);
		$cliente->direccion=$data["direccion"];
		$cliente->fono=strtoupper($data["fono"]);
		$cliente->nombre_contacto=strtoupper($data["nombre_contacto"]);
		$cliente->cel=strtoupper($data["cel"]);
		if ($cliente->guardar()){
			http_response_code(201);
			print json_encode($cliente);
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
		$cliente=Cliente::getClienteDeId($data["id"]);
		$cliente->nit=$data["nit"];
		$cliente->razon_social=strtoupper($data["razon_social"]);
		$cliente->direccion=strtoupper($data["direccion"]);
		$cliente->fono=strtoupper($data["fono"]);
		$cliente->nombre_contacto=$data["nombre_contacto"];
		$cliente->cel=strtoupper($data["cel"]);
		if ($cliente->guardar()){
			http_response_code(200);
			print json_encode($cliente);
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
		$cliente=Cliente::getClienteDeId($data["id"]);
		$cliente->eliminar();
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
