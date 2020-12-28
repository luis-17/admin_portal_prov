<div class="modal-header">
	<h4 class="modal-title"> Detalle de la cita </h4>
</div>
<div class="modal-body">
	<form class="row" name="formDocumento">
		<div class="form-group col-sm-6 mb-md">
			<label class="control-label mb-n block"> Código de cita</label>
			<label> {{fDataCita.idcita}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
			<label class="control-label mb-n block"> Código Spring</label>
			<label> {{fDataCita.idcitaspring}} </label>
		</div>
		<div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Fecha de Registro</label>
            <label> {{fDataCita.fecha_registro}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Fecha de Cita</label>
            <label> {{fDataCita.fecha_cita}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Hora de Cita</label>
            <label> {{fDataCita.hora}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Fecha Anulación</label>
            <label> {{fDataCita.fecha_anulacion}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Tipo de documento</label>
            <label> {{fDataCita.tipo_documento}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> N° de documento</label>
            <label> {{fDataCita.numero_documento}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Paciente</label>
            <label> {{fDataCita.cliente}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Correo</label>
            <label> {{fDataCita.correo}}</label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Teléfono</label>
            <label> {{fDataCita.telefono}}</label>
		</div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Médico</label>
            <label> {{fDataCita.medico}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Especialidad</label>
            <label> {{fDataCita.especialidad}} </label>
        </div>
        <div class="form-group col-sm-6 mb-md">
            <label class="control-label mb-n block"> Garante</label>
            <label> {{fDataCita.descripcion_gar}} </label>
		</div>
	</form>
</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancel()">Cerrar</button>
</div>