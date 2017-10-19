/**
 * customer change password controller
 */
( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.directive( 'gridView', window.gridView );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'subscriptionController', [
            '$scope', '$state', '$filter', '$rootScope', '$document', 'requestFactory', 'data', function ( $scope, $state, $filter, $rootScope, $document, requestFactory, data ) {
                $scope.subscription = [];
                $scope.next_page = '';
                var successResponseData;
                $scope.subscription = data.data.message.subscription[ Math.floor( ( Math.random() * data.data.message.subscription.length ) + 0 ) ];
                var dataBinder = function () {
                    $scope.data = successResponseData.message;
                };
                var success = function ( success ) {
                    successResponseData = success;
                    dataBinder();
                };
                var fail = function ( fail ) {
                    return fail;
                };
                $scope.moreNotifications = function () {
                    requestFactory.get( $scope.next_page, success, fail );
                };
                $scope.resetPassword = function ( $event ) {
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var authURI = 'auth/change';
                        requestFactory.put( requestFactory.getUrl( authURI ), $scope.user, function ( response ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + response.message + '</strong>'
                            } );
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