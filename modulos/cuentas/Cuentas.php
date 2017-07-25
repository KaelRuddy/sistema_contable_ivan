<?php
require("../../conexion.php");

class Cuentas{
	const TABLA="cuentas";
	
	// se decalara los camnpos de la tabla
	public $id=-1;	// ID entero
	public $cuenta="";
	public $grupo="";
	public $descripcion="";
	public $tipo="";
	public $moneda=
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		$this->id=-1;	// ID entero
		$this->cuenta="";	// NOMBRE cadena
		$this->grupo="";	// CI
		$this->descripcion="";	// CEL
		$this->tipo="";	// USUARIO
		$this->moneda="";	// PASS
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);// NO BORRAR
	}
	
	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		$miCuenta=new self();	// creamos un cuenta con datos vacios
		$miCuenta->id=$fila["ID"];	// los campos de la tabla
		$miCuenta->cuenta=$fila["CUENTA"];
		$miCuenta->grupo=$fila["GRUPO"];
		$miCuenta->descripcion=$fila["DESCRIPCION"];
		$miCuenta->tipo=$fila["TIPO"];
		$miCuenta->moneda=$fila["MONEDA"];
		return $miCuenta;
	}
	
	/**
	 * Obtienen todos los registros de la tabla CUENTAS
	 * */
	public static function lista(){
		$cuenta=new self();	// se esta llamando asi mismo
		$lista=array();	// vector de usuarios
		$result=mysqli_query($cuenta->conexion,"select * from ".self::TABLA); // select * from cuenta
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		return $lista;
	}
	
	/**
	 * Función que convierte en Json este objeto
	 * */
	public function toJSON(){
		return json_encode($this);
	}
}

$cuenta=new Cuenta();
$cuenta->lista();
?>
