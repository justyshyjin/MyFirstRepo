( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'CategoryListController', [
            '$rootScope', 'requestFactory', '$stateParams', '$scope', 'ngToast', 'data', function ( $rootScope, requestFactory, $stateParams, $scope, ngToast, data ) {
                $scope.videos = {};
                $scope.classinchecker = function () {
                    angular.forEach( $scope.categories.child_category, function ( subcat ) {
                        angular.forEach( subcat.child_category, function ( session ) {
                            if ( $scope.categoryFilter.indexOf( session.slug ) > -1 ) {
                                $scope.classinchecker[ subcat.slug ] = true;
                            }
                        } )
                    } )
                };
                var dataBinder = function ( response ) {
                    $scope.categories = response.categories;
                    $rootScope.categories = response.categories;
                };
                dataBinder( data.data.response );
                $scope.getLength = function ( obj ) {
                    if ( obj ) {
                        return Object.keys( obj ).length;
                    }
                }
            }
    ] )

} )();