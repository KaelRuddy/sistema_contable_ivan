<?php
require 'Usuario.php';
if ( $_POST ) {
	foreach ( $_POST as $key => $value ) {
		echo "llave: ".$key."- Valor:".$value."<br />";
	}
}else{
	print json_encode(Usuario::lista());
}
?>
