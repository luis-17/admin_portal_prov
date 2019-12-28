<div class="modal-header">
	<h4 class="modal-title"> Configuración SEO </h4>
</div>
<div class="modal-body">
	<form class="row" name="formPagina"> 
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Nombre de la Página <small class="text-danger">(*)</small> </label>
			<strong class="control-label mb-n block"> {{ fData.nombre }} </strong>
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Meta Título SEO (entre 35 y 65 caracteres) <small class="text-danger">(*)</small> </label>
			<input type="text" class="form-control input-sm" ng-model="fData.titulo_seo" placeholder="Meta Título SEO" required tabindex="10" />
		</div>
		<div class="form-group col-md-12 mb-md">
			<label class="control-label mb-n"> Meta Contenido SEO ( máximo 156 caracteres) <small class="text-danger">(*)</small> </label>
			<textarea class="form-control input-sm" ng-model="fData.meta_content_seo" placeholder="Meta Contenido SEO" tabindex="50" rows="5" required></textarea>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="aceptar(); $event.preventDefault();" ng-disabled="formPagina.$invalid">Aceptar</button>
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>