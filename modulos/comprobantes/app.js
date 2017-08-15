var app = angular.module('transaccionesApp', [ 'ui.bootstrap' ]);
app
		.controller(
				'transaccionesCtrl',
				function($scope, $http) {

					$scope.cargarTransacciones = function(response) {
						$http.get("api.php").then(function(response) {
							$scope.transacciones = response.data.transacciones;
						});
					}

					$scope.cargarTransacciones();

					$scope.cargarDatos = function() {
						$http
								.get("api.php")
								.then(
										function(response) {
											$scope.cuentas = response.data.cuentas;
											$scope.tipos_transaccion = response.data.tipos_transaccion;
											$scope.comprobante.nro_comprobante = response.data.sig_nro_comprobante;
										});
					};

					/**
					 * Muestra una transacción
					 */
					$scope.mostrarTransaccion = function(transaccion) {
						$http
								.get("api.php?transaccion=" + transaccion.id)
								.then(
										function(response) {
											$scope.transaccion = response.data.transaccion;
											$scope.tituloModal = "Transacción "
													+ transaccion.nro_comprobante;
											$scope.sumarDebeHaber();
											$('#mostrarTransaccionModal')
													.modal('toggle');
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
												+ $scope.transaccion.fk_tipo_transaccion)
								.then(
										function(response) {
											$scope.transaccion.nro_tipo_comprobante = response.data.sig_nro_tipo_comprobante;
										});
					};

					// $scope.comprobante={fecha: new Date()};

					/**
					 * Crear un nuevo objeto transaccion
					 */
					$scope.nuevaTransaccion = function() {
						$scope.transaccion = {
							id : -1,
							nro_comprobante : null,
							nro_tipo_comprobante : null,
							operaciones : []
						};
						$scope.operacion = {
							id : -Math.random(),
							debe : 0.0,
							haber : 0.0,
							descripcion : ""
						};
						$scope.tituloModal = "Nueva Transacción";
						$http
								.get("api.php")
								.then(
										function(response) {
											$scope.cuentas = response.data.cuentas;
											$scope.tipos_transaccion = response.data.tipos_transaccion;
											$scope.transaccion.nro_comprobante = response.data.sig_nro_comprobante;
											$scope.sumarDebeHaber();
											$('#editarTransaccionModal').modal(
													'toggle');
										});

					};

					$scope.sumarDebeHaber = function() {
						$scope.sumaDebe = 0.0;
						$scope.sumaHaber = 0.0;
						for (var i = 0; i < $scope.transaccion.operaciones.length; i++) {
							$scope.sumaDebe += parseFloat($scope.transaccion.operaciones[i].debe);
							$scope.sumaHaber += parseFloat($scope.transaccion.operaciones[i].haber);
						}
					};

					/**
					 * adiciona una operacion temporal a la transaccion
					 */
					$scope.adicionarOperacion = function(operacion) {
						$scope.transaccion.operaciones.push(operacion);
						$scope.operacion = {
							id : -Math.random(),
							debe : 0.0,
							haber : 0.0,
							descripcion : ""
						};
						$scope.sumarDebeHaber();
					};

					/**
					 * eliminar una operacion temporal a la transaccion
					 */
					$scope.eliminarOperacion = function(operacion) {
						if (confirm("Desea eliminar la operación: "
								+ operacion.descripcion)) {
							var vecOpe = [];
							for (var i = 0; i < $scope.transaccion.operaciones.length; i++) {
								if ($scope.transaccion.operaciones[i].id !== operacion.id) {
									vecOpe
											.push($scope.transaccion.operaciones[i]);
								}
							}
							$scope.transaccion.operaciones = vecOpe;
							$scope.sumarDebeHaber();
						}
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
					 * Guarda un objeto transaccion
					 */
					$scope.guardarTransaccion = function() {
						alert($scope.transaccion.fecha);
						$http(
								{
									url : 'api.php',
									method : $scope.transaccion.id <= 0 ? "POST"
											: "PUT",
									data : $scope.transaccion
								}).then(
								function(response) {
									// alert(response.status);
									// console.log(response);
									if (response.status === 201
											|| response.status === 200) {
										$('#editarTransaccionModal').modal(
												'toggle');
										$scope.cargarTransacciones();
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

					/**
					 * Para la autoseleccion
					 */
					var _selected;

					$scope.selected = undefined;
//					$scope.cuentas = [ 'Alabama', 'Alaska', 'Arizona',
//							'Arkansas', 'California', 'Colorado',
//							'Connecticut', 'Delaware', 'Florida', 'Georgia',
//							'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa',
//							'Kansas', 'Kentucky', 'Louisiana', 'Maine',
//							'Maryland', 'Massachusetts', 'Michigan',
//							'Minnesota', 'Mississippi', 'Missouri', 'Montana',
//							'Nebraska', 'Nevada', 'New Hampshire',
//							'New Jersey', 'New Mexico', 'New York',
//							'North Dakota', 'North Carolina', 'Ohio',
//							'Oklahoma', 'Oregon', 'Pennsylvania',
//							'Rhode Island', 'South Carolina', 'South Dakota',
//							'Tennessee', 'Texas', 'Utah', 'Vermont',
//							'Virginia', 'Washington', 'West Virginia',
//							'Wisconsin', 'Wyoming' ];
				});
