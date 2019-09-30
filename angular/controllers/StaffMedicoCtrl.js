app.controller('StaffMedicoCtrl', ['$scope', '$filter', '$uibModal', '$bootbox', '$log', '$timeout', 'pinesNotifications', 'uiGridConstants', 'blockUI', 
	'StaffMedicoFactory',
	'StaffMedicoServices',
	// 'CategoriaClienteServices', 
	'HorarioServices', 
	'EspecialidadServices',
	function($scope, $filter, $uibModal, $bootbox, $log, $timeout, pinesNotifications, uiGridConstants, blockUI, 
	StaffMedicoFactory,
	StaffMedicoServices,
	// CategoriaClienteServices,
	HorarioServices,
	EspecialidadServices
	) {
			$scope.metodos = {}; // contiene todas las funciones 
			$scope.fArr = {}; // contiene todos los arrays generados por las funciones 
	  	$scope.mySelectionGrid = [];
	  	var paginationOptions = {
	      pageNumber: 1,
	      firstRow: 0,
	      pageSize: 10,
	      sort: uiGridConstants.DESC,
	      sortName: null,
	      search: null
	  	};
	  	$scope.gridOptions = {
		    rowHeight: 30,
		    paginationPageSizes: [10, 50, 100, 500, 1000],
		    paginationPageSize: 10,
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
		      { field: 'idmedico', name: 'idmedico', displayName: 'ID', width: '70',  sort: { direction: uiGridConstants.DESC} },
		      { field: 'nombres', name: 'nombres', displayName: 'Nombres', minWidth: 150 },
		      { field: 'ap_paterno', name: 'ap_paterno', displayName: 'Apellido Pat.', minWidth: 120 },
		      { field: 'ap_materno', name: 'ap_materno', displayName: 'Apellido Mat.', minWidth: 120 },
		      { field: 'cmp', name: 'cmp', displayName: 'CMP.', minWidth: 80 },
		      { field: 'estado', type: 'object', name: 'estado', displayName: 'ESTADO', width: '95', enableFiltering: false, enableSorting: false, enableColumnMenus: false, enableColumnMenu: false, 
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
		        //console.log(sortColumns);
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
		          'me.idmedico' : grid.columns[1].filters[0].term,
		          'me.nombres' : grid.columns[2].filters[0].term,
		          'me.ap_paterno' : grid.columns[3].filters[0].term,
		          'me.ap_materno' : grid.columns[4].filters[0].term,
		          'me.cmp' : grid.columns[5].filters[0].term
		        }
		        $scope.metodos.getPaginationServerSide();
		      });
		    }
			};
			paginationOptions.sortName = $scope.gridOptions.columnDefs[0].name; 
		  $scope.btnBuscar = function(){ 
			  $scope.gridOptions.enableFiltering = !$scope.gridOptions.enableFiltering;
			  $scope.gridApi.core.notifyDataChange( uiGridConstants.dataChange.COLUMN );
			};
			
			// $scope.metodos.listaColaboradores = function(myCallback) { 
			// 	var myCallback = myCallback || function() { };
			// 	EspecialidadServices.sListarCbo().then(function(rpta) {
			// 		$scope.fArr.listaColaboradores = rpta.datos; 
			// 		myCallback();
			// 	});
			// };
			$scope.metodos.getPaginationServerSide = function(loader) {
			  if( loader ){
			  	blockUI.start('Procesando información...');
			  }
			  var arrParams = {
			    paginate : paginationOptions
			  };
			  StaffMedicoServices.sListar(arrParams).then(function (rpta) { 
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
			$scope.btnNuevo = function() { 
				var arrParams = {
					'metodos': $scope.metodos,
					'fArr': $scope.fArr 
				}
				StaffMedicoFactory.regMedicoModal(arrParams); 
			}
			$scope.btnEditar = function() { 
				var arrParams = {
					'metodos': $scope.metodos,
					'mySelectionGrid': $scope.mySelectionGrid,
					'fArr': $scope.fArr,
					'fSessionCI': $scope.fSessionCI 
				}
				StaffMedicoFactory.editMedicoModal(arrParams); 
			}
			$scope.btnHorarios = function() { 
				blockUI.start('Abriendo formulario...');
				$uibModal.open({ 
		      templateUrl: angular.patchURLCI+'StaffMedico/ver_popup_horarios',
		      size: 'lg',
		      backdrop: 'static',
		      keyboard:false,
		      scope: $scope,
		      controller: function ($scope, $uibModalInstance) { 
		      	blockUI.stop(); 
		      	$scope.fData = {};
		      	$scope.fHorario = {};
		      	$scope.editClassForm = null;
		      	$scope.tituloBloque = 'Agregar Horario';
		      	$scope.contBotonesReg = true;
		      	$scope.contBotonesEdit = false;
		      	if( $scope.mySelectionGrid.length == 1 ){ 
		          $scope.fData = $scope.mySelectionGrid[0];
		        }else{
		          alert('Seleccione una sola fila');
		        }
		      	$scope.titleForm = 'Horarios';
		      	$scope.cancel = function () {
		      	  $uibModalInstance.dismiss('cancel');
		      	} 
		    //   	$scope.btnBuscarHorarios = function(){
						//   $scope.gridOptionsHorarios.enableFiltering = !$scope.gridOptionsHorarios.enableFiltering;
						//   $scope.gridApiHorario.core.notifyDataChange( uiGridConstants.dataChange.COLUMN );
						// };
		      	var paginationOptionsHorarios = {
				      pageNumber: 1,
				      firstRow: 0,
				      pageSize: 10,
				      sort: uiGridConstants.DESC,
				      sortName: null,
				      search: null
					  };
						$scope.gridOptionsHorarios = { 
					    rowHeight: 30,
					    paginationPageSizes: [10, 50, 100, 500, 1000],
					    paginationPageSize: 10,
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
					      { field: 'id', name: 'idhorario', displayName: 'ID', visible: false, width: '50',  sort: { direction: uiGridConstants.DESC} },
					      { field: 'dia', name: 'dia', displayName: 'Día', width: 150 },
					      { field: 'hora_inicio', name: 'hora_inicio', displayName: 'Horario Inicio', width: 200 },
					      { field: 'hora_fin', name: 'hora_fin', displayName: 'Horario Fin', width: 200 }
					    ],
					    onRegisterApi: function(gridApiHorario) { 
					      $scope.gridApiHorario = gridApiHorario;
					      gridApiHorario.selection.on.rowSelectionChanged($scope,function(row){
					        $scope.mySelectionGridHorario = gridApiHorario.selection.getSelectedRows(); 
					        // EDICIÓN DE CONTACTO 
						      if( $scope.mySelectionGridHorario.length == 1 ){
						      	$scope.editClassForm = ' edit-form'; 
						      	$scope.tituloBloque = 'Edición de Horario';
						      	$scope.contBotonesReg = false;
						      	$scope.contBotonesEdit = true;
						      	$scope.fHorario = $scope.mySelectionGridHorario[0];
						      }else{
						      	$scope.editClassForm = null; 
						      	$scope.tituloBloque = 'Agregar Horario';
						      	$scope.contBotonesReg = true;
						      	$scope.contBotonesEdit = false;
						      	$scope.fHorario = {};
						      }
						      /* END */
					      });
					      gridApiHorario.selection.on.rowSelectionChangedBatch($scope,function(rows){
					        $scope.mySelectionGridHorario = gridApiHorario.selection.getSelectedRows();
					      });

					      $scope.gridApiHorario.core.on.sortChanged($scope, function(grid, sortColumns) { 
					        if (sortColumns.length == 0) {
					          paginationOptionsHorarios.sort = null;
					          paginationOptionsHorarios.sortName = null;
					        } else {
					          paginationOptionsHorarios.sort = sortColumns[0].sort.direction;
					          paginationOptionsHorarios.sortName = sortColumns[0].name;
					        }
					        $scope.metodos.getPaginationServerSideHorarios(true);
					      });
					      gridApiHorario.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
					        paginationOptionsHorarios.pageNumber = newPage;
					        paginationOptionsHorarios.pageSize = pageSize;
					        paginationOptionsHorarios.firstRow = (paginationOptionsHorarios.pageNumber - 1) * paginationOptionsHorarios.pageSize;
					        $scope.metodos.getPaginationServerSideHorarios(true);
					      });
					      // $scope.gridApiHorario.core.on.filterChanged( $scope, function(grid, searchColumns) {
					      //   var grid = this.grid;
					      //   paginationOptionsHorarios.search = true; 
					      //   paginationOptionsHorarios.searchColumn = {
					      //     'co.idcontacto' : grid.columns[1].filters[0].term,
					      //     'co.nombres' : grid.columns[2].filters[0].term,
					      //     'co.apellidos' : grid.columns[3].filters[0].term,
					      //     'co.telefono_fijo' : grid.columns[4].filters[0].term,
					      //     'co.telefono_movil' : grid.columns[5].filters[0].term,
					      //     'co.email' : grid.columns[6].filters[0].term 
					      //   }
					      //   $scope.metodos.getPaginationServerSideHorarios();
					      // }); 
					    }
						};
						$scope.quitarHorario = function() {
							var pMensaje = '¿Realmente desea anular el registro?';
					      $bootbox.confirm(pMensaje, function(result) {
					        if(result){
					        	var arrParams = {
					        		idhorario: $scope.fHorario.idhorario
					        	}
					        	blockUI.start('Procesando información...');
					          HorarioServices.sQuitarHorario(arrParams).then(function (rpta) {
					            if(rpta.flag == 1){
					              var pTitle = 'OK!';
					              var pType = 'success';
					              $scope.metodos.getPaginationServerSideHorarios();
					              $scope.editClassForm = null; 
								      	$scope.tituloBloque = 'Agregar Horario';
								      	$scope.contBotonesReg = true;
								      	$scope.contBotonesEdit = false;
					            }else if(rpta.flag == 0){
					              var pTitle = 'Error!';
					              var pType = 'danger';
					            }else{
					              alert('Error inesperado');
					            }
					            pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
					            blockUI.stop(); 
					          });
					        }
					      });
						}
						$scope.actualizarHorario = function() { 
							// console.log('click me');
							blockUI.start('Procesando información...');
		          HorarioServices.sActualizarHorario($scope.fHorario).then(function (rpta) {
		            if(rpta.flag == 1){
		              var pTitle = 'OK!';
		              var pType = 'success';
		              $scope.fHorario = {};
		              $scope.metodos.getPaginationServerSideHorarios(true); 
		              $scope.editClassForm = null; 
					      	$scope.tituloBloque = 'Agregar Horario';
					      	$scope.contBotonesReg = true;
					      	$scope.contBotonesEdit = false;
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
						paginationOptionsHorarios.sortName = $scope.gridOptionsHorarios.columnDefs[0].name;
						$scope.metodos.getPaginationServerSideHorarios = function(loader) {
						  if( loader ){
						  	blockUI.start('Procesando información...');
						  }
						  var arrParams = { 
						    paginate : paginationOptionsHorarios,
						    datos: $scope.fData 
						  };
						  HorarioServices.sListarHorarios(arrParams).then(function (rpta) { 
						    $scope.gridOptionsHorarios.totalItems = rpta.paginate.totalRows;
						    $scope.gridOptionsHorarios.data = rpta.datos; 
						    if( loader ){
						    	blockUI.stop(); 
						    }
						  });
						  $scope.mySelectionGridHorario = [];
						};
						$scope.metodos.getPaginationServerSideHorarios(true); 
		      	$scope.agregarHorario = function () { 
		      		blockUI.start('Procesando información...');
		      		$scope.fHorario.idmedico = $scope.fData.idmedico; 
		          HorarioServices.sAgregarHorario($scope.fHorario).then(function (rpta) {
		            if(rpta.flag == 1){
		              var pTitle = 'OK!';
		              var pType = 'success';
		              $scope.fHorario = {};
		              $scope.metodos.getPaginationServerSideHorarios(true); 
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
		      }
		    });
			}
			$scope.btnEspecialidades = function() { 
				blockUI.start('Abriendo formulario...');
				$uibModal.open({ 
		      templateUrl: angular.patchURLCI+'StaffMedico/ver_popup_especialidades',
		      size: 'lg',
		      backdrop: 'static',
		      keyboard:false,
		      scope: $scope,
		      controller: function ($scope, $uibModalInstance) { 
		      	blockUI.stop(); 
		      	$scope.fData = {};
		      	$scope.fEspMedico = {};
		      	$scope.editClassForm = null;
		      	$scope.tituloBloque = 'Agregar Especialidad';
		      	$scope.contBotonesReg = true;
		      	$scope.contBotonesEdit = false;
		      	if( $scope.mySelectionGrid.length == 1 ){ 
		          $scope.fData = $scope.mySelectionGrid[0];
		        }else{
		          alert('Seleccione una sola fila');
		        }
		      	$scope.titleForm = 'Especialidades';
		      	$scope.cancel = function () {
		      	  $uibModalInstance.dismiss('cancel');
		      	}
		      	$scope.metodos.listaEspecialidades = function(myCallback) {
							var myCallback = myCallback || function() { };
							EspecialidadServices.sListarCbo().then(function(rpta) {
								$scope.fArr.listaEspecialidades = rpta.datos; 
								myCallback();
							});
						};
		      	var paginationOptionsEsp = {
				      pageNumber: 1,
				      firstRow: 0,
				      pageSize: 10,
				      sort: uiGridConstants.DESC,
				      sortName: null,
				      search: null
					  };
						$scope.gridOptionsEsp = { 
					    rowHeight: 30,
					    paginationPageSizes: [10, 50, 100, 500, 1000],
					    paginationPageSize: 10,
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
					      { field: 'idespecialidadmedico', name: 'idespecialidadmedico', displayName: 'ID', visible: false, width: '100',  sort: { direction: uiGridConstants.DESC} },
					      { field: 'nombre', name: 'nombre', displayName: 'Nombre', width: 250 },
					      { field: 'uri', name: 'uri', displayName: 'URI', width: 200 }
					    ],
					    onRegisterApi: function(gridApiEsp) { 
					      $scope.gridApiEsp = gridApiEsp;
					      gridApiEsp.selection.on.rowSelectionChanged($scope,function(row){
					        $scope.mySelectionGridEsp = gridApiEsp.selection.getSelectedRows(); 
					        // EDICIÓN DE CONTACTO 
						      if( $scope.mySelectionGridEsp.length == 1 ){
						      	$scope.editClassForm = ' edit-form'; 
						      	$scope.tituloBloque = 'Edición de Especialidad';
						      	$scope.contBotonesReg = false;
						      	$scope.contBotonesEdit = true;
						      	$scope.fEspMedico = $scope.mySelectionGridEsp[0];
						      	var myCallBackSM = function() { 
                    var objIndex = $scope.fArr.listaEspecialidades.filter(function(obj) {         
                      return obj.id == $scope.fEspMedico.idespecialidad;
                    }).shift(); 
                    $scope.fEspMedico.especialidad = objIndex; 
                  }
                  $scope.metodos.listaEspecialidades(myCallBackSM);
						      }else{
						      	$scope.editClassForm = null; 
						      	$scope.tituloBloque = 'Agregar Especialidad';
						      	$scope.contBotonesReg = true;
						      	$scope.contBotonesEdit = false;
						      	$scope.fEspMedico = {};
						      }
						      /* END */
					      });
					      gridApiEsp.selection.on.rowSelectionChangedBatch($scope,function(rows){
					        $scope.mySelectionGridEsp = gridApiEsp.selection.getSelectedRows();
					      });

					      $scope.gridApiEsp.core.on.sortChanged($scope, function(grid, sortColumns) { 
					        if (sortColumns.length == 0) {
					          paginationOptionsEsp.sort = null;
					          paginationOptionsEsp.sortName = null;
					        } else {
					          paginationOptionsEsp.sort = sortColumns[0].sort.direction;
					          paginationOptionsEsp.sortName = sortColumns[0].name;
					        }
					        $scope.metodos.getPaginationServerSideEsp(true);
					      });
					      gridApiEsp.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
					        paginationOptionsEsp.pageNumber = newPage;
					        paginationOptionsEsp.pageSize = pageSize;
					        paginationOptionsEsp.firstRow = (paginationOptionsEsp.pageNumber - 1) * paginationOptionsEsp.pageSize;
					        $scope.metodos.getPaginationServerSideEsp(true);
					      });
					    }
						};
						$scope.quitarEspecialidad = function() {
							var pMensaje = '¿Realmente desea anular el registro?';
					      $bootbox.confirm(pMensaje, function(result) {
					        if(result){
					        	var arrParams = {
					        		idespecialidadmedico: $scope.fEspMedico.idespecialidadmedico
					        	}
					        	blockUI.start('Procesando información...');
					          EspecialidadServices.sAnularEspMedico(arrParams).then(function (rpta) {
					            if(rpta.flag == 1){
					              var pTitle = 'OK!';
					              var pType = 'success';
					              $scope.metodos.getPaginationServerSideEsp();
					              $scope.editClassForm = null; 
								      	$scope.tituloBloque = 'Agregar Especialidad';
								      	$scope.contBotonesReg = true;
								      	$scope.contBotonesEdit = false;
					            }else if(rpta.flag == 0){
					              var pTitle = 'Error!';
					              var pType = 'danger';
					            }else{
					              alert('Error inesperado');
					            }
					            pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
					            blockUI.stop(); 
					          });
					        }
					      });
						}
						$scope.actualizarEspecialidad = function() { 
							// console.log('click me');
							blockUI.start('Procesando información...');
		          EspecialidadServices.sEditarEspMedico($scope.fEspMedico).then(function (rpta) {
		            if(rpta.flag == 1){
		              var pTitle = 'OK!';
		              var pType = 'success';
		              $scope.fEspMedico = {};
		              $scope.metodos.getPaginationServerSideEsp(true); 
		              $scope.editClassForm = null; 
					      	$scope.tituloBloque = 'Editar Especialidad';
					      	$scope.contBotonesReg = true;
					      	$scope.contBotonesEdit = false;
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
						paginationOptionsEsp.sortName = $scope.gridOptionsEsp.columnDefs[0].name;
						$scope.metodos.getPaginationServerSideEsp = function(loader) {
						  if( loader ){
						  	blockUI.start('Procesando información...');
						  }
						  var arrParams = { 
						    paginate : paginationOptionsEsp,
						    datos: $scope.fData 
						  };
						  EspecialidadServices.sListarEspMedico(arrParams).then(function (rpta) { 
						    $scope.gridOptionsEsp.totalItems = rpta.paginate.totalRows;
						    $scope.gridOptionsEsp.data = rpta.datos; 
						    if( loader ){
						    	blockUI.stop(); 
						    }
						  });
						  $scope.mySelectionGridEsp = [];
						  var myCallBackSM = function() { 
	              $scope.fArr.listaEspecialidades.splice(0,0,{ id : '0', descripcion:'--Seleccione especialidad--'}); 
	              $scope.fEspMedico.especialidad = $scope.fArr.listaEspecialidades[0]; 
	            }
	            $scope.metodos.listaEspecialidades(myCallBackSM); 
						};
						$scope.metodos.getPaginationServerSideEsp(true); 
		      	$scope.agregarEspecialidad = function () { 
		      		blockUI.start('Procesando información...');
		      		$scope.fEspMedico.idmedico = $scope.fData.idmedico; 
		          EspecialidadServices.sAgregarEspMedico($scope.fEspMedico).then(function (rpta) {
		            if(rpta.flag == 1){
		              var pTitle = 'OK!';
		              var pType = 'success';
		              $scope.fEspMedico = {};
		              $scope.metodos.getPaginationServerSideEsp(true); 
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
		      }
		    });
			}
			$scope.btnOcultar = function() { 
		    var pMensaje = '¿Realmente desea ocultar al médico?';
		    $bootbox.confirm(pMensaje, function(result) {
		      if(result){
		        var arrParams = {
		          idmedico: $scope.mySelectionGrid[0].idmedico 
		        };
		        blockUI.start('Procesando información...');
		        StaffMedicoServices.sOcultar(arrParams).then(function (rpta) {
		          if(rpta.flag == 1){
		            var pTitle = 'OK!';
		            var pType = 'success';
		            $scope.metodos.getPaginationServerSide();
		          }else if(rpta.flag == 0){
		            var pTitle = 'Error!';
		            var pType = 'danger';
		          }else{
		            alert('Error inesperado');
		          }
		          pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
		          blockUI.stop(); 
		        });
		      }
		    });
		  }
		  $scope.btnMostrar = function() { 
		    var pMensaje = '¿Realmente desea mostrar al médico?';
		    $bootbox.confirm(pMensaje, function(result) {
		      if(result){
		        var arrParams = {
		          idmedico: $scope.mySelectionGrid[0].idmedico 
		        };
		        blockUI.start('Procesando información...');
		        StaffMedicoServices.sMostrar(arrParams).then(function (rpta) {
		          if(rpta.flag == 1){
		            var pTitle = 'OK!';
		            var pType = 'success';
		            $scope.metodos.getPaginationServerSide();
		          }else if(rpta.flag == 0){
		            var pTitle = 'Error!';
		            var pType = 'danger';
		          }else{
		            alert('Error inesperado');
		          }
		          pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
		          blockUI.stop(); 
		        });
		      }
		    });
		  }
		  $scope.btnEliminar = function() { 
		    var pMensaje = '¿Realmente desea eliminar al médico?';
		    $bootbox.confirm(pMensaje, function(result) {
		      if(result){
		        var arrParams = {
		          idmedico: $scope.mySelectionGrid[0].idmedico 
		        };
		        blockUI.start('Procesando información...');
		        StaffMedicoServices.sEliminar(arrParams).then(function (rpta) {
		          if(rpta.flag == 1){
		            var pTitle = 'OK!';
		            var pType = 'success';
		            $scope.metodos.getPaginationServerSide();
		          }else if(rpta.flag == 0){
		            var pTitle = 'Error!';
		            var pType = 'danger';
		          }else{
		            alert('Error inesperado');
		          }
		          pinesNotifications.notify({ title: pTitle, text: rpta.message, type: pType, delay: 2500 });
		          blockUI.stop(); 
		        });
		      }
		    });
		  }
}]);

app.service("StaffMedicoServices",function($http, $q, handleBehavior) {
    return({
        sListar: sListar,
        sRegistrar: sRegistrar,
        sEditar: sEditar,
        sOcultar: sOcultar,
        sMostrar: sMostrar,
        sEliminar: sEliminar
    });
    function sListar(datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/listar_staff",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sRegistrar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/registrar",
            data : datos,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sEditar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/editar",
            data : datos,
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sEliminar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/eliminar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sOcultar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/ocultar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
    function sMostrar (datos) {
      var request = $http({
            method : "post",
            url : angular.patchURLCI+"StaffMedico/mostrar",
            data : datos
      });
      return (request.then(handleBehavior.success,handleBehavior.error));
    }
});

app.factory("StaffMedicoFactory", function($uibModal, pinesNotifications, blockUI, StaffMedicoServices) { 
	var interfaz = {
		regMedicoModal: function (arrParams) {
			blockUI.start('Abriendo formulario...');
			$uibModal.open({ 
	      templateUrl: angular.patchURLCI+'StaffMedico/ver_popup_formulario',
	      size: 'md',
	      backdrop: 'static',
	      keyboard:false,
	      controller: function ($scope, $uibModalInstance, arrParams) { 
	      	blockUI.stop(); 
	      	$scope.fData = {};
	      	// $scope.fData.foto = 'noimage.jpg';
	      	$scope.fData.sexo = 'M';
	      	$scope.metodos = arrParams.metodos;
	      	$scope.fArr = arrParams.fArr;
	      	$scope.titleForm = 'Registro de Médico';
	      	$scope.cancel = function () {
	      	  $uibModalInstance.dismiss('cancel');
	      	}

	      	$scope.aceptar = function () { 
	      		blockUI.start('Procesando información...');
	      		var formData = new FormData();
	      		angular.forEach($scope.fData,function (index,val) { 
			        formData.append(val,index);
			      });
	          StaffMedicoServices.sRegistrar(formData).then(function (rpta) {
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
		},
		editMedicoModal: function (arrParams) {
			blockUI.start('Abriendo formulario...');
			$uibModal.open({ 
	      templateUrl: angular.patchURLCI+'StaffMedico/ver_popup_formulario',
	      size: 'md',
	      backdrop: 'static',
	      keyboard:false,
	      controller: function ($scope, $uibModalInstance, arrParams) { 
	      	blockUI.stop(); 
	      	$scope.fData = {};
	      	$scope.metodos = arrParams.metodos;
	      	$scope.fArr = arrParams.fArr;
	      	// $scope.disabledVendedor = false;
	      	if( arrParams.mySelectionGrid.length == 1 ){ 
	          $scope.fData = arrParams.mySelectionGrid[0];
	        }else{
	          alert('Seleccione una sola fila');
	        }
	      	$scope.titleForm = 'Edición de Médico';
	      	$scope.cancel = function () {
	      	  $uibModalInstance.dismiss('cancel');
	      	}
	      	$scope.aceptar = function () { 
	      		blockUI.start('Procesando información...');
	      		var formData = new FormData();
	      		angular.forEach($scope.fData,function (index,val) { 
			        formData.append(val,index);
			      });
	          StaffMedicoServices.sEditar(formData).then(function (rpta) {
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
