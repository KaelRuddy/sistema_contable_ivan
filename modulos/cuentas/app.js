var app = angular.module('cuentaApp', []);
app.controller('cuentaCtrl', function($scope, $http) {

	$scope.cargarCuentas = function() {
	//alert("ingrese");
		$http.get("api.php").then(function(response) {
			$scope.cuentas = response.data.cuentas;
			//alert($scope.cuentas);
		});
	};

	$scope.cargarCuentas();

	//$scope.a=34;
	//$scope.b=45;

	$scope.grupos = [ {
		"id" : "ACTIVO",
		"nombre" : "ACTIVO"
	}, {
		"id" : "PASIVO",
		"nombre" : "PASIVO"//+$scope.a
	}, {
		"id" : "PATRIMONIO",
		"nombre" : "PATRIMONIO"// $scope.a+$scope.b*$scope.a
	}, {
		"id" : "INGRESO",
		"nombre" : "INGRESO"
	}, {
		"id" : "EGRESO",
		"nombre" : "EGRESO"
	} ];

	/**
	 * Crear un nuevo objeto usuario
	 */
	$scope.nuevoCuenta = function() {
		$scope.tituloModal = "Nueva cuenta";
		$scope.cuenta = {
			id : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarCuenta = function(cuenta) {
		$scope.tituloModal = "Editar cuenta";
		$http({
			url : 'api.php?id=' + cuenta.id,
			method : "GET",
		}).then(function(response) {
			$scope.cuenta = response.data.cuenta;
			$('#editarCuentaModal').modal('toggle');
		});
	};
	/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarCuenta = function() {
		$http({
			url : 'api.php',
			method : $scope.cuenta.id == -1 ? "POST" : "PUT",
			data : $scope.cuenta
		}).then(function(response) {
			//alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarCuentaModal').modal('toggle');
				$scope.cargarCuentas();
			} else {
				alert("Ocurrio un error1");
				console.log(response.data);
			}
		}, function(error) { // optional
			alert("Ocurrio un error2");
			console.log("ERROR:", error);
			document.write(error.error);
		});
	};

	/**
	 * Eliminar Cuenta
	 */
	$scope.eliminarCuenta = function(cuenta) {
		//console.log(cuenta);
		if (confirm("Esta seguro de eliminar la cuenta: "+cuenta.nombre_cta)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"id" : cuenta.id
				}
			}).then(function(response) {
				if (response.status === 204 || response.status === 200) {
					$scope.cargarCuentas();
				} else {
					alert("Ocurrio un error1");
					console.log(response.data);
				}
			}, function(error) { // optional
				alert("Ocurrio un error");
				console.log("ERROR:", error)
			});
		}
	};
});
