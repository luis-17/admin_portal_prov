<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div>
<div class="modal-body">
	<form class="row" name="formTestimonio"> 
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Paciente <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.paciente" placeholder="Nombre del paciente" required tabindex="10" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Descripción: <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.testimonio_html" placeholder="Describa el testimonio" tabindex="30" required></textarea>
		</div>
		<div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible" ng-false-value="0" ng-true-value="1"> ¿Es Visible?
			</label>						
        </div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Foto de Paciente (125px * 125px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.foto" ng-src="{{ app.name + 'assets/dinamic/testimonio/' + fData.foto }}" />
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
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formTestimonio.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>