(function(){
'use strict';
appRoute.config(['$stateProvider','$urlRouterProvider','$ocLazyLoadProvider',function($stateProvider,$urlRouterProvider,$ocLazyLoadProvider) {
	$stateProvider.state( "login", {
         url : "/login",
         controller : 'loginController',
         controllerAs : 'loginCtrl',
         templateUrl : 'loginModel',
         resolve : {
             data : function ( requestFactory, $http ) {
                 return $http.get( requestFactory.getUrl( 'loginModel' ), {
                     headers : requestFactory.getHeaders()
                 } );
             },
             loadCtrl : [
                     '$ocLazyLoad', function ( $ocLazyLoad ) {
                         var load = {
                             serie : true,
                             files : [],
                             cache : true
                         };
                        
                         $ocLazyLoad.getModuleConfig( 'login' ).files.forEach( function ( files ) {
                             load.files.push( files );
                         } );
                    
                         if ( load.files.length ) {
                             return $ocLazyLoad.load( load );
                         }
                     }
             ],
         }
     } );
$stateProvider.state( "newpassword", {
         url : "/newpassword",
         controller : 'newpasswordController',
         controllerAs : 'newpwdCtrl',
         templateUrl : 'newpasswordModel',
         resolve : {
             data : function ( requestFactory, $http ) {
                 return $http.get( requestFactory.getUrl( 'newpasswordModel' ), {
                     headers : requestFactory.getHeaders()
                 } );
             },
             loadCtrl : [
                     '$ocLazyLoad', function ( $ocLazyLoad ) {
                         var load = {
                             serie : true,
                             files : [],
                             cache : true
                         };
                        
                         $ocLazyLoad.getModuleConfig( 'newpassword' ).files.forEach( function ( files ) {
                             load.files.push( files );
                         } );
                    
                         if ( load.files.length ) {
                             return $ocLazyLoad.load( load );
                         }
                     }
             ],
         }
     } );
      $stateProvider.state("signup",{
          url: "signup", 
          parent:"dashboard",
          onEnter: ['$stateParams', '$state', '$uibModal', function($stateParams, $state, $uibModal) {
              angular.element('div.login-popup.in[role="dialog"]').remove();
              $uibModal.open({
                  template: '<div ui-view="modal" class="text-center"></div>',
                  animation: true,
                  size: 'md',
                  windowClass: 'signup-popup',
                  controller: 'signupController',
              }).result.finally(function() {
                  $state.go('dashboard',{},{reload:true});
              });
          }],
          views: {
              'modal@': {
                  templateUrl: 'signUpModel'
              }
          },
          resolve : {
              loadCtrl: ['$ocLazyLoad', function ($ocLazyLoad) {
                  var load = { serie : true, files :[],cache: true};
                   /**
                     * init the dependencies array
                     * 
                     * @type {Array}
                     */
                    $ocLazyLoad.getModuleConfig('signup').files.forEach(function(files){
                      load.files.push(files);
                    });
                  /**
                     * check if the lazy load data exists
                     */
                  if(load.files.length){
                     return $ocLazyLoad.load(load);
                    }
                }],
          }
    });
    }])

})();
