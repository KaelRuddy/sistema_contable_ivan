<?php
require("../../conexion.php");

class Usuario{
	const TABLA="usuario_cont";
	
	public $id=0;	// ID entero
	public $nombre="";	// NOMBRE cadena
	public $ci="";	// CI
	public $cel="";	// CEL
	public $usuario="";	// USUARIO
	public $pass="";	// PASS
	public $tipo=0;	// TIPO
	
	/**
	 * Metodo que guarda un registro en la base de datos
	 * */
	public function guardar(){
		$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		if($this->id==0){	// es nuevo
			$sql="insert into ".self::TABLA." (NOMBRE,CI,CEL,USUARIO,PASS,TIPO) values('".$this->nombre."','".$this->ci."','".$this->cel."','".$this->usuario."','".$this->pass."','".$this->tipo."')";
		}else{// actualizar
			$sql="update ".self::TABLA." set NOMBRE='".$this->nombre."',CI='".$this->ci."',CEL='".$this->cel."',USUARIO='".$this->usuario."',PASS='".$this->pass."',TIPO='".$this->tipo."' where ID='".$this->id."'";
		}
		$result=mysqli_query($con,$sql);
	}
	
	/**
	 * Obtienen todos los registros de la tabla usuario
	 * */
	public static function lista(){
		$lista=array();	// vector de usuarios
		$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		$result=mysqli_query($con,"select * from ".self::TABLA); // select * from usuario_cont
		while($fila=mysqli_fetch_array($result)) {
			array_push($lista,self::mapper($fila));	// guardamos a cada usuario en el vector
		}
		return $lista;
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
