( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.directive( 'gridView', window.gridView );
    controller.factory( 'requestFactory', requestFactory );
    controller.controller( 'transactionController', [
            '$scope', '$state', '$filter', '$rootScope', '$document', 'requestFactory', 'data', function ( $scope, $state, $filter, $rootScope, $document, requestFactory, data ) {
                $scope.subscription = {};
                $scope.transaction = [];
                $scope.next_page = '';
                var successResponseData;
                $scope.subscription = data.data.message.subscription;
                var dataBinder = function () {
                    $scope.data = successResponseData.message.data;
                    $scope.next_page = $scope.data.next_page_url;
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

                $scope.subscribeCustomer = function ( $event ) {
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
            }
    ] );
} )();