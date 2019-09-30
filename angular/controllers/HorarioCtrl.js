app.service("HorarioServices",function($http, $q, handleBehavior) {
    return({   
        sListarHorarios: sListarHorarios, 
        sAgregarHorario: sAgregarHorario,
        sActualizarHorario: sActualizarHorario,
        sQuitarHorario: sQuitarHorario
    });
    function sListarHorarios(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Horario/listar_horarios",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sAgregarHorario (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Horario/registrar", 
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sActualizarHorario (datos) { 
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Horario/editar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sQuitarHorario (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Horario/anular",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
});
