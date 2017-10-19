'use strict';

var youTube = angular.module( 'youTube', [] );

youTube.directive( 'baseValidator', validatorDirective );

youTube.factory( 'requestFactory', requestFactory );

youTube.controller( 'YoutubeImportController', ['$window','$scope','$rootScope','requestFactory','$timeout',function ( win, scope, $rootScope, requestFactory, $timeout ) {
    requestFactory.toggleLoader();

}] );
