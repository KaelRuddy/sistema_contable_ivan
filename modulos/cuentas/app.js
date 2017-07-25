var app = angular.module('cuentaApp', []);
app.controller('cuentaCtrl', function($scope, $http) {

	$scope.cargarCuenta = function() {
	//alert("ingrese");
		$http.get("api.php").then(function(response) {
			$scope.cuentas = response.data;
			//alert($scope.cuentas);
		});
	};

	$scope.cargarCuenta();
	
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
			CODIGO_CTA : -1
		};
	};
	/**
	 * Se prepara el usuario para editar
	 */
	$scope.editarCuenta = function(CODIGO_CTACuenta) {
		$scope.tituloModal = "Editar cuenta";
		$http({
			url : 'api.php?CODIGO_CTA=' + CODIGO_CTACuenta,
			method : "GET",
		}).then(function(response) {
			$scope.cuenta = response.data;
			$('#editarCuentaModal').modal('toggle');
		});
	};
	/**
	 * Guarda un objeto usuario
	 */
	$scope.guardarCuenta = function() {
		$http({
			url : 'api.php',
			method : $scope.cuenta.CODIGO_CTA == -1 ? "POST" : "PUT",
			data : $scope.cuenta
		}).then(function(response) {
			//alert(response.status);
			// console.log(response);
			if (response.status === 201 || response.status === 200) {
				$('#editarCuentaModal').modal('toggle');
				$scope.cargarCuenta();
			} else {
				alert("Ocurrio un error1");
				console.log(response.data);0
			}
		}, function(error) { // optional
			alert("Ocurrio un error2");
			console.log("ERROR:", error);
		});
	};

	/**
	 * Eliminar Cuenta
	 */
	$scope.eliminarCuenta = function(cuenta) {
		//console.log(cuenta);
		if (confirm("Esta seguro de eliminar la cuenta: "+cuenta.NOMBRE_CTA)) {
			$http({
				url : 'api.php',
				method : "DELETE",
				data : {
					"CODIGO_CTA" : cuenta.CODIGO_CTA
				}
			}).then(function(response) {
				$scope.cargarCuenta();
			}, function(error) { // optional
				alert("Ocurrio un error");
				console.log("ERROR:", error)
			});
		}
	};
});
