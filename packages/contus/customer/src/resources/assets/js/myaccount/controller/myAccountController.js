/**
 * customer controller
 */
( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'myAccountController', [
            '$scope', '$state', '$filter', 'ngToast', '$rootScope', '$document', 'requestFactory', 'data', function ( $scope, $state, $filter, ngToast, $rootScope, $document, requestFactory, data ) {
                $scope.profile = {};
                $scope.subscription = {};
                var successResponseData;
                var dataBinder = function () {
                    $scope.profile = successResponseData.message.profile;
                    $scope.subscription = successResponseData.message.subscription[ Math.floor( ( Math.random() * successResponseData.message.subscription.length ) + 0 ) ];
                    $scope.subscriptions = successResponseData.message.subscription;
                };
                var success = function ( success ) {
                    successResponseData = success;
                    dataBinder();
                };
                var fail = function ( fail ) {
                    return fail;
                };
                baseValidator.setRules( {
                        name : 'required',
                        phone : 'required|numeric|min:10',                    
                } );
                successResponseData = data.data;
                dataBinder();
                $scope.editCust = function ( $event ) {
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var authURI = 'customerProfile';
                        requestFactory.put( requestFactory.getUrl( authURI ), $scope.profile, function ( response ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + response.message + '</strong>'
                            } );
                            $state.go( 'profile', {}, {
                                reload : true
                            } );
                        }, function ( response ) {
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
                        } );
                    }
                };
                $scope.subscribeCust = function ( $event ) {
                    if(angular.element('input[type="checkbox"]').is(':checked')){
                    if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                        var sub = angular.element( 'input[type="radio"][name="subscription"]:checked' ).val();
                        var authURI = 'addsubscriber';
                        requestFactory.get( requestFactory.getUrl( authURI + '/' + sub ), function ( response ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + 'Membership Subscribed Successfully' + '</strong>'
                            } );
                            $state.go( 'profile', {}, {
                                reload : true
                            } );
                        }, $scope.fillError );
                    }
                    }
                };
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
                };
            }
    ] );
} )();