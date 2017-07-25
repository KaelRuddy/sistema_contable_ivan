<?php
require("../../conexion.php");

class Usuario{
	const TABLA="productos";
	
	public $id=-1;	// ID entero
	public $codigo_prod="";	// NOMBRE cadena
	public $tipo_prod="";	// CI
	public $descripcion="";	// CEL
	public $origen="";	// USUARIO
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		$this->id=-1;	// ID entero
		$this->codigo_prod="";	// NOMBRE cadena
		$this->tipo_prod="";	// CI
		$this->descripcion="";	// CEL
		$this->origen="";	// USUARIO
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	}
	
	/**
	 * Metodo que guarda un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function guardar(){
		if($this->id==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (codigo_prod,tipo_prod,descripcion,origen) values('".$this->codigo_prod."','".$this->tipo_prod."','".$this->descripcion."','".$this->origen."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set codigo_prod='".$this->codigo_prod."',tipo_prod='".$this->tipo_prod."',descripcion='".$this->descripcion."',origen='".$this->origen."' where id='".$this->id."'";
		}
		if(mysqli_query($this->conexion,$sql)){
			if($this->id==-1){
				// nuevo
				$sql="select max(id) from ".self::TABLA;
				$result=mysqli_query($this->conexion,$sql);
				$fila=mysqli_fetch_array($result);
				$this->id=$fila[0];
			}
			return self::getUsuarioDeId($this->id);
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
		$result=mysqli_query($producto->conexion,"select * from ".self::TABLA); // select * from id_cont
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
		$miProducto->id=$fila["ID"];	// los campos de la tabla
		$miProducto->codigo_prod=$fila["codigo_prod"];
		$miProducto->tipo_prod=$fila["tipo_prod"];
		$miProducto->descripcion=$fila["descripcion"];
		$miProducto->origen=$fila["origen"];
		return $miProducto;
	}
	
	/**
	 * Función que convierte en Json este objeto
	 * */
	public function toJSON(){
		return json_encode($this);
	}
}
?>
