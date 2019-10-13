<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div> 
<div class="modal-body">  
	<form class="row" name="formMedico">
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Nombres: <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.nombres" placeholder="Ingrese nombres" required tabindex="10" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Ap. Paterno: <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.ap_paterno" placeholder="Ingrese Apellido Paterno" required tabindex="20" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Ap. Materno: <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.ap_materno" placeholder="Ingrese Apellido Paterno" required tabindex="30" />
		</div>
		<div class="form-group col-md-3 mb-md">
			<label class="control-label mb-n"> CMP: <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.cmp" placeholder="Ingrese CMP" tabindex="40" required />
		</div> 
		<div class="form-group col-md-3 mb-md">
			<label class="control-label mb-n"> RNE: </label>
			<input type="tel" class="form-control input-sm" ng-model="fData.rne" placeholder="Ingrese RNE" tabindex="50" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Sexo: <small class="text-danger">(*)</small> </label>
			<select class="form-control input-sm" ng-model="fData.sexo" tabindex="55" required>
				<option value="M">MASCULINO</option>
				<option value="F">FEMENINO</option>
			</select>
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Lema: </label>
			<input type="text" class="form-control input-sm" ng-model="fData.lema" placeholder="Ingrese lema" tabindex="60" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Estudios: </label>
			<text-angular tabindex="70" ng-model="fData.estudios_html" required></text-angular>
			<!-- <textarea class="form-control input-sm" ng-model="fData.estudios_html" placeholder="Ingrese estudios" tabindex="70"></textarea> -->
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Foto Miniatura (200px * 253px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.foto" ng-src="{{ app.name + 'assets/dinamic/medico/' + fData.foto }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.foto_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Foto de Perfil (813px * 1200px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.foto_perfil" ng-src="{{ app.name + 'assets/dinamic/medico/medico-perfil/' + fData.foto_perfil }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.foto_perfil_blob" /> 
					</span>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formMedico.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>
