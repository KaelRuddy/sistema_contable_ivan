<!DOCTYPE html>
<html lang="es-ES">
  <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modulo Cuentas v1.0</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
	<script src="../../js/angular.min.js"></script>
	<script src="app.js"></script>
  </head>
  <body>
  	<div class="container" data-ng-app="transaccionesApp" data-ng-controller="transaccionesCtrl">
  		<h1>Transacciones</h1>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editarCuentaModal" data-ng-click="nuevoCuenta()">Nueva Transaccion</button>
		<p/>
		<table class="table">
			<tr>
				<th>ID</th><th>CODIGO</th><th>COD TRANSACCION</th><th>TIPO</th><th>FECHA</th><th>GLOSA</th><th></th>
			</tr>
			<tr data-ng-repeat="transaccion in transacciones">
			    <td>{{ transaccion.id}}</td>
			    <td>{{ transaccion.nro_comprobante }}</td>
			    <td>{{ transaccion.nro_tipo_comprobante }}</td>
			    <td>{{ transaccion.fk_tipo_transaccion }}</td>
			    <td>{{ transaccion.fecha }}</td>
			    <td>{{ transaccion.glosa }}</td>
				<td>
					<button type="button" class="btn btn-warning btn-xs" data-ng-click="editarCuenta(cuenta)">Editar</button>
					<button type="button" class="btn btn-danger btn-xs" data-ng-click="eliminarCuenta(cuenta)">Eliminar</button>
				</td>
			</tr>
		</table>

		<!-- Modal Editar Cuenta-->
		 <div id="editarCuentaModal" class="modal fade" role="dialog">
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
					    <label class="control-label col-sm-2" for="Codigo">Codigo:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="Codigo" placeholder="Codigo de cuenta" data-ng-model="cuenta.codigo">
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-2" for="grupo">Grupo:</label>
					    <div class="col-sm-10">
					      <!-- <input type="text" class="form-control" id="grupo" placeholder="Grupo" data-ng-model="cuenta.GRUPO"> -->
					      <select id="tipo" data-ng-model="cuenta.grupo">
					      	<option></option>
							<option data-ng-repeat="grupo in grupos" value="{{grupo.id}}">{{grupo.nombre}}</option>
						</select>
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-2" for="nombre_cta">Descripcion:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="nombre_cta" placeholder="Descripcion" data-ng-model="cuenta.nombre_cta">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="tipo">Tipo:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="tipo" placeholder="Tipo" data-ng-model="cuenta.tipo">
					    </div>
					  </div>
					  <div class="form-group">
					    <label class="control-label col-sm-2" for="moneda">Moneda:</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="moneda" placeholder="Moneda" data-ng-model="cuenta.moneda">
					    </div>
					  </div>
					</form>
			  </div>
			  <div class="modal-footer">
			  	<button type="submit" class="btn btn-success" data-ng-click="guardarCuenta()">Guardar</button>
				<button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
			  </div>
			</div>

		  </div>
		</div>
  	</div>
  </body>
</html>