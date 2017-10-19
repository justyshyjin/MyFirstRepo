( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'signupController', ['$rootScope','requestFactory','$uibModalInstance','$state','$scope','ngToast','$stateParams',function ( $rootScope, requestFactory, $uibModalInstance, $state, $scope, ngToast, $stateParams ) {
        $scope.examSelection = [];
        $scope.user = {'is_active' : 1,'name' : $rootScope.pass.name,'email' : $rootScope.pass.email,'acesstype' : 'web','login_type' : 'normal'};

        requestFactory.get( requestFactory.getUrl( 'allexams' ),  function ( response ) {
            if ( response.statusCode == 200 ) {
                $scope.exams = response.response.allexams;
            }
        }, function(){});baseValidator.setRules( {exam:"required",age : "required" ,name : "required|max:100",email : "required|max:100|email",phone : "required|numeric|min:6|max:10",password_confirmation : "required|min:6",password : "required|same:password_confirmation|min:6",} );

        $scope.cancel = function () {
            $rootScope.closePopUp($state);
        };
        $scope.phonecode = function ( code, val ) {
            angular.element( 'ng-model="inputText"' ).attr( 'placeholder', code );
            angular.element( '#formdropdown' ).html( val );
        }
        var date = angular.element('#age');

       var checkValue = function (str, max) {
          if (str.charAt(0) !== '0' || str == '00') {
            var num = parseInt(str);
            if (isNaN(num) || num <= 0 || num > max) num = 1;
            str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
          };
          return str;
        };

        $scope.dateKeyup =  function(e,date) {
          var input = date;
          if(e.keyCode == 8) return false;
          if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
          var values = input.split('-').map(function(v) {
            return v.replace(/\D/g, '')
          });
          if (values[0]) values[0] = checkValue(values[0], 31);
          if (values[1]) values[1] = checkValue(values[1], 12);
          var output = values.map(function(v, i) {
            return v.length == 2 && i < 2 ? v + '-' : v;
          });
          $scope.user.age = output.join('').substr(0, 10);
        }

        $scope.dateBlur =  function(e,date) {
          var input = date;
          var values = input.split('-').map(function(v, i) {
            return v.replace(/\D/g, '')
          });
          var output = '';

          if (values.length == 3) {
			var currDate = new Date();
            var year = values[2].length !== 4 ? currDate.getFullYear()-20 : parseInt(values[2]);
            var month = parseInt(values[1]) - 1;
            var day = parseInt(values[0]);
            var d = new Date(year, month, day);
            if (!isNaN(d)) {
              var dates = [d.getDate(),d.getMonth() + 1,d.getFullYear()];
              output = dates.map(function(v) {
                v = v.toString();
                return v.length == 1 ? '0' + v : v;
              }).join('-');
            };
          };
          angular.element( '#age' ).click();
          $scope.user.age =  output;
        }

        $scope.selectexam = function ( slug ) {
            var idx = $scope.examSelection.indexOf( slug );
            // Is currently selected
            if ( idx > -1 ) {
                $scope.examSelection.splice( idx, 1 );
            }
            // Is newly selected
            else {
                $scope.examSelection.push( slug );
            }
            $scope.user.exam = $scope.examSelection.join( ',' );
        }
        $scope.setname = 1;
        $scope.signup = function ( $event ) {
            if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                var authURI = 'auth/register';
                requestFactory.post( requestFactory.getUrl( authURI ), $scope.user, function ( response ) {
                    if ( response.statusCode == 200 ) {
                        var form = document.querySelector( 'form[name="signupForm"]' );
                        $scope.setname = 0;
                        form.action = 'auth/login';
                        $rootScope.httpLoaderLocalElement = 1;
                        form.submit()
                    }
                }, $scope.fillError );
            }
        }
        $scope.fillError = function ( response ) {
            if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
                angular.forEach( response.data.message, function ( message, key ) {
                    if ( typeof message == 'object' && message.length > 0 ) {
                        $scope.errors [key] = {has : true,message : message [0]};
                    }
                } );
            }
        }
    }] )

} )();


(function(){'use strict';var controllers=angular.module("app.controllers");controllers.factory('requestFactory',requestFactory);controllers.directive('baseValidator',validatorDirective);controllers.controller('newpasswordController',['$rootScope','requestFactory','$uibModalInstance','$state','$scope','ngToast',function($rootScope,requestFactory,$uibModalInstance,$state,$scope,ngToast){$scope.forgot={};baseValidator.setRules({email:"required|email"});$scope.cancel=function(){$uibModalInstance.dismiss('cancel');$rootScope.closePopUp($state);};$scope.submitForgotPassowrd=function($event){if(baseValidator.validateAngularForm($event.target,$scope)){var authURI='auth/forgotpassword';requestFactory.post(requestFactory.getUrl(authURI),$scope.forgot,function(response){if(response.statusCode==200){ngToast.create({className:'success',content:'<strong>'+response.message+'</strong>'});$state.go('login',{},{reload:true});$scope.forgot={};}},$scope.fillError);}}
$scope.fillError=function(response){if(response.status==422&&response.data.hasOwnProperty('message')){angular.forEach(response.data.message,function(message,key){if(typeof message=='object'&&message.length>0){$scope.errors[key]={has:true,message:message[0]};}});}}}])})();

(function(){'use strict';var controllers=angular.module("app.controllers");controllers.factory('requestFactory',requestFactory);controllers.directive('baseValidator',validatorDirective);controllers.controller('loginController',['$rootScope','requestFactory','$uibModalInstance','$state','$scope','ngToast',function($rootScope,requestFactory,$uibModalInstance,$state,$scope,ngToast){$scope.user={'login_type':'normal'};baseValidator.setRules({email:'required|email',password:'required|min:6'});$scope.cancel=function(){$rootScope.closePopUp($state);};$scope.loadterms=function(){$uibModalInstance.dismiss('cancel');setTimeout(function(){$state.go('staticContent',{'slug':'terms-and-condition'},{reload:false});},100);};$scope.login=function($event){if(baseValidator.validateAngularForm($event.target,$scope)){var authURI='auth/login';requestFactory.post(requestFactory.getUrl(authURI),$scope.user,function(response){if(response.statusCode==200){var form=document.querySelector('form[name="loginForm"]')
form.action=authURI;$rootScope.httpLoaderLocalElement=1;form.submit()}},$scope.fillError);}}
$scope.fillError=function(response){if(response.status==422&&response.data.hasOwnProperty('messages')){angular.forEach(response.data.messages,function(message,key){if(typeof message=='object'&&message.length>0){$scope.errors[key]={has:true,message:message[0]};}});}
if(response.status===401&&response.message!==''){$scope.user.password='';$scope.errors['email']={has:true,message:response.data.message};}}}])})();
(function(){'use strict';appRoute.config(['$stateProvider','$urlRouterProvider','$ocLazyLoadProvider',function($stateProvider,$urlRouterProvider,$ocLazyLoadProvider){$stateProvider.state("login",{url:"login",parent:"dashboard",onEnter:function($rootScope){$rootScope.login()}}).state("signup",{url:"signup",parent:"dashboard",onEnter:function($rootScope){$rootScope.signup();}}).state("newpassword",{url:"newpassword",parent:"dashboard",onEnter:function($rootScope){$rootScope.newpassword();}});}])})();
