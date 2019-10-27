'use strict';

/**
 * Config for the router
 */
angular.module('app')
  .run(
    [
      '$rootScope', '$state', '$stateParams',
      function ($rootScope,   $state,   $stateParams) {
          $rootScope.$state = $state;
          $rootScope.$stateParams = $stateParams;        
      }
    ]
  )
  .config(
    [
      '$stateProvider', '$urlRouterProvider', 'JQ_CONFIG', 'MODULE_CONFIG', 
      function ($stateProvider, $urlRouterProvider, JQ_CONFIG, MODULE_CONFIG) { 
        var layout = "tpl/app.html";
        if(window.location.href.indexOf("material") > 0){
          layout = "tpl/blocks/material.layout.html";
          $urlRouterProvider
            .otherwise('/app/dashboard');
        }else{
          $urlRouterProvider
            .otherwise('/app/dashboard');
        }
          
        $stateProvider
          .state('app', {
            abstract: true,
            url: '/app',
            templateUrl: layout
          })
          .state('app.dashboard', {
            url: '/dashboard',
            templateUrl: 'tpl/app_dashboard.html',
            resolve: load([
              'angular/controllers/chart.js',
            ])
          })
          .state('app.sliders-home', {
            url: '/sliders-home',
            templateUrl: 'tpl/sliders-home.html',
            resolve: load([
              'angular/controllers/SlidersHomeCtrl.js',
            ])
          })
          .state('app.staff-medico', {
            url: '/staff-medico',
            templateUrl: 'tpl/staff-medico.html',
            resolve: load([
              'angular/controllers/StaffMedicoCtrl.js',
              // 'angular/controllers/CategoriaClienteCtrl.js',
              'angular/controllers/HorarioCtrl.js',
              'angular/controllers/EspecialidadCtrl.js'
            ])
          })
          .state('app.testimonio', {
            url: '/testimonio',
            templateUrl: 'tpl/testimonio.html',
            resolve: load([
              'angular/controllers/TestimonioCtrl.js',
            ])
          })
          .state('app.especialidad', {
            url: '/especialidad',
            templateUrl: 'tpl/especialidad.html',
            resolve: load([
              'angular/controllers/EspecialidadCtrl.js' 
            ]) 
          })
          .state('app.servicio', { 
            url: '/servicio', 
            templateUrl: 'tpl/servicio.html', 
            resolve: load([
              'angular/controllers/ServicioCtrl.js'
            ]) 
          })
          .state('app.seguro', { 
            url: '/seguro', 
            templateUrl: 'tpl/seguro.html', 
            resolve: load([
              'angular/controllers/SeguroCtrl.js' 
            ]) 
          })
          .state('app.promocion', { 
            url: '/promocion', 
            templateUrl: 'tpl/promocion.html', 
            resolve: load([
              'angular/controllers/PromocionCtrl.js'
            ])
          })
          .state('app.blog', { 
            url: '/blog', 
            templateUrl: 'tpl/blog.html', 
            resolve: load([
              'angular/controllers/BlogCtrl.js'
            ]) 
          })
          .state('app.usuario', {
            url: '/usuario',
            templateUrl: 'tpl/usuario.html',
            resolve: load([
              'angular/controllers/UsuarioCtrl.js',   
              'angular/controllers/ColaboradorCtrl.js',
              'angular/controllers/UsuarioEmpresaAdminCtrl.js',
              'angular/controllers/EmpresaAdminCtrl.js'               
            ]) 
          })    
          .state('app.contacto', {
            url: '/contacto',
            templateUrl: 'tpl/contacto.html',
            resolve: load([      
              'angular/controllers/ContactoEmpresaCtrl.js', 
              'angular/controllers/ClienteEmpresaCtrl.js',
              'angular/controllers/ClienteCtrl.js'   
            ]) 
          })                                                                            
          .state('lockme', {
              url: '/lockme',
              templateUrl: 'tpl/page_lockme.html'
          })
          .state('access', {
              url: '/access',
              template: '<div ui-view class="fade-in-right-big smooth"></div>'
          })
          .state('access.login', {
              url: '/login',
              templateUrl: 'tpl/login.html',
              resolve: load( ['angular/controllers/Login.js'] )
          })
          // others
          .state('access.signup', {
              url: '/signup',
              templateUrl: 'tpl/page_signup.html',
              resolve: load( ['angular/controllers/signup.js'] )
          })
          .state('access.404', {
              url: '/404',
              templateUrl: 'tpl/page_404.html'
          })
          // mail
          .state('app.mail', {
              abstract: true,
              url: '/mail',
              templateUrl: 'tpl/mail.html',
              // use resolve to load other dependences
              resolve: load( ['angular/app/mail/mail.js','angular/app/mail/mail-service.js','moment'] )
          })
          .state('app.mail.list', {
              url: '/inbox/{fold}',
              templateUrl: 'tpl/mail.list.html'
          })
          .state('app.mail.detail', {
              url: '/{mailId:[0-9]{1,4}}',
              templateUrl: 'tpl/mail.detail.html'
          })
          .state('app.mail.compose', {
              url: '/compose',
              templateUrl: 'tpl/mail.new.html'
          });

        function load(srcs, callback) {
          return {
              deps: ['$ocLazyLoad', '$q',
                function( $ocLazyLoad, $q ){
                  var deferred = $q.defer();
                  var promise  = false;
                  srcs = angular.isArray(srcs) ? srcs : srcs.split(/\s+/);
                  if(!promise){
                    promise = deferred.promise;
                  }
                  angular.forEach(srcs, function(src) {
                    promise = promise.then( function(){
                      if(JQ_CONFIG[src]){
                        return $ocLazyLoad.load(JQ_CONFIG[src]);
                      }
                      angular.forEach(MODULE_CONFIG, function(module) {
                        if( module.name == src){
                          name = module.name;
                        }else{
                          name = src;
                        }
                      });
                      return $ocLazyLoad.load(name);
                    } );
                  });
                  deferred.resolve();
                  return callback ? promise.then(function(){ return callback(); }) : promise;
              }]
          }
        }
      }
    ]
  );
