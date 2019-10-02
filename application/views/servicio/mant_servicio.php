<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div> 
<div class="modal-body">  
	<form class="row" name="formServicio"> 
		<!-- <div class="form-group col-md-4 mb-md ">
			<label class="control-label mb-n"> Tipo de Elemento <small class="text-danger">(*)</small> </label>
            <select disabled class="form-control input-sm" ng-model="fData.tipo_elemento" ng-options="item as item.descripcion for item in fArr.listaTipoElemento" required tabindex="10" ></select> 
		</div> -->
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Nombre <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.nombre" placeholder="Nombre" required tabindex="10" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> URI <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.alias" placeholder="URI" required tabindex="20" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Descripción: <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.descripcion_html" placeholder="Descripción" tabindex="30"></textarea>
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Embed de Video: <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.embed_video" placeholder="Pegue aquí código EMBED" tabindex="40"></textarea>
		</div>
		<div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible" ng-false-value="0" ng-true-value="1"> ¿Es Visible?
			</label>						
        </div>
        <div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible_menu" ng-checked="fData.visible_menu" ng-false-value="0" ng-true-value="1"> ¿Es Visible en Menú?
			</label>						
        </div>
        <div class="form-group col-md-12 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible_esp" ng-checked="fData.visible_esp" ng-false-value="0" ng-true-value="1"> ¿Es Visible en Especialidad?
			</label>						
        </div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen de Servicio (550px * 500px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.imagen_servicio" ng-src="{{ app.name + 'assets/dinamic/servicio/imagenes/' + fData.imagen_servicio }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.imagen_servicio_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Icono de Servicio (120px * 120px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.icono_servicio" ng-src="{{ app.name + 'assets/dinamic/servicio/' + fData.icono_servicio }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.icono_servicio_blob" /> 
					</span>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formServicio.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>