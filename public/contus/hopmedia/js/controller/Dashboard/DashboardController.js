( function () {
    'use strict';

    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'dashboardController', ['$scope', 'requestFactory','$state', '$rootScope','$timeout','$location', function ( $scope, requestFactory, $state, $rootScope, $timeout, $location ) {
         $scope.user={};
         $scope.user = {'login_type':'normal'};
        
            baseValidator.setRules( {
                name : 'required|min:6',
                email : 'required|email|unique:users',
                password : 'required|confirmed|min:6',
                confirm_password : "required|same:password",
                company : 'required',
                phone:'filled|numeric|min:6|max:10|unique:users',
                domain:'required|unique:users'
            } );
            $scope.validationmsg = false;
            $scope.showResponseMessage = false;
            $rootScope.httpLoaderLocalElement = 1;
            $timeout(function() {
                $rootScope.httpLoaderLocalElement = 0;
            }, 2000);
            
            if($location.path()=='/'){
                 angular.element('body').addClass('home');
            }else{
                angular.element('body').removeClass('home');
            }

            /**
           * Function to login user.
           */
            $scope.login = function ( $event ) { 
                var terms=angular.element('input[name="Signinterms"]');;
                if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {  
                   var authURI = 'auth/login'; 
                   if (terms[0].checked == false || terms[0].checked == undefined){ 
                        $scope.validationmsg = true;
                    }else{ 
                        requestFactory.post( requestFactory.getUrl( authURI ), $scope.user, function ( response ) { 
                            if(response.statusCode == 200){ 
                                $scope.validationmsg = false;
                                var form = document.querySelector('form[name="loginForm"]');
                                form.action=authURI;
                                $rootScope.httpLoaderLocalElement = 1;
                                form.submit();
                            }
                        }, $scope.fillError );
                    }                   
                }
            }


            /**
           * Function to add broadcasters .
           */
            $scope.save = function ( $event ) {   
                var terms = angular.element('input[name="Signupterms"]');
                if (baseValidator.validateAngularForm($event.target,$scope)) { 
                    if(terms[0].checked==false || terms[0].checked==undefined){
                        $scope.validationmsg = true;
                    }else{
                        $scope.validationmsg = false;
                        requestFactory.post(requestFactory.getUrl('users/add'),$scope.user,function(response){
                            if(response.statusCode == 200){ 
                                $scope.showResponseMessage = true;
                                $scope.responseMessage=response.message;
                                $scope.user={};
     
                            }
                    }, $scope.fillError );
                    }
                  }

            }

            $scope.fillError = function ( response ) { 
                if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) { 
                    angular.forEach( response.data.message, function ( message, key ) {
                        if ( typeof message == 'object' && message.length > 0 ) {
                            $scope.errors[ key ] = {  
                                has : true,
                                message : message[ 0 ]
                            };
                        }
                    } );
                }
                if ( response.status === 401 && response.message!=='' ) {
                    $scope.user.password = '';
                    $scope.errors[ 'email' ] = {
                        has : true,
                        message : response.data.message
                    };
                }
                }
         }
    ] );
} )();