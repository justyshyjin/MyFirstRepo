( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'loginController', [
            '$rootScope', 'requestFactory', '$state', '$scope', 'ngToast', function ( $rootScope, requestFactory,  $state, $scope, ngToast ) {
                $scope.user = {'login_type':'normal'};
                baseValidator.setRules( {
                    email : 'required|email',
                    password : 'required|min:6'
                } );
                
                $scope.loadterms = function () {
                   // $uibModalInstance.dismiss( 'cancel' );
                    setTimeout( function () {
                        $state.go( 'staticContent', {'slug':'terms-and-condition'}, {
                            reload : false
                        } );
                    }, 100 );
                };
                $scope.login = function ( $event ) {
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var authURI = 'auth/login';
                        requestFactory.post( requestFactory.getUrl( authURI ), $scope.user, function ( response ) {
                            if(response.statusCode == 200){
                                var form = document.querySelector('form[name="loginForm"]')
                                form.action=authURI;
                                $rootScope.httpLoaderLocalElement = 1;
                                form.submit()
                            }
                        }, $scope.fillError );
                    }
                }
                $scope.fillError = function ( response ) {
                    if ( response.status == 422 && response.data.hasOwnProperty( 'messages' ) ) {
                        angular.forEach( response.data.messages, function ( message, key ) {
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
    ] )

} )();