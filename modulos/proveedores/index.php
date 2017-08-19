<!DOCTYPE html>
<html lang="es-ES">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Módulo proveedores v1.0</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
	<script src="../../js/angular.min.js"></script>
	<!-- <script src="app.js"></script> -->
  </head>
  <body>
  	<div class="container" data-ng-app="proveedoreApp" data-ng-controller="proveedoreCtrl">
  		<h1>Proveedores</h1>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarproveedoreModal" data-ng-click="nuevoproveedore()">Nuevo proveedor</button>
		<p/>
		<table class="table">
			<tr>
				<th>ID</th><th>NIT</th><th>RAZON SOCIAL</th><th>DIRECCION</th><th>TELEFONO</th><th>NOMBRE CONTACTO</th><th>FONO CONTACTO</th><th></th>
			</tr>
			<tr data-ng-repeat="proveedore in proveedores">
			    <td>{{ proveedore.id }}</td>
			    <td>{{ proveedore.nit }}</td>
			    <td>{{ proveedore.razon_social }}</td>
			    <td>{{ proveedore.direccion }}</td>
			    <td>{{ proveedore.fono }}</td>
			    <td>{{ proveedore.nombre_contacto }}</td>
			    <td>{{ proveedore.cel }}</td>
			    <td>
					<button type="button" class="btn btn-warning btn-xs" data-ng-click="editarproveedore(proveedore.id)">Editar</button>
					<button type="button" class="btn btn-danger btn-xs" data-ng-click="eliminarproveedore(proveedore)">Eliminar</button>
			    </td>
			</tr>
		</table>

		<!-- Modal Editar proveedore-->
		<div id="editarproveedoreModal" class="modal fade" role="dialog">
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
					      <input type="text" class="form-control" id="nit" placeholder="NIT" data-ng-model="proveedore.nit">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="razon_social">Razon Social:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="razon_social" placeholder="Razon social o nombre" data-ng-model="proveedore.razon_social">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="direccion">Direccion:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="direccion" placeholder="Direccion Empresa" data-ng-model="proveedore.direccion">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="fono">Telefono:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="fono" placeholder="Telefono" data-ng-model="proveedore.fono">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="nombre_contacto">Nombre Contacto:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="nombre_contacto" placeholder="Nombre de Contacto" data-ng-model="proveedore.nombre_contacto">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="cel">Celular:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="cel" placeholder="Celular de Contacto" data-ng-model="proveedore.cel">
					    </div>
					  </div>
					 </form>
			  </div>
			  <div class="modal-footer">
			  	<button type="submit" class="btn btn-success" data-ng-click="guardarproveedore()">Guardar</button>
				<button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
			  </div>
			</div>

		  </div>
		</div>

  	</div>
  	<script>

  	var app = angular.module('proveedoreApp', []);
app.controller('proveedoreCtrl', function($scope, $http) {

	$scope.cargarproveedores = function() {
		$http.get("api.php").then(function(response) {
			$scope.proveedores = response.data
			//alert($scope.proveedores);
		});
	};

	$scope.cargarproveedores();

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
	$scope.nuevoproveedore = function() {
		$scope.tituloModal = "Nuevo proveedor";
		$scope.proveedore = {
			id : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarproveedore = function(idproveedore) {
		$scope.tituloModal = "Editar proveedore";
		$http({
			url : 'api.php?id=' + idproveedore,
			method : "GET",
		}).then(function(response) {
			$scope.proveedore = response.data;
			//console.log($scope.proveedore);
			$('#editarproveedoreModal').modal('toggle');
		});
	};
	/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarproveedore = function() {
		//console.log($scope.proveedore);
		$http({
			url : 'api.php',
			method : $scope.proveedore.id == -1 ? "POST" : "PUT",
			data : $scope.proveedore
		}).then(function(response) {
			 //alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarproveedoreModal').modal('toggle');
				$scope.cargarproveedores();
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
	$scope.eliminarproveedore = function(proveedore) {
		//console.log(proveedore);
		//if (confirm("Esta seguro de eliminar al usuario: "+proveedores.razon_social)) {
		if (confirm("Esta seguro de eliminar al usuario: "+proveedore.razon_social)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"id" : proveedore.id
				}
			}).then(function(response) {
				$scope.cargarproveedores();
			}, function(error) { // optional
				console.log("ERROR:", error)
			});
		}
	};
});

  	</script>
  </body>
</html>
