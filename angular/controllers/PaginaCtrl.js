app.controller('PaginaCtrl', ['$scope', '$filter', '$uibModal', '$bootbox', '$log', '$timeout', 'pinesNotifications', 'uiGridConstants', 'blockUI', 
  'PaginaFactory',
  'PaginaServices',
  function($scope, $filter, $uibModal, $bootbox, $log, $timeout, pinesNotifications, uiGridConstants, blockUI, 
  PaginaFactory,
  PaginaServices
  ) {
    $scope.metodos = {}; // contiene todas las funciones 
    $scope.fArr = {}; // contiene todos los arrays generados por las funciones 
    $scope.mySelectionGrid = [];
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
        { field: 'idpagina', name: 'pag.idpagina', displayName: 'ID', width: '75',  sort: { direction: uiGridConstants.DESC} },
        { field: 'nombre', name: 'pag.nombre', displayName: 'Nombre de Página', minWidth: 100 },
        { field: 'titulo_seo', name: 'pag.titulo_seo', displayName: 'Título SEO', minWidth: 200 },
        { field: 'meta_content_seo', name: 'pag.meta_content_seo', displayName: 'Contenido SEO', minWidth: 200 }
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
            'pag.idpagina' : grid.columns[1].filters[0].term,
            'pag.nombre' : grid.columns[2].filters[0].term,
            'pag.titulo_seo' : grid.columns[3].filters[0].term,
            'pag.meta_content_seo' : grid.columns[4].filters[0].term
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
        paginate : paginationOptions
      };
      PaginaServices.sListar(arrParams).then(function (rpta) { 
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
    // $scope.btnNuevo = function() { 
    //   var arrParams = {
    //     'metodos': $scope.metodos,
    //     'fArr': $scope.fArr 
    //   }
    //   PaginaFactory.regModal(arrParams); 
    // }
    $scope.btnEditar = function() { 
      var arrParams = {
        'metodos': $scope.metodos,
        'mySelectionGrid': $scope.mySelectionGrid,
        'fArr': $scope.fArr 
      }
      PaginaFactory.editModal(arrParams); 
    }
    // $scope.btnAnular = function() { 
    //   var pMensaje = '¿Realmente desea anular el registro?';
    //   $bootbox.confirm(pMensaje, function(result) {
    //     if(result){
    //       var arrParams = {
    //         idpagina: $scope.mySelectionGrid[0].idpagina 
    //       };
    //       blockUI.start('Procesando información...');
    //       PaginaServices.sAnular(arrParams).then(function (rpta) {
    //         if(rpta.flag == 1){
    //           var pTitle = 'OK!';
    //           var pType = 'success';
    //           $scope.metodos.getPaginationServerSide();
    //         }else if(rpta.flag == 0){
    //           var pTitle = 'Error!';
    //           var pType = 'danger';
    //         }else{
    //           alert('Error inesperado');
    //         }
    //         pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
    //         blockUI.stop(); 
    //       });
    //     }
    //   });
    // }
}]);

app.service("PaginaServices",function($http, $q, handleBehavior) {
    return({
        sListar: sListar,
        sEditar: sEditar,
    });
    function sListar(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Pagina/listar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sEditar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"Pagina/editar",
            data : datos,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
});

app.factory("PaginaFactory", function($uibModal, pinesNotifications, blockUI, PaginaServices) { 
  var interfaz = {
    editModal: function (arrParams) {
      blockUI.start('Abriendo formulario...');
      $uibModal.open({ 
        templateUrl: angular.patchURLCI+'Pagina/ver_popup_formulario',
        size: 'md',
        backdrop: 'static',
        keyboard:false,
        controller: function ($scope, $uibModalInstance, arrParams) { 
          blockUI.stop(); 
          $scope.fData = {};
          $scope.metodos = arrParams.metodos;
          $scope.fArr = arrParams.fArr; 
          if( arrParams.mySelectionGrid.length == 1 ){ 
            $scope.fData = arrParams.mySelectionGrid[0];
          }else{
            alert('Seleccione una sola fila');
          }
          $scope.titleForm = 'Edición de Página';
          $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
          }
          $scope.aceptar = function () { 
            blockUI.start('Procesando información...');
            var formData = new FormData();
            angular.forEach($scope.fData,function (index,val) { 
              formData.append(val,index);
            });
            PaginaServices.sEditar(formData).then(function (rpta) {
              if(rpta.flag == 1){
                var pTitle = 'OK!';
                var pType = 'success';
                $uibModalInstance.dismiss('cancel');
                if(typeof $scope.metodos.getPaginationServerSide == 'function'){
                  $scope.metodos.getPaginationServerSide(true);
                }
              }else if(rpta.flag == 0){
                var pTitle = 'Error!';
                var pType = 'danger';
              }else{
                alert('Error inesperado');
              }
              blockUI.stop(); 
              pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
            });
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
});
