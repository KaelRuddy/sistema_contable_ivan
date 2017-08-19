<?php
require("../../conexion.php");

class Usuario{
	const TABLA="usuario_cont";
	
	public $id=-1;	// ID entero
	public $nombre="";	// NOMBRE cadena
	public $ci="";	// CI
	public $cel="";	// CEL
	public $usuario="";	// USUARIO
	public $pass="";	// PASS
	public $tipo=0;	// TIPO
	
	private $conexion=null;
	
	/**
	 * Constructor
	 * */
	function __construct() {
		$this->id=-1;	// ID entero
		$this->nombre="";	// NOMBRE cadena
		$this->ci="";	// CI
		$this->cel="";	// CEL
		$this->usuario="";	// USUARIO
		$this->pass="";	// PASS
		$this->tipo=0;	// TIPO
		$this->conexion=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	}
	
	/**
	 * Metodo que guarda un registro en la base de datos
	 * retorna un objeto creado
	 * */
	public function guardar(){
		if($this->id==-1){	// es nuevo
			$sql="insert into ".self::TABLA." (NOMBRE,CI,CEL,USUARIO,PASS,TIPO) values('".$this->nombre."','".$this->ci."','".$this->cel."','".$this->usuario."','".$this->pass."','".$this->tipo."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set NOMBRE='".$this->nombre."',CI='".$this->ci."',CEL='".$this->cel."',USUARIO='".$this->usuario."',PASS='".$this->pass."',TIPO='".$this->tipo."' where ID='".$this->id."'";
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
		$usuario=new self();
		$lista=array();	// vector de usuarios
		$result=mysqli_query($usuario->conexion,"select * from ".self::TABLA); // select * from usuario_cont
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		return $lista;
	}
	
	/**
	 * Obtienen el objeto usuario de id, null en caso de que no exista
	 * */
	public static function getUsuarioDeId($id){
		$usuario=new self();
		$sql="select * from ".self::TABLA." where id ='$id'";
		$result=mysqli_query($usuario->conexion,$sql); // select * from usuario_cont
		if($fila=mysqli_fetch_array($result)) {
			return self::mapper($fila);	// guardamos a cada usuario en el vector
		}
		return null;
	}
	
	/**
	 * Mapeador para crear un objeto usuario
	 * */
	private static function mapper($fila){
		$miUsuario=new self();	// creamos un usuario con datos vacios
		$miUsuario->id=$fila["ID"];	// los campos de la tabla
		$miUsuario->nombre=$fila["NOMBRE"];
		$miUsuario->ci=$fila["CI"];
		$miUsuario->cel=$fila["CEL"];
		$miUsuario->usuario=$fila["USUARIO"];
		$miUsuario->pass=$fila["PASS"];
		$miUsuario->tipo=$fila["TIPO"];
		return $miUsuario;
	}
	
	/**
	 * Función que convierte en Json este objeto
	 * */
	public function toJSON(){
		return json_encode($this);
	}
}
?>
