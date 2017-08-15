<!DOCTYPE html>
<html lang="es-ES">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Módulo Clientes v1.0</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
	<script src="../../js/angular.min.js"></script>
	<!-- <script src="app.js"></script> -->
  </head>
  <body>
  	<div class="container" data-ng-app="clienteApp" data-ng-controller="clienteCtrl">
  		<h1>Clientes</h1>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarClienteModal" data-ng-click="nuevoCliente()">Nuevo Cliente</button>
		<p/>
		<table class="table">
			<tr>
				<th>ID</th><th>NIT</th><th>RAZON SOCIAL</th><th>DIRECCION</th><th>TELEFONO</th><th>NOMBRE CONTACTO</th><th>FONO CONTACTO</th><th></th>
			</tr>
			<tr data-ng-repeat="cliente in clientes">
			    <td>{{ cliente.id }}</td>
			    <td>{{ cliente.nit }}</td>
			    <td>{{ cliente.razon_social }}</td>
			    <td>{{ cliente.direccion }}</td>
			    <td>{{ cliente.fono }}</td>
			    <td>{{ cliente.nombre_contacto }}</td>
			    <td>{{ cliente.cel }}</td>
			    <td>
					<button type="button" class="btn btn-warning btn-xs" data-ng-click="editarCliente(cliente.id)">Editar</button>
					<button type="button" class="btn btn-danger btn-xs" data-ng-click="eliminarCliente(cliente)">Eliminar</button>
			    </td>
			</tr>
		</table>

		<!-- Modal Editar Cliente-->
		<div id="editarClienteModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{{ tituloModal }}</h4>
			  </div>
			  <div class="modal-body">
				 <form class="form-horizontal">
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="nit">NIT:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="nit" placeholder="NIT" data-ng-model="cliente.nit">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="razon_social">Razon Social:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="razon_social" placeholder="Razon social o nombre" data-ng-model="cliente.razon_social">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="direccion">Direccion:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="direccion" placeholder="Direccion Empresa" data-ng-model="cliente.direccion">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="fono">Telefono:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="fono" placeholder="Telefono" data-ng-model="cliente.fono">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="nombre_contacto">Nombre Contacto:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="nombre_contacto" placeholder="Nombre de Contacto" data-ng-model="cliente.nombre_contacto">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="cel">Celular:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="cel" placeholder="Celular de Contacto" data-ng-model="cliente.cel">
					    </div>
					  </div>
					 </form>
			  </div>
			  <div class="modal-footer">
			  	<button type="submit" class="btn btn-success" data-ng-click="guardarCliente()">Guardar</button>
				<button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
			  </div>
			</div>

		  </div>
		</div>

  	</div>
  	<script>

  	var app = angular.module('clienteApp', []);
app.controller('clienteCtrl', function($scope, $http) {

	$scope.cargarClientes = function() {
		$http.get("api.php").then(function(response) {
			$scope.clientes = response.data
			//alert($scope.clientes);
		});
	};

	$scope.cargarClientes();

	$scope.tipos = [ {
		"id" : 1,
		"nombre" : "Administrador"
	}, {
		"id" : 2,
		"nombre" : "Contador"
	}, {
		"id" : 3,
		"nombre" : "Usuario"
	} ];

	/**
	 * Crear un nuevo objeto usuario
	 */
	$scope.nuevoCliente = function() {
		$scope.tituloModal = "Nuevo Cliente";
		$scope.cliente = {
			id : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarCliente = function(idCliente) {
		$scope.tituloModal = "Editar Cliente";
		$http({
			url : 'api.php?id=' + idCliente,
			method : "GET",
		}).then(function(response) {
			$scope.cliente = response.data;
			//console.log($scope.cliente);
			$('#editarClienteModal').modal('toggle');
		});
	};
	/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarCliente = function() {
		//console.log($scope.cliente);
		$http({
			url : 'api.php',
			method : $scope.cliente.id == -1 ? "POST" : "PUT",
			data : $scope.cliente
		}).then(function(response) {
			 //alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarClienteModal').modal('toggle');
				$scope.cargarClientes();
			} else {
				alert("Ocurrio un error1");
				console.log(response.data);
			}
		}, function(error) { // optional
			//console.log(response);
			alert("Ocurrio un error2");
			console.log("ERROR:", error)
		});
	};

	/**
	 * Eliminar Usuario
	 */
	$scope.eliminarCliente = function(cliente) {
		//console.log(cliente);
		//if (confirm("Esta seguro de eliminar al usuario: "+clientes.razon_social)) {
		if (confirm("Esta seguro de eliminar al usuario: "+cliente.razon_social)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"id" : cliente.id
				}
			}).then(function(response) {
				$scope.cargarClientes();
			}, function(error) { // optional
				console.log("ERROR:", error)
			});
		}
	};
});

  	</script>
  </body>
</html>
