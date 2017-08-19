<!DOCTYPE html>
<html lang="es-ES">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Módulo Productos v1.0</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
	<script src="../../js/angular.min.js"></script>
	<!-- <script src="app.js"></script> -->
  </head>
  <body>
  	<div class="container" data-ng-app="productoApp" data-ng-controller="productoCtrl">
  		<h1>Productos</h1>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarProductoModal" data-ng-click="nuevoProducto()">Nuevo Producto</button>
		<p/>
		<table class="table">
			<tr>
				<th>ID</th><th>COD. PROD.</th><th>PRODUCTO</th><th>MARCA</th><th>MEDIDA</th><th>MODELO</th><th>DESCRIPCION</th><th>PRECIO 1</th><th>PRECIO 2</th><th>PRECIO 3</th><th></th>
			</tr>
			<tr data-ng-repeat="producto in productos">
			    <td>{{ producto.id }}</td>
			    <td>{{ producto.codigo_prod }}</td>
			    <td>{{ producto.producto }}</td>
			    <td>{{ producto.marca }}</td>
			    <td>{{ producto.medida }}</td>
			    <td>{{ producto.modelo }}</td>
			    <td>{{ producto.descripcion }}</td>
			    <td>{{ producto.precio1 }}</td>
			    <td>{{ producto.precio2 }}</td>
			    <td>{{ producto.precio3 }}</td>
			    <td>
					<button type="button" class="btn btn-warning btn-xs" data-ng-click="editarProducto(producto.id)">Editar</button>
					<button type="button" class="btn btn-danger btn-xs" data-ng-click="eliminarProducto(producto)">Eliminar</button>
			    </td>
			</tr>
		</table>
		
		<!-- Modal Editar Producto-->
		<div id="editarProductoModal" class="modal fade" role="dialog">
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
					    <label class="control-label col-sm-2" for="codigo_prod">Codigo de producto:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="codigo_prod" placeholder="Codigo de Producto" data-ng-model="producto.codigo_prod">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="producto">Producto:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="producto" placeholder="Tipo de Producto" data-ng-model="producto.producto">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="marca">Marca:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="marca" placeholder="Marca de Producto" data-ng-model="producto.marca">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="medida">Medida:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="medida" placeholder="Medida de Producto" data-ng-model="producto.medida">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="modelo">Modelo:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="modelo" placeholder="Modelo de Producto" data-ng-model="producto.modelo">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="descripcion">Descripcion:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="descripcion" placeholder="Nombre del producto" data-ng-model="producto.descripcion">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="precio1">Precio 1:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="precio1" placeholder="Precio1" data-ng-model="producto.precio1">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="precio2">Precio 2:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="precio2" placeholder="Precio2" data-ng-model="producto.precio2">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="precio3">Precio 3:</label>
					    <div class="col-sm-10">
					    	<input type="text" class="form-control" id="precio3" placeholder="Precio3" data-ng-model="producto.precio3">
					    </div>
					  </div>					</form> 
			  </div>
			  <div class="modal-footer">
			  	<button type="submit" class="btn btn-success" data-ng-click="guardarProducto()">Guardar</button>
				<button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
			  </div>
			</div>
	
		  </div>
		</div>

  	</div>
  	<script>
  	var app = angular.module('productoApp', []);
app.controller('productoCtrl', function($scope, $http) {

	$scope.cargarProductos = function() {
		$http.get("api.php").then(function(response) {
			$scope.productos = response.data;
			//alert($scope.producto);
		});
	};

	$scope.cargarProductos();

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
	$scope.nuevoProducto = function() {
		$scope.tituloModal = "Nuevo producto";
		$scope.producto = {
			id : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarProducto = function(idProducto) {
		$scope.tituloModal = "Editar Producto";
		$http({
			url : 'api.php?id=' + idProducto,
			method : "GET",
		}).then(function(response) {
			$scope.producto = response.data;
			$('#editarProductoModal').modal('toggle');
		});
	};
	/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarProducto = function() {
		$http({
			url : 'api.php',
			method : $scope.producto.id == -1 ? "POST" : "PUT",
			data : $scope.producto
		}).then(function(response) {
			// alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarProductoModal').modal('toggle');
				$scope.cargarProductos();
			} else {
				alert("Ocurrio un error");
				console.log(response.data);
			}
		}, function(error) { // optional
			alert("Ocurrio un error");
			console.log("ERROR:", error)
		});
	};

	/**
	 * Eliminar Usuario
	 */
	$scope.eliminarProducto = function(producto) {
		//console.log(producto);
		if (confirm("Esta seguro de eliminar al usuario: "+producto.descripcion)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"id" : producto.id
				}
			}).then(function(response) {
				$scope.cargarProductos();
			}, function(error) { // optional
				console.log("ERROR:", error)
			});
		}
	};
});

  	</script>
  </body>
</html>
