'use strict';
var wowzaController = ['$scope','flowFactory','requestFactory','$window','$sce','$timeout',function ( scope, flowFactory, requestFactory, $window, $sce, $timeout ) {
    var self = this;
    this.latestnews = {};
    this.showResponseMessage = false;
    scope.checkStream = "Yes";
    requestFactory.setThisArgument( this );
    scope.init = function ( id ) {
        requestFactory.get( requestFactory.getUrl( 'videos/latestnews/' + id ), function ( response ) {
            this.editLatestNews( response.message )
        }, this.fillError );
    }
    scope.existingFlowObject = flowFactory.create( {target : document.querySelector( 'meta[name="base-api-url"]' ).getAttribute( 'description' ) + '/latest/latestnews-image',permanentErrors : [404,500,501],testChunks : false,maxChunkRetries : 1,chunkRetryInterval : 5000,simultaneousUploads : 4,singleFile : true} );
    scope.existingFlowObject.on( 'fileSuccess', function ( event,message ) {
        if ( message) {
            self.latestnews.selected_thumb = message;
            angular.element( '.loaders' ).hide();
            angular.element( '.submitbutton' ).attr('disabled', false)
        }
    } );
    scope.existingFlowObject.on( 'fileAdded', function ( file ) {
        if ( file.size > 2097152 ) {
            return false;
        }
        angular.element( '.loaders' ).show();                  
        angular.element( '.submitbutton' ).attr('disabled', true)
    } );

    /**
     *  To get the auth id
     *  
     */
    this.setQuery = function ( $authId ) {
        this.authId = $authId;
    }

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    /**
     *  Function is used to save the latestnews
     *  
     *  @param $event,id
     */
    this.save = function ( $event) {
        scope.errors={};
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
                requestFactory.post( requestFactory.getUrl( 'createlivestream' ), this.latestnews, function ( response ) {
                    window.location.href=requestFactory.getTemplateUrl( 'admin/livevideos' ) ;
                }, this.fillError );
        }
    }
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.aspect_ratio ) ) {
            scope.searchRecords.aspect_ratio = 'all';
        }
    } );
}];

window.gridControllers = {wowzaController : wowzaController};
window.gridInitApp = angular.module( 'grid', ['flow','angularjs-datetime-picker'] );
window.gridDirectives = {baseValidator : validatorDirective};

$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );