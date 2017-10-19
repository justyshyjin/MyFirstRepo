/**
 * customer controller
 */
(function() {
    "use strict";
    var controller = angular.module("app.controllers");
    controller.factory('requestFactory', requestFactory);
    controller.directive( 'baseValidator', validatorDirective );    
    controller.controller(
                    'myAccountController',
                    [
                            '$scope',
                            '$state',
                            '$filter',
                            'ngToast',
                            '$rootScope',
                            '$document',
                            'requestFactory',
                            function($scope, $state, $filter,ngToast, $rootScope,
                                    $document,requestFactory    ) {
                                $scope.profile={};
                                $scope.subscription={};
                                var successResponseData;
                                var dataBinder = function() {
                                    $scope.profile = successResponseData.message.profile;
                                    $scope.subscription = successResponseData.message.subscription;
                                };
                                var success = function(success) {
                                    successResponseData = success;
                                    dataBinder();
                                };
                                var fail = function(fail) {
                                    return fail;
                                };
                                requestFactory.get(requestFactory.getUrl('profile'), success, fail);
                                
                                baseValidator.setRules( {
                                        name : 'required',
                                        phone : 'filled|numeric|min:6|max:10',          
                                    
                                } );
                                $scope.editCust = function($event){
                                     if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                                           var authURI = 'customerProfile';
                                            requestFactory.put( requestFactory.getUrl( authURI ), $scope.profile, function ( response ) {
                                                ngToast.create( {
                                                    className : 'success',
                                                    content : '<strong>' + response.message + '</strong>'
                                                } );
                                            }, $scope.fillError );
                                        }
                                };
                                $scope.subscribeCust = function($event){
                                     if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
                                           var authURI = 'customerProfile';
                                            requestFactory.post( requestFactory.getUrl( authURI ), $scope.subscription, function ( response ) {
                                                ngToast.create( {
                                                    className : 'success',
                                                    content : '<strong>' + response.message + '</strong>'
                                                } );
                                            }, $scope.fillError );
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
                            } ]);
})();