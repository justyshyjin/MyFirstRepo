/**
 * customer change password controller
 */
( function () {
    "use strict";
  
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'changePasswordController', [
            '$scope', 'ngToast', '$state', 'requestFactory', '$window', 'data', function ( $scope, ngToast, $state, requestFactory, $window, data ) {
                $scope.user = {};
                baseValidator.setRules( {
                    old_password : 'required|min:6',
                    password : 'required|min:6|different:old_password|same:password_confirmation',
                    password_confirmation : 'required|min:6'
                } );
                $scope.subscription = data.data.message.subscription[ Math.floor( ( Math.random() * data.data.message.subscription.length ) + 0 ) ];
                $scope.resetPassword = function ( $event ) {
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var authURI = 'auth/change';
                        requestFactory.put( requestFactory.getUrl( authURI ), $scope.user, function ( response ) {
                            sessionStorage.redrectmessage = response.message;
                            $window.location.reload();
                        }, $scope.fillError );
                    }
                }
                if ( sessionStorage.redrectmessage !== '' && sessionStorage.redrectmessage !== undefined ) {
                    ngToast.create( {
                        className : 'success',
                        content : '<strong>' + sessionStorage.redrectmessage + '</strong>'
                    } );
                    sessionStorage.redrectmessage = '';
                    $state.go( 'profile', {}, {
                        reload : true
                    } );
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
                    }else if(response.status == 500){
                        ngToast.create( {
                            className : 'danger',
                            content : '<strong>' + response.data.message + '</strong>'
                        } );
                    }
                }
            }
    ] )

} )();