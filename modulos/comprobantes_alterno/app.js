var app = angular.module('1transaccionesApp', [ 'ui.bootstrap' ]);
app
		.controller(
				'1transaccionesCtrl',
				function($scope, $http) {

					$scope.cargar1transacciones = function(response) {
						$http.get("api.php").then(function(response) {
							$scope.1transacciones = response.data.1transacciones;
						});
					}

					$scope.cargar1transacciones();

					$scope.cargarDatos = function() {
						$http
								.get("api.php")
								.then(
										function(response) {
											$scope.cuentas = response.data.cuentas;
											$scope.tipos_1transaccion = response.data.tipos_1transaccion;
											$scope.comprobante.nro_comprobante = response.data.sig_nro_comprobante;
										});
					};

					/**
					 * Muestra una transacción
					 * */
					$scope.mostrar1transaccion = function(1transaccion){
						$http.get("api.php?1transaccion="+1transaccion.id).then(function(response) {
							$scope.1transaccion = response.data.1transaccion;
							$scope.tituloModal = "Transacción "+1transaccion.nro_comprobante;
							$scope.sumarDebeHaber();
							$('#mostrar1transaccionModal').modal('toggle');
						});
					}

					// $scope.cargarDatos();

					/**
					 * fucncion para actualizar
					 */
					$scope.actualizarNroTipoComprobante = function() {
						$http
								.get(
										"api.php?sig_nro_tipo_comprobante="
												+ $scope.1transaccion.fk_tipo_1transaccion)
								.then(
										function(response) {
											$scope.1transaccion.nro_tipo_comprobante = response.data.sig_nro_tipo_comprobante;
										});
					};

					// $scope.comprobante={fecha: new Date()};

					/**
					 * Crear un nuevo objeto 1transaccion
					 */
					$scope.nueva1transaccion = function() {
						$scope.1transaccion = {
							id : null,
							nro_comprobante : null,
							nro_tipo_comprobante : null,
							1operaciones : []
						};
						$scope.1operacion = {
							id :null,
							debe : 0.0,
							haber : 0.0,
							cuenta : null
						};
						$scope.tituloModal = "Nueva Transacción";
						$http
								.get("api.php")
								.then(
										function(response) {
											$scope.cuentas = response.data.cuentas;
											$scope.tipos_1transaccion = response.data.tipos_1transaccion;
											$scope.1transaccion.nro_comprobante = response.data.sig_nro_comprobante;
											$scope.sumarDebeHaber();
											$('#editar1transaccionModal').modal(
													'toggle');
										});

					};

					$scope.sumarDebeHaber = function() {
						$scope.sumaDebe = 0.0;
						$scope.sumaHaber = 0.0;
						for (var i = 0; i < $scope.1transaccion.1operaciones.length; i++) {
							$scope.sumaDebe += parseFloat($scope.1transaccion.1operaciones[i].debe);
							$scope.sumaHaber += parseFloat($scope.1transaccion.1operaciones[i].haber);
						}
					};

					/**
					 * adiciona una 1operacion temporal a la 1transaccion
					 */
					$scope.adicionar1operacion = function(1operacion) {
						1operacion.cuenta=JSON.parse(1operacion.cuenta);
						$scope.1transaccion.1operaciones.push(1operacion);
						//console.log(1operacion);
						$scope.1operacion = {
							id : null,
							debe : 0.0,
							haber : 0.0,
							descripcion : "",
							cuenta: null
						};
						$scope.sumarDebeHaber();
					};

					/**
					 * eliminar una 1operacion temporal a la 1transaccion
					 */
					$scope.eliminar1operacion = function(1operacion) {
						if (confirm("Desea eliminar la operación: "
								+1operacion.cuenta.nombre_cta)) {
							var vecOpe = [];
							for (var i = 0; i < $scope.1transaccion.1operaciones.length; i++) {
								if ($scope.1transaccion.1operaciones[i].id !== 1operacion.id) {
									vecOpe
											.push($scope.1transaccion.1operaciones[i]);
								}
							}
							$scope.1transaccion.1operaciones = vecOpe;
							$scope.sumarDebeHaber();
						}
					};

					/**
					 * Se prepara la 1transaccion usuario para editar
					 */
					$scope.editar1transaccion = function(1transaccion) {
						$scope.tituloModal = "Editar transaccion";
						$scope.1operacion = {
								id :null,
								debe : 0.0,
								haber : 0.0,
								cuenta : null
							};
						$http({
							url : 'api.php?1transaccion=' + 1transaccion.id,
							method : "GET",
						}).then(function(response) {
							$scope.1transaccion = response.data.1transaccion;
							$http
							.get("api.php")
							.then(
									function(response) {
										$scope.cuentas = response.data.cuentas;
										$scope.tipos_1transaccion = response.data.tipos_1transaccion;
										$scope.1transaccion.nro_comprobante = response.data.sig_nro_comprobante;
										$scope.sumarDebeHaber();
										$('#editar1transaccionModal').modal(
												'toggle');
									});
							//$('#editar1transaccionModal').modal('toggle');
						});
					};
					/**
					 * Guarda un objeto 1transaccion
					 */
					$scope.guardar1transaccion = function() {
						//alert($scope.1transaccion.fecha);
						$scope.1transaccion.tipo_1transaccion=JSON.parse($scope.1transaccion.tipo_1transaccion);
						console.log($scope.1transaccion);
						$http(
								{
									url : 'api.php',
									method : $scope.1transaccion.id === null ? "POST"
											: "PUT",
									data : $scope.1transaccion
								}).then(
								function(response) {
									// alert(response.status);
									console.log(response);
									if (response.status === 201
											|| response.status === 200) {
										$('#editar1transaccionModal').modal(
												'toggle');
										$scope.cargar1transacciones();
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
						// console.log(usuario);
						if (confirm("Esta seguro de eliminar al usuario: "
								+ usuario.nombre)) {
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
					$scope.popup1 = {
						opened : false
					};
					$scope.open1 = function() {
						$scope.popup1.opened = true;
					};
				});
