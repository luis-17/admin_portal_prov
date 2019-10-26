<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div>
<div class="modal-body">
	<form class="row" name="formSlidersHome"> 
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Lema Principal <small class="text-danger">(*)</small> </label>
			<text-angular tabindex="10" ng-model="fData.lema" required></text-angular>
			<!-- <input type="text" class="form-control input-sm" ng-model="fData.lema" required tabindex="10" /> -->
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Lema Secundario <small class="text-danger">(*)</small> </label>
			<text-angular tabindex="20" ng-model="fData.lema_alt" required></text-angular>
			<!-- <input type="text" class="form-control input-sm" ng-model="fData.lema_alt" required tabindex="20" /> -->
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> URL de Redirección <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.link_button" required tabindex="30" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Texto del Botón <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.text_button" required tabindex="40" />
		</div>
		<!-- <div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Descripción: <small class="text-danger">(*)</small> </label>
			<text-angular tabindex="30" ng-model="fData.testimonio_html" required></text-angular>
		</div> -->
		<div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible" ng-false-value="0" ng-true-value="1"> ¿Es Visible?
			</label>						
        </div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen de Fondo (1920px * 834px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.image_background" ng-src="{{ app.name + 'assets/dinamic/slider/' + fData.image_background }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.image_background_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen Lateral (Calado) (576px * 637px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.image_lateral" ng-src="{{ app.name + 'assets/dinamic/slider/lateral/' + fData.image_lateral }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.image_lateral_blob" /> 
					</span>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formSlidersHome.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>