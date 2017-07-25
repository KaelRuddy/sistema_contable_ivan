<?php
require("../../conexion.php");

class Cuenta{
	const TABLA="CUENTAS";
	
	// se decalara los campos de la tabla
	//public $id=-1;	// ID entero
	public $CODIGO_CTA=-1;
	public $CODIGO="";
	public $GRUPO="";
	public $NOMBRE_CTA="";
	public $TIPO="";
	public $MONEDA="";
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		//$this->id=-1;	// ID entero
		$this->CODIGO_CTA=-1;	// NOMBRE cadena
		$this->CODIGO="";	// NOMBRE cadena
		$this->GRUPO="";	// CI
		$this->NOMBRE_CTA="";	// CEL
		$this->TIPO="";	// USUARIO
		$this->MONEDA="";	// PASS
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);// NO BORRAR
	}
	
	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		//echo $fila["CODIGO"];
		$miCuenta=new self();	// creamos un cuenta con datos vacios
		$miCuenta->CODIGO_CTA=$fila["CODIGO_CTA"];	// los campos de la tabla
		$miCuenta->CODIGO=$fila["CODIGO"];
		$miCuenta->GRUPO=$fila["GRUPO"];
		$miCuenta->NOMBRE_CTA=$fila["NOMBRE_CTA"];
		$miCuenta->TIPO=$fila["TIPO"];
		$miCuenta->MONEDA=$fila["MONEDA"];
		return $miCuenta;
	}
	
	/**
	 * Obtienen todos los registros de la tabla CUENTAS
	 * */
	public static function lista(){
		$cuenta=new self();	// se esta llamando asi mismo
		$lista=array();	// vector de usuarios
		$sql="select * from ".self::TABLA." order by CODIGO";
		//echo $sql;
		$result=mysqli_query($cuenta->conexion,$sql); // select * from cuenta
		//echo $cuenta->conexion;
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		//echo $lista;
		return $lista;
	}
	
	/**
	 * Metodo que guarda un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function guardar(){
		if($this->CODIGO_CTA==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (CODIGO,GRUPO, NOMBRE_CTA, TIPO,MONEDA) values('".$this->CODIGO."','".$this->GRUPO."','".$this->NOMBRE_CTA."','".$this->TIPO."','".$this->MONEDA."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set CODIGO='".$this->CODIGO."',GRUPO='".$this->GRUPO."',NOMBRE_CTA='".$this->NOMBRE_CTA."',TIPO='".$this->TIPO."',MONEDA='".$this->MONEDA."' where CODIGO_CTA ='".$this->CODIGO_CTA."'";
		//echo $sql;
		//print $sql;
			}
		if(mysqli_query($this->conexion,$sql)){
			if($this->CODIGO_CTA==-1){
				// nuevo
				$sql="select max(CODIGO_CTA) from ".self::TABLA;
				$result=mysqli_query($this->conexion,$sql);
				$fila=mysqli_fetch_array($result);
				$this->CODIGO_CTA=$fila[0];
			}
			return self::getCuentaDeCODIGO_CTA($this->CODIGO_CTA);
		}
		return null;
	}
	
	/**
	 * Metodo que elimina un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function eliminar(){
		$sql="delete from ".self::TABLA." where CODIGO_CTA='".$this->CODIGO_CTA."'";
		mysqli_query($this->conexion,$sql);
		return null;
	}
	/**
	 * Obtienen el objeto usuario de id, null en caso de que no exista
	 * */
	public static function getCuentaDeCODIGO_CTA($CODIGO_CTA){
		$cuenta=new self();
		$sql="select * from ".self::TABLA." where CODIGO_CTA ='$CODIGO_CTA'";
		$result=mysqli_query($cuenta->conexion,$sql); // select * from usuario_cont
		if($fila=mysqli_fetch_array($result)) {
			return self::mapper($fila);	// guardamos a cada usuario en el vector
		}
		return null;
	}
	
	/**
	 * Función que convierte en Json este objeto
	 * */
	public function toJSON(){
		return json_encode($this);
	}
}

//$cuenta=new Cuenta();
//Cuenta::lista();
?>
