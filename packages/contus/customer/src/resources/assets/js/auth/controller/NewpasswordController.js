( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'newpasswordController', [
            '$rootScope', 'requestFactory', '$state', '$scope', 'ngToast', function ( $rootScope, requestFactory, $state, $scope, ngToast ) {
                $scope.forgot = {};
                baseValidator.setRules( {
                    email : "required|email"
                } );
                $scope.cancel = function () {
                    $state.go( 'login', {}, {
                        reload : true
                    } );
                };
                $scope.submitForgotPassowrd = function ( $event ) {
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var authURI = 'auth/forgotpassword';
                        requestFactory.post( requestFactory.getUrl( authURI ),$scope.forgot, function ( response ) {
                            if ( response.statusCode == 200 ) {
                                ngToast.create( {
                                    className : 'success',
                                    content : '<strong>' + response.message + '</strong>'
                                } );
                                $state.go( 'login', {}, {
                                    reload : true
                                } );
                                $scope.forgot = {};
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
                }
            }
    ] )

} )();