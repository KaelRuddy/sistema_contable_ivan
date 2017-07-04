<?php
require '../modelo/Usuario.php';
if (isset($_GET['id'])){
	$titulo = "Editar usuario";
}else{
	$titulo = "Adicionar Nuevo";
}
// si es que se guarda
if (isset($_POST['nombre'])){
	$usuario=new Usuario();	// creamos un nuevo usuario
	$usuario->nombre=$_POST['nombre'];
	$usuario->ci=$_POST['ci'];
	$usuario->cel=$_POST['cel'];
	$usuario->pass=$_POST['pass'];
	$usuario->usuario=$_POST['usuario'];
	$usuario->tipo=$_POST['tipo'];
	$usuario->guardar();	// graba en la base de datos
	header("Location:.");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
		<?php echo $titulo; ?>
		</title>
	</head>
<body>
<h1><?php echo $titulo; ?></h1>


<form method="post" action="#">
<table border="1">
	<tr>
		<th>NOMBRE</th><td><input type="text" name="nombre"/></td>
	</tr>
	<tr>
		<th>CI</th><td><input type="text" name="ci"/></td>
	</tr>
	<tr>
		<th>CEL</th><td><input type="text" name="cel"/></td>
	</tr>
	<tr>
		<th>USUARIO</th><td><input type="text" name="usuario"/></td>
	</tr>
	<tr>
		<th>PASS</th><td><input type="password" name="pass"/></td>
	</tr>
	<tr>
		<th>TIPO</th>
		<td>
			<select name="tipo">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select>
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" value="Guardar"/>
		</th>
	</tr>
</table>

</form>
</body>
</html>
 