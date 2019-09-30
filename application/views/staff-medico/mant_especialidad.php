<div class="modal-header">
	<h4 class="modal-title"> {{ titleForm }} </h4>
</div> 
<div class="modal-body">  
	<div class="row" > 
		<div class="form-group block col-sm-6">
			<label class="control-label"> Médico: </label>
            <p class="text-bold"> {{ fData.nombres }} {{ fData.ap_paterno }} {{ fData.ap_materno }} </p> 
		</div>
		<div class="form-group block col-sm-6">
			<label class="control-label"> CMP: </label>
            <p class="text-bold"> {{ fData.cmp }} </p> 
		</div>
		<div class="col-sm-4 col-xs-12"> 
			<form name="formEspecialidad" class="pt-sm {{ editClassForm }} "> 
				<fieldset class="fieldset-sm">
					<legend class="mb-sm "> {{ tituloBloque }} </legend> 
					<div class="form-group">
						<label class="control-label"> Especialidad: <small class="text-danger">(*)</small> </label>
			            <select class="form-control input-sm" ng-model="fEspMedico.especialidad" ng-options="item as item.descripcion for item in fArr.listaEspecialidades" 
			            	required ></select> 
					</div>
					<div class="form-group" ng-if="contBotonesReg">
						<button type="button" ng-click="agregarEspecialidad(); $event.preventDefault();" ng-disabled="formEspecialidad.$invalid" tabindex="100" class="block btn btn-primary btn-sm btn-full"> <i class="fa fa-plus"></i> AGREGAR </button>
					</div> 
					<div class="form-group" ng-if="contBotonesEdit">
						<button type="button" ng-click="actualizarEspecialidad(); $event.preventDefault();" tabindex="110" ng-disabled="formEspecialidad.$invalid" class="block btn btn-primary btn-sm btn-block"> <i class="fa fa-edit"></i> ACTUALIZAR </button>
						<button type="button" ng-click="quitarEspecialidad(); $event.preventDefault();" tabindex="120" class="block btn btn-danger btn-sm btn-block"> <i class="fa fa-trash"></i> QUITAR </button>
					</div> 
				</fieldset>	
			</form>
		</div>
		<div class="col-sm-8 col-xs-12">
			<div class="row">
				<div class="col-xs-12">
					<div  ui-grid="gridOptionsEsp" ui-grid-pagination ui-grid-selection ui-grid-resize-columns ui-grid-auto-resize class="grid table-responsive fs-mini-grid"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>
