'use strict';

/*
 * Contalog Admin AngularJS
 */
( function () {

    "use strict";
    /**
     * Initiating the app
     */
    angular.module( "app", [
            "app.controllers", "app.routes", "app.directive", "app.config", "app.filter", "app.services"
    ] );
    angular.module( "app.routes", [
            "ui.router", "ui.bootstrap", 'oc.lazyLoad', "ngAnimate"
    ] ), angular.module( "app.controllers", [
        'djds4rce.angular-socialshare'
    ] ), angular.module( "app.config", [
            "angular-loading-bar", "ngToast"
    ] ), angular.module( "app.factory", [
        'requestFactory'
    ] ), angular.module( "app.filter", [] ), angular.module( "app.directive", [] );
    angular.module( "app.services", [] );
    /**
     * to set whitelist domain resource Url
     */
    angular.module( "app.config" ).config( [
            '$sceDelegateProvider', '$httpProvider', 'cfpLoadingBarProvider', 'ngToastProvider', function ( $sceDelegateProvider, $httpProvider, cfpLoadingBarProvider, ngToastProvider ) {
                $sceDelegateProvider.resourceUrlWhitelist( [
                        'self',
                ] );
                cfpLoadingBarProvider.includeSpinner = false; // Show the spinner.
                cfpLoadingBarProvider.includeBar = true; // Show the bar.
                ngToastProvider.configure( {
                    dismissButton : true,
                    animation : 'fade',
                    dismissOnClick : true,
                    verticalPosition: 'bottom',
                    horizontalPosition: 'center',
                    maxNumber: 3
                } );
                $httpProvider.interceptors.push( function ( $q, $rootScope ) {
                    return {
                        responseError : function ( res ) {
                            if ( res.status == 403 ) {
                                window.location = $rootScope.currentUrl;
                            }
                            $rootScope.pageLoaderComplete();
                            return $q.reject( res );
                        },
                        request : function ( request ) {
                            $rootScope.pageLoaderStart();
                            return request
                        },
                        response : function ( response ) {
                            $rootScope.pageLoaderComplete();
                            return response
                        }
                    }
                } );
            }
    ] ).run( function ( $rootScope ) {
        $rootScope.fields = {
            search : sessionStorage.search
        };
    } ).run( function ( $FB ) {
        $FB.init( '175178909612583' );
    } ).run( function ( $rootScope, $location ) {
        $rootScope.location = $location;
        $rootScope.httpCount = 0;
        $rootScope.httpLoaderLocalElement = 0;
        $rootScope.pageLoaderStart = function () {
            $rootScope.httpCount = $rootScope.httpCount + 1;
            var loader = angular.element( document.getElementById( 'preloader' ) );
            if ( $rootScope.httpLoaderLocalElement === 0 && loader.find( '#status' ).css( 'display' ) == 'none' ) {
                loader.addClass( 'loader' );
                loader.find( '#status' ).css( 'display', 'block' );
                loader.css( 'display', 'block' );
            }
        };
        $rootScope.pageLoaderComplete = function () {
            $rootScope.httpLoaderLocalElement = 0;
            $rootScope.httpCount = $rootScope.httpCount - 1;
            var loader = angular.element( document.getElementById( 'preloader' ) );
            if ( $rootScope.httpCount === 0 && loader.find( '#status' ).css( 'display' ) == 'block' ) {
                loader.removeClass( 'loader' );
                loader.find( '#status' ).css( 'display', 'none' );
                loader.css( 'display', 'none' );
            }
        };
    } ).run( function ( $rootScope, $location, $anchorScroll, $window ) {
        // when the route is changed scroll to the proper element.
        $rootScope.$on( '$stateChangeSuccess', function ( state, newRoute, oldRoute ) {
            $window.scrollTo( 0, 0 );
            if(newRoute.name !== 'categoryvideos'){
                $rootScope.fields.search = '';
            }
        } );
    } );
    ;
} )();