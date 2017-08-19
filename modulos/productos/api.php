<?php
require("../../conexion.php");

class Producto{
	const TABLA="productos";
	
	public $id=-1;	// ID entero
	public $codigo_prod="";	// NOMBRE cadena
	public $producto="";	// USUARIO
	public $marca="";	// CI
	public $medida="";	// CI
	public $modelo="";	// CI
	public $descripcion="";	// CEL
	public $precio1="";	// USUARIO
	public $precio2="";	// USUARIO
	public $precio3="";	// USUARIO
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		$this->id=-1;	// ID entero
		$this->codigo_prod="";	// NOMBRE cadena
		$this->producto="";	// CI
		$this->marca="";	// USUARIO
		$this->medida="";	// USUARIO
		$this->modelo="";	// USUARIO
		$this->descripcion="";	// CEL
		$this->precio1="";	// USUARIO
		$this->precio2="";	// USUARIO
		$this->precio3="";	// USUARIO
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	}
	
	/**
	 * Metodo que guarda un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function guardar(){
		if($this->id==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (codigo_prod,producto,marca,medida,modelo,descripcion,precio1,precio2,precio3) values('".$this->codigo_prod."','".$this->producto."','".$this->marca."','".$this->medida."','".$this->modelo."','".$this->descripcion."','".$this->precio1."','".$this->precio2."','".$this->precio3."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set codigo_prod='".$this->codigo_prod."',producto='".$this->producto."',marca='".$this->marca."',medida='".$this->medida."',modelo='".$this->modelo."',descripcion='".$this->descripcion."',precio1='".$this->precio1."',precio2='".$this->precio2."',precio3='".$this->precio3."' where id='".$this->id."'";
		}
		if(mysqli_query($this->conexion,$sql)){
			if($this->id==-1){
				// nuevo
				$sql="select max(id) from ".self::TABLA;
				$result=mysqli_query($this->conexion,$sql);
				$fila=mysqli_fetch_array($result);
				$this->id=$fila[0];
			}
			return self::getProductoDeId($this->id);
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
		$producto=new self();
		$lista=array();	// vector de usuarios
		$sql="select * from ".self::TABLA." order by codigo_prod"; //lo agregrue yo
		//$result=mysqli_query($producto->conexion,"select * from ".self::TABLA); // select * from id_cont
		$result=mysqli_query($producto->conexion,$sql);
		//echo $producto->conexion;
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		return $lista;
	}
	
	/**
	 * Obtienen el objeto usuario de id, null en caso de que no exista
	 * */
	public static function getProductoDeId($id){
		$producto=new self();
		$sql="select * from ".self::TABLA." where id ='$id'";
		$result=mysqli_query($producto->conexion,$sql); // select * from usuario_cont
		if($fila=mysqli_fetch_array($result)) {
			return self::mapper($fila);	// guardamos a cada usuario en el vector
		}
		return null;
	}
	
	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		$miProducto=new self();	// creamos un usuario con datos vacios
		$miProducto->id=$fila["id"];	// los campos de la tabla
		$miProducto->codigo_prod=$fila["codigo_prod"];
		$miProducto->producto=$fila["producto"];
		$miProducto->marca=$fila["marca"];
		$miProducto->medida=$fila["medida"];
		$miProducto->modelo=$fila["modelo"];
		$miProducto->descripcion=$fila["descripcion"];
		$miProducto->precio1=$fila["precio1"];
		$miProducto->precio2=$fila["precio2"];
		$miProducto->precio3=$fila["precio3"];
		return $miProducto;
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
			$producto=Producto::getProductoDeId($_GET["id"]);
			print json_encode($producto);
		}else{
			print json_encode(Producto::lista());
		}
		break;
	case 'POST':
		// guardar
		$data = json_decode(file_get_contents('php://input'), true);
		$producto=new Producto();
		$producto->codigo_prod=$data["codigo_prod"];
		$producto->producto=strtoupper($data["producto"]);
		$producto->marca=$data["marca"];
		$producto->medida=strtoupper($data["medida"]);
		$producto->modelo=strtoupper($data["modelo"]);
		$producto->descripcion=strtoupper($data["descripcion"]);
		$producto->precio1=$data["precio1"];
		$producto->precio2=$data["precio2"];
		$producto->precio3=$data["precio3"];
		if ($producto->guardar()){
			http_response_code(201);
			print json_encode($producto);
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
		$producto=Producto::getProductoDeId($data["id"]);
		$producto->codigo_prod=$data["codigo_prod"];
		$producto->producto=strtoupper($data["producto"]);
		$producto->marca=strtoupper($data["marca"]);
		$producto->medida=strtoupper($data["medida"]);
		$producto->modelo=$data["modelo"];
		$producto->descripcion=strtoupper($data["descripcion"]);
		$producto->precio1=$data["precio1"];
		$producto->precio2=$data["precio2"];
		$producto->precio3=$data["precio3"];
		if ($producto->guardar()){
			http_response_code(200);
			print json_encode($producto);
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
		$producto=Producto::getProductoDeId($data["id"]);
		$producto->eliminar();
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
