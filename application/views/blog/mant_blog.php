<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div> 
<div class="modal-body">  
	<form class="row" name="formBlog"> 
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Título <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.titulo" placeholder="Título" required tabindex="10" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> URI <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.uri" placeholder="URI" required tabindex="20" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Fecha de Publicación <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.fecha_publicacion" placeholder="Fecha de Publicación" required tabindex="25" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Autor <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.autor" placeholder="Autor" required tabindex="30" />
		</div>
		<div class="form-group col-md-6 mb-md">
			<label class="control-label mb-n"> Cargo de Autor <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.cargo_autor" placeholder="Cargo de autor" required tabindex="40" />
		</div>
		<div class="form-group col-md-6 mb-md"> 
			<label class="checkbox block" style="margin-left: 20px;">
				<input type="checkbox" ng-model="fData.visible" ng-checked="fData.visible" ng-false-value="0" ng-true-value="1"> ¿Es Visible?
			</label>						
        </div>
        <div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Meta Título SEO (entre 35 y 65 caracteres) <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.titulo_seo" placeholder="Meta Título SEO" required tabindex="45" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Meta Contenido SEO ( máximo 156 caracteres) <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.meta_content_seo" placeholder="Meta Contenido SEO" tabindex="46" rows="5" required></textarea>
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Contenido: <small class="text-danger">(*)</small> </label>
			<text-angular tabindex="50" ng-model="fData.contenido_html" required></text-angular>
			<!-- <textarea class="form-control input-sm" ng-model="fData.contenido_html" placeholder="Contenido" tabindex="50" required></textarea> -->
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Embed de Video: </label>
			<textarea class="form-control input-sm" ng-model="fData.embed_video" placeholder="Pegue aquí código EMBED" tabindex="60"></textarea>
		</div>
		
        <div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Foto de Autor (180px * 180px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.foto_autor" ng-src="{{ app.name + 'assets/dinamic/blog/foto-autor/' + fData.foto_autor }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.foto_autor_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen de Vista Previa (300px * 200px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.imagen_preview" ng-src="{{ app.name + 'assets/dinamic/blog/' + fData.imagen_preview }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.imagen_preview_blob" /> 
					</span>
				</div>
			</div>
		</div>
		<div class="form-group col-md-6 col-sm-12 mb-md">
			<label class="control-label mb-xs"> Imagen de Portada (1200px * 640px) </label>
			<div class="fileinput fileinput-new" data-provides="fileinput" style="width: 100%;">
				<div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; text-align: center;">
					<img ng-if="fData.imagen_portada" ng-src="{{ app.name + 'assets/dinamic/blog/portadas/' + fData.imagen_portada }}" />
				</div>
				<div>
					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Quitar</a> 
					<span class="btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span> 
						<span class="fileinput-exists">Cambiar</span> 
						<input type="file" name="file" file-model="fData.imagen_portada_blob" /> 
					</span>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formBlog.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>