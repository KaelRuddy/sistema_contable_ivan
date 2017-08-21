
var app = angular.module('usuariosApp', []);
app.controller('usuariosCtrl', function($scope, $http) {

	$scope.cargarUsuarios = function() {
		$http.get("api.php").then(function(response) {
			$scope.usuarios = response.data;
		});
	};

	$scope.cargarUsuarios();

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
	$scope.nuevoUsuario = function() {
		$scope.tituloModal = "Nuevo usuario";
		$scope.usuario = {
			id : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarUsuario = function(idUsuario) {
		$scope.tituloModal = "Editar usuario";
		$http({
			url : 'api.php?id=' + idUsuario,
			method : "GET",
		}).then(function(response) {
			$scope.usuario = response.data;
			$('#editarUsuarioModal').modal('toggle');
		});
	};

	var password = document.getElementById("pwd").value;
	var confirmar = document.getElementById("conf").value;
	if(password == confirmar){
			/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarUsuario = function() {
		$http({
			url : 'api.php',
			method : $scope.usuario.id == -1 ? "POST" : "PUT",
			data : $scope.usuario
		}).then(function(response) {
			// alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarUsuarioModal').modal('toggle');
				$scope.cargarUsuarios();
			} else {
				alert("Ocurrio un error");
				console.log(response.data);
			}
		}, function(error) { // optional
			alert("Ocurrio un error");
			console.log("ERROR:", error)
		});
		};
	}
	else{
		alert("Los Datos no coinciden");
	}
	/**
	 * Eliminar Usuario
	 */
	$scope.eliminarUsuario = function(usuario) {
		//console.log(usuario);
		if (confirm("Esta seguro de eliminar al usuario: "+usuario.nombre)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"id" : usuario.id
				}
			}).then(function(response) {
				$scope.cargarUsuarios();
			}, function(error) { // optional
				alert("Ocurrio un error");
				console.log("ERROR:", error)
			});
		}
	};
});
