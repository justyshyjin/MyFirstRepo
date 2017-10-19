(function() {
    'use strict';
    var controllers = angular.module("app.controllers", []);
    controllers.factory('requestFactory', requestFactory);
    controllers.directive('baseValidator', validatorDirective);
    controllers.controller('blogController', [
            '$filter',
            '$rootScope',
            'requestFactory',
            '$stateParams',
            '$scope',
            'ngToast',
            'data',
            '$state','$sce',
            function($filter, $rootScope, requestFactory, $stateParams, $scope,
                    ngToast, data, $state,$sce) {
                var successResponseData;
                var dataBinder = function(response) {
                    var temp = ($scope.blog) ? $scope.blog.data : null;
                    $scope.blog = response;
                    $scope.blog.data = (temp) ? temp.concat($scope.blog.data)
                            : $scope.blog.data;
                };
                $scope.errors = {};
                dataBinder(data.data.response);
                $scope.showmore = function() {
                    if ($scope.blog.next_page_url !== null) {
                        requestFactory.get($scope.blog.next_page_url, function(
                                success) {
                            dataBinder(success.response);
                        }, function(fail) {
                        });
                    }

                }
                $scope.to_trusted = function ( html_code ) {
                    return $sce.trustAsHtml( html_code );
                }
            }, ])
})();
