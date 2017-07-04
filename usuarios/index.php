<!DOCTYPE html>
<html>
	<head>
		<title>Lista de usuarios</title>
	</head>
<body>
<?php 
require '../modelo/Usuario.php';
$usuarios=Usuario::lista();	// obtenemos a los usuarios
?>
<h1>Lista de usuarios</h1>
<a href="editar.php">Adicionar nuevo</a>
<table border="1">
	<tr>
		<th>ID</th><th>NOMBRE</th><th>CI</th><th>CEL</th><th>USUARIO</th><th>PASS</th><th>TIPO</th>
	</tr>
	<?php 
	foreach ($usuarios as &$usuario) {
		echo "<tr>";
		echo "<td>".$usuario->id."</td>";
		echo "<td>".$usuario->nombre."</td>";
		echo "<td>".$usuario->ci."</td>";
		echo "<td>".$usuario->cel."</td>";
		echo "<td>".$usuario->usuario."</td>";
		echo "<td>*****</td>";
		echo "<td>".$usuario->tipo."</td>";
		echo "</tr>";
	}
	?>
</table>


</body>
</html>
 