<?php
require("../../conexion.php");

class Cuenta{
	const TABLA="transacciones";
	
	// se decalara los campos de la tabla
	public $id=-1;	// ID entero
	public $nro_comprobante="";
	public $nro_tipo_comprobante="";
	public $fk_tipo_transaccion="";
	public $fecha="";
	public $tipo_cambio="";
	public $ca="";
	public $glosa="";
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		$this->id=-1;	// ID entero
		$this->nro_comprobante="";	// NOMBRE cadena
		$this->nro_tipo_comprobante="";	// CI
		$this->fk_tipo_transaccion="";	// CEL
		$this->fecha="";	// USUARIO
		$this->tipo_cambio="";	// PASS
		$this->ca="";	// PASS
		$this->glosa="";	// PASS
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);// NO BORRAR
	}
	
	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		//echo $fila["CODIGO"];
		$miTransaccion=new self();	// creamos un cuenta con datos vacios
		$miTransaccion->id=$fila["id"];	// los campos de la tabla
		$miTransaccion->nro_comprobante=$fila["nro_comprobante"];
		$miTransaccion->nro_tipo_comprobante=$fila["nro_tipo_comprobante"];
		$miTransaccion->fk_tipo_comprobante=$fila["fk_tipo_comprobante"];
		$miTransaccion->fecha=$fila["fecha"];
		$miTransaccion->glosa=$fila["glosa"];
		return $miTransaccion;
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
		if($this->id==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (CODIGO,GRUPO, NOMBRE_CTA, TIPO,MONEDA) values('".$this->CODIGO."','".$this->GRUPO."','".$this->NOMBRE_CTA."','".$this->TIPO."','".$this->MONEDA."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set CODIGO='".$this->CODIGO."',GRUPO='".$this->GRUPO."',NOMBRE_CTA='".$this->NOMBRE_CTA."',TIPO='".$this->TIPO."',MONEDA='".$this->MONEDA."' where id ='".$this->id."'";
		//echo $sql;
		//print $sql;
			}
		if(mysqli_query($this->conexion,$sql)){
			if($this->id==-1){
				// nuevo
				$sql="select max(id) from ".self::TABLA;
				$result=mysqli_query($this->conexion,$sql);
				$fila=mysqli_fetch_array($result);
				$this->id=$fila[0];
			}
			return self::getCuentaDeId($this->id);
		}
		return null;
	}
	
	/**
	 * Metodo que elimina un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function eliminar(){
		$sql="delete from ".self::TABLA." where id='".$this->id."'";
		mysqli_query($this->conexion,$sql);
		return null;
	}
	/**
	 * Obtienen el objeto usuario de id, null en caso de que no exista
	 * */
	public static function getTransaccionDeId($id){
		$cuenta=new self();
		$sql="select * from ".self::TABLA." where id ='$id'";
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
