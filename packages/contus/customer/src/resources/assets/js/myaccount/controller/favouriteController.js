/**
 * customer controller
 */
( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'baseValidator', validatorDirective );
    controller.controller( 'favouritesController', [
            '$scope', '$state', '$filter', 'ngToast', '$rootScope', '$document', 'requestFactory', 'data', 'favourites', function ( $scope, $state, $filter, ngToast, $rootScope, $document, requestFactory, data, favourites ) {
                $scope.videos = {};
                $scope.subscription = data.data.message.subscription[ Math.floor( ( Math.random() * data.data.message.subscription.length ) + 0 ) ];
                var dataBinder = function ( response ) {
                    var temp = '';
                    temp = ($scope.videos.data);
                    $scope.videos = response.response;
                   $scope.videos.data = ( temp ) ? temp.concat( $scope.videos.data ) : $scope.videos.data;
                };
                dataBinder( favourites.data );
                $scope.unfoollow = function ( slug ) {
                    requestFactory.put( requestFactory.getUrl( 'favourite' ), {
                        'video_slug' : slug
                    }, function ( success ) {
                        ngToast.create( {
                            className : 'success',
                            content : '<strong>' + success.message + '</strong>'
                        } );
                        $state.reload();
                    }, function ( error ) {
                        ngToast.create( {
                            className : 'danger',
                            content : '<strong>' + error.message + '</strong>'
                        } );
                    } );

                }
            }
    ] );
} )();