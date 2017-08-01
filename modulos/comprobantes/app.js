var app = angular.module('transaccionesApp', []);
app.controller('transaccionesCtrl', function($scope, $http) {

	$scope.cargarTransacciones = function(response){
		$http.get("api.php").then(function(response) {
			$scope.transacciones = response.data.transacciones;
		});
	}

	$scope.cargarTransacciones();

	$scope.cargarDatos = function() {
		$http.get("api.php").then(function(response) {
			$scope.cuentas = response.data.cuentas;
			$scope.tipos_transaccion = response.data.tipos_transaccion;
			$scope.comprobante.nro_comprobante = response.data.sig_nro_comprobante;
		});
	};

	//$scope.cargarDatos();

	/**
	 * fucncion para actualizar
	 * */
	$scope.actualizarNroTipoComprobante = function(){
		$http.get("api.php?sig_nro_tipo_comprobante="+$scope.comprobante.fk_tipo_transaccion).then(function(response) {
			$scope.comprobante.nro_tipo_comprobante = response.data.sig_nro_tipo_comprobante;
		});
	};

	//$scope.comprobante={fecha: new Date()};

	/**
	 * Crear un nuevo objeto transaccion
	 */
	$scope.nuevaTransaccion = function() {
		$scope.transaccion={
				id : -1,
				nro_comprobante:null,
				nro_tipo_comprobante:null,
				operaciones:[]
			};
		$scope.operacion={
				id:Math.random(),
				debe:0.0,
				haber:0.0
			};
		$scope.tituloModal = "Nueva Transacción";
		$http.get("api.php").then(function(response) {
			$scope.cuentas = response.data.cuentas;
			$scope.tipos_transaccion = response.data.tipos_transaccion;
			$scope.transaccion.nro_comprobante = response.data.sig_nro_comprobante;
			$scope.sumarDebeHaber();
			$('#editarTransaccionModal').modal('toggle');
		});

	};

	$scope.sumarDebeHaber=function(){
		$scope.sumaDebe=0.0;
		$scope.sumaHaber=0.0;
		for(var i=0;i<$scope.transaccion.operaciones.length;i++){
			$scope.sumaDebe+=parseFloat($scope.transaccion.operaciones[i].debe);
			$scope.sumaHaber+=parseFloat($scope.transaccion.operaciones[i].haber);
		}
	};

	/**
	 * adiciona una operacion temporal a la transaccion
	 */
	$scope.adicionarOperacion = function(operacion) {
		$scope.transaccion.operaciones.push(operacion);
		$scope.operacion={
				id:Math.random(),
				debe:0.0,
				haber:0.0
			};
		$scope.sumarDebeHaber();
	};

	/**
	 * eliminar una operacion temporal a la transaccion
	 */
	$scope.eliminarOperacion = function(operacion) {
		var vecOpe=[];
		for(var i=0;i<$scope.transaccion.operaciones.length;i++){
			if($scope.transaccion.operaciones[i].id!==operacion.id){
				vecOpe.push($scope.transaccion.operaciones[i]);
			}
		}
		$scope.transaccion.operaciones=vecOpe;
		$scope.sumarDebeHaber();
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