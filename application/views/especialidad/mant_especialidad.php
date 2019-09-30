<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div> 
<div class="modal-body">  
	<form class="row" name="formTransporte">
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Nombre: <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.nombre" placeholder="Nombre" required tabindex="100" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Descripción: <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.descripcion_html" placeholder="Descripción" tabindex="70"></textarea>
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> URI: </label>
			<input type="text" class="form-control input-sm" ng-model="fData.uri" placeholder="URI" tabindex="300" />
		</div>
		<!-- <div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible"> ¿Tiene Landing?
			</label>						
        </div> -->
		<div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible" ng-false-value="0" ng-true-value="1"> ¿Es Visible?
			</label>						
        </div>
        <div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block">
				<input type="checkbox" ng-model="fData.visible_home" ng-checked="fData.visible_home" ng-false-value="0" ng-true-value="1"> ¿Es Visible en Home?
			</label>						
        </div>
        <div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Icono de Especialidad (120px * 120px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.icono_home" ng-src="{{ app.name + 'assets/dinamic/especialidad/iconos-home/' + fData.icono_home }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.icono_home_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen de Especialidad (550px * 500px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.image_banner" ng-src="{{ app.name + 'assets/dinamic/especialidad/' + fData.image_banner }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.image_banner_blob" /> 
					</span>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formTransporte.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div> 