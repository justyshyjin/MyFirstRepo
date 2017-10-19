var grid = angular.module( 'grid', ['flow','ui'] );
grid.factory( 'requestFactory', requestFactory );
/**
* Define all grid directives
*/
if ( angular.isObject( window.gridDirectives ) ) {
    for ( var directive in window.gridDirectives ) {
        if ( angular.isArray( window.gridDirectives [directive] ) || angular.isFunction( window.gridDirectives [directive] ) ) {
            grid.directive( directive, window.gridDirectives [directive] );
        }
    }
}
/**
* Define all grid controllers
*/
if ( angular.isObject( window.gridControllers ) ) {
    for ( var controller in window.gridControllers ) {
        if ( angular.isArray( window.gridControllers [controller] ) || angular.isFunction( window.gridControllers [controller] ) ) {
            grid.controller( controller, window.gridControllers [controller] );
        }
    }
}