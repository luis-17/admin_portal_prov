app.controller('ReporteCitasCtrl', ['$scope', '$filter', '$uibModal', '$bootbox', '$log', '$timeout', 'pinesNotifications', 'uiGridConstants', 'blockUI', 
  'CitaFactory',
  'CitaServices',
  'ModalReporteFactory',
  function($scope, $filter, $uibModal, $bootbox, $log, $timeout, pinesNotifications, uiGridConstants, blockUI, 
  CitaFactory,
  CitaServices,
  ModalReporteFactory
  ) {
    $scope.metodos = {}; // contiene todas las funciones 
    $scope.fArr = {}; // contiene todos los arrays generados por las funciones 
    $scope.mySelectionGrid = [];
    $scope.fBusqueda = {}; 

    $scope.fBusqueda.desde = $filter('date')(new Date(),'01-MM-yyyy');
    $scope.fBusqueda.desdeHora = '00';
    $scope.fBusqueda.desdeMinuto = '00';
    $scope.fBusqueda.hastaHora = 23;
    $scope.fBusqueda.hastaMinuto = 59;
    $scope.fBusqueda.hasta = $filter('date')(new Date(),'dd-MM-yyyy');

    $scope.fArr.listaEstados = [
      { id: 'ALL', descripcion: 'TODOS' }, 
      { id: '1', descripcion: 'ACTIVAS' },
      { id: '0', descripcion: 'ANULADAS' }
    ];
    $scope.fBusqueda.estado = $scope.fArr.listaEstados[1];

    $scope.btnBuscar = function(){ 
      $scope.gridOptions.enableFiltering = !$scope.gridOptions.enableFiltering;
      $scope.gridApi.core.notifyDataChange( uiGridConstants.dataChange.COLUMN );
    };
    var paginationOptions = {
      pageNumber: 1,
      firstRow: 0,
      pageSize: 100,
      sort: uiGridConstants.DESC,
      sortName: null,
      search: null
    };
    $scope.gridOptions = {
      rowHeight: 30,
      paginationPageSizes: [100, 500, 1000],
      paginationPageSize: 100,
      useExternalPagination: true,
      useExternalSorting: true,
      useExternalFiltering : true,
      enableGridMenu: true,
      enableRowSelection: true,
      enableSelectAll: true,
      enableFiltering: false,
      enableFullRowSelection: true,
      multiSelect: false,
      columnDefs: [ 
        { field: 'idcita', name: 'ci.idcita', displayName: 'ID', width: '75',  sort: { direction: uiGridConstants.DESC} },
        { field: 'idcitaspring', name: 'ci.idcitaspring', displayName: 'Id Spring', minWidth: 100, visible: false },
        { field: 'fecha_cita', name: 'ci.fecha_cita', displayName: 'Fecha Cita', minWidth: 100, enableFiltering: false },
        { field: 'hora', name: 'hora', displayName: 'Hora', minWidth: 100, enableFiltering: false },
        { field: 'fecha_registro', name: 'ci.fecha_registro', displayName: 'F. Registro', minWidth: 100, enableFiltering: false, visible: false },
        { field: 'numero_documento', name: 'cl.numero_documento', displayName: 'N° Documento', minWidth: 100, visible: false },
        { field: 'cliente', name: 'cliente', displayName: 'Cliente', minWidth: 180 },
        { field: 'medico', name: 'ci.medico', displayName: 'Médico', minWidth: 180 },
        { field: 'especialidad', name: 'ci.especialidad', displayName: 'Médico', minWidth: 120 },
        { field: 'estado_obj', type: 'object', name: 'estado_obj', displayName: 'ESTADO', width: '140', enableFiltering: false, enableSorting: false, enableColumnMenus: false, enableColumnMenu: false, 
          cellTemplate:'<div class="ui-grid-cell-contents">' + 
            '<label tooltip-placement="left" tooltip="{{ COL_FIELD.labelText }}" class=" label {{ COL_FIELD.claseLabel }} ml-xs">'+ 
            ' {{COL_FIELD.labelText}} </label>'+ 
            '</div>' 
        }
      ],
      onRegisterApi: function(gridApi) { 
        $scope.gridApi = gridApi;
        gridApi.selection.on.rowSelectionChanged($scope,function(row){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
          $scope.mySelectionGrid = gridApi.selection.getSelectedRows();
        });
        $scope.gridApi.core.on.sortChanged($scope, function(grid, sortColumns) { 
          if (sortColumns.length == 0) {
            paginationOptions.sort = null;
            paginationOptions.sortName = null;
          } else {
            paginationOptions.sort = sortColumns[0].sort.direction;
            paginationOptions.sortName = sortColumns[0].name;
          }
          $scope.metodos.getPaginationServerSide(true);
        });
        gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
          paginationOptions.pageNumber = newPage;
          paginationOptions.pageSize = pageSize;
          paginationOptions.firstRow = (paginationOptions.pageNumber - 1) * paginationOptions.pageSize;
          $scope.metodos.getPaginationServerSide(true);
        });
        $scope.gridApi.core.on.filterChanged( $scope, function(grid, searchColumns) {
          var grid = this.grid;
          paginationOptions.search = true; 
          paginationOptions.searchColumn = {
            'ci.idcita' : grid.columns[1].filters[0].term,
            'ci.idcitaspring' : grid.columns[2].filters[0].term,
            'cl.numero_documento' : grid.columns[6].filters[0].term,
            "CONCAT(COALESCE(cl.nombres,''), ' ', COALESCE(cl.apellido_paterno,''), ' ', COALESCE(cl.apellido_materno,''))" : grid.columns[7].filters[0].term,
            'ci.medico' : grid.columns[8].filters[0].term,
            'ci.especialidad' : grid.columns[9].filters[0].term
          }
          $scope.metodos.getPaginationServerSide();
        });
      }
    };
    paginationOptions.sortName = $scope.gridOptions.columnDefs[0].name; 
    $scope.metodos.getPaginationServerSide = function(loader) {
      if( loader ){
        blockUI.start('Procesando información...');
      }
      var arrParams = {
        paginate : paginationOptions,
        datos: $scope.fBusqueda
      };
      CitaServices.sListar(arrParams).then(function (rpta) { 
        if( rpta.datos.length == 0 ){
          rpta.paginate = { totalRows: 0 };
        }
        $scope.gridOptions.totalItems = rpta.paginate.totalRows;
        $scope.gridOptions.data = rpta.datos; 
        if( loader ){
          blockUI.stop(); 
        }
      });
      $scope.mySelectionGrid = [];
    };
    $scope.metodos.getPaginationServerSide(true); 
    // MAS ACCIONES
    $scope.btnVer = function(cita) { 
      var arrParams = {
        'metodos': $scope.metodos,
        'fArr': $scope.fArr,
        'cita': cita
      };
      CitaFactory.verCitaModal(arrParams); 
    }
    $scope.btnExportarListaExcel = function () {
      console.log('excel xd');
			var arrParams = {
				titulo: 'LISTADO DE CITAS',
				datos: {
					filtro: $scope.fBusqueda,
					paginate: paginationOptions,
					tituloAbv: 'LIST-CITA',
          titulo: 'LISTADO DE CITAS',
          salida: 'excel'
				}
			}
      arrParams.url = angular.patchURLCI + 'Cita/listado_citas_excel',
      ModalReporteFactory.getPopupReporte(arrParams);
    }
}]);

app.service("CitaServices",function($http, $q, handleBehavior) {
    return({
        sListar: sListar
    });
    function sListar(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Cita/listar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
});

app.factory("CitaFactory", function($uibModal, pinesNotifications, blockUI, CitaServices) { 
  var interfaz = {
    verCitaModal: function (arrParams) {
      blockUI.start('Abriendo formulario...');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Cita/ver_detalle_cita',
        size: 'md',
        backdrop: 'static',
        keyboard:false,
        controller: function ($scope, $uibModalInstance, arrParams) { 
          blockUI.stop(); 
          $scope.fDataCita = {};
          $scope.metodos = arrParams.metodos;
          $scope.fArr = arrParams.fArr;
          $scope.titleForm = 'Detalle de la cita';
          $scope.fDataCita = arrParams.cita;
          // if( arrParams.mySelectionGrid.length == 1 ){ 
          //   $scope.fData = arrParams.mySelectionGrid[0];
          // }else{
          //   alert('Seleccione una sola fila');
          // }

          $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
          }
          
        },
        resolve: {
          arrParams: function() {
            return arrParams;
          }
        }
      });
    }
  }
  return interfaz;
})

