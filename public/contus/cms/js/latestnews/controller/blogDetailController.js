( function () {
    'use strict';
    var controllers = angular.module( "app.controllers", [] );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'blogDetailController', ['$filter','$rootScope','requestFactory','$stateParams','$scope','ngToast','data','$state','$sce',function ( $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, $state, $sce ) {
        var successResponseData;
        var dataBinder = function ( response ) {
            $scope.blogdetail = response;

        };
        dataBinder( data.data.response );

        $scope.to_trusted = function ( html_code ) {
            return $sce.trustAsHtml( html_code );
        }
    },] )
} )();
