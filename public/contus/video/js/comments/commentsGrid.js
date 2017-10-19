'use strict';

var CommentsGridController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function (  scope, requestFactory, $window, $sce, $timeout, $compile, $interval ) {
    var self = this;
    this.info = {};
    this.group = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument( this );
    this.uniqueRoute = requestFactory.getUrl( 'examgroups/examgroups-unique' );
    angular.element( '.alert-success' ).fadeIn( 1000 ).delay( 5000 ).fadeOut( 1000 );

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    this.closegroupEdit = function () {
        classie.remove( document.getElementById( 'st-container' ), 'st-menu-open' );
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.category = data.info.category;
        requestFactory.toggleLoader();
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'comments/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();
    /**
     * Function to update status of a preset,group,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function ( record ) {
        scope.routeName = 'comments';
        scope.updateStatus( record );
    };

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.is_active ) ) {
            scope.searchRecords.is_active = 'all';
        }
    } );
}];

window.gridControllers = {CommentsGridController : CommentsGridController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};
