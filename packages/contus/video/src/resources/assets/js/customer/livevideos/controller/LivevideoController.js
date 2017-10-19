( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'LivevideoController', ['$rootScope','requestFactory','$stateParams','$scope','ngToast','data',function ( $rootScope, requestFactory, $stateParams, $scope, ngToast, data ) {
        var nexturl = 0;
        $scope.videos = {};
        $scope.recordvideos = {};
        $scope.livevideos = {};
        var dataBinder = function ( response ) {
        	var temp = ($scope.livevideos.data) ? $scope.livevideos.data : null;
            $scope.livevideos = response.upcoming_live_videos;
            $scope.livevideos.data = (temp) ? temp.concat( $scope.livevideos.data) :  $scope.livevideos.data;
        };
        dataBinder( data.data.response );
        $scope.loadmorerecordedvideo = function () {
            nexturl = 1;
            $rootScope.filterRecord();
        };
        $scope.loadmorelivevideo = function () {
            nexturl = 1;
            $rootScope.filter();
        };
        var success = function ( success ) {
            dataBinder( success.response );
            nexturl = 0;
        };
        var fail = function ( fail ) {
            return fail;
        };
        $rootScope.filterRecord = function () {
            var url;
            if ( nexturl ) {
                url = $scope.recordvideos.next_page_url;
            } else {
                url = requestFactory.getUrl( 'livevideos' );
            }
            requestFactory.post( url,{},successrecord, fail);
        }
        $rootScope.filter = function () {
            var url;
            if ( nexturl ) {
                url = $scope.livevideos.next_page_url;
            } else {
                url = requestFactory.getUrl( 'livevideos' );
            }
            requestFactory.post( url,{},success, fail);
        }
    }] )

} )();

/**
 * extends string prototype object to get a string with a number of characters from a string.
 * 
 * @type {Function|*}
 */
String.prototype.trunc = String.prototype.trunc || function ( n ) {

    // this will return a substring and
    // if its larger than 'n' then truncate and append '...' to the string and return it.
    // if its less than 'n' then return the 'string'
    return this.length > n ? this.substr( 0, n - 1 ) + '...' : this.toString();
};