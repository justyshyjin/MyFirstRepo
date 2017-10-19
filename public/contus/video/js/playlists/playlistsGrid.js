'use strict';

var PlaylistGridController = ['flowFactory','$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function ( flowFactory, scope, requestFactory, $window, $sce, $timeout, $compile, $interval ) {
    var self = this;
    this.info = {};
    this.playlist = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument( this );
    this.uniqueRoute = requestFactory.getUrl( 'playlists/playlists-unique' );
    angular.element( '.alert-success' ).fadeIn( 1000 ).delay( 5000 ).fadeOut( 1000 );
    scope.existingFlowObject = flowFactory.create( {target : document.querySelector( 'meta[name="base-api-url"]' ).getAttribute( 'content' ) + '/image?types=playlists',permanentErrors : [404,500,501],testChunks : false,maxChunkRetries : 1,chunkRetryInterval : 5000,simultaneousUploads : 4,singleFile : true} );
    scope.existingFlowObject.on( 'fileSuccess', function ( event,message ) {
        if ( message ) {
            self.playlist.playlist_image = message;
            angular.element( '.loaders' ).hide();
            angular.element( '.submitbutton' ).attr('disabled', false);
        }
    } );
    scope.existingFlowObject.on( 'fileAdded', function ( file ) {
        if ( file.size > 2097152 ) {
            scope.errors [key] = {has : true,message : 'Image should be below 2 mb'};
            return false;
        }
        angular.element( '.loaders' ).show();                  
        angular.element( '.submitbutton' ).attr('disabled', true)
    } );
    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    this.closeplaylistEdit = function () {
        classie.remove( document.getElementById( 'st-container' ), 'st-menu-open' );
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.category = data.info.category;
        requestFactory.toggleLoader();
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'playlists/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();

    /**
     *  Function is used to get the categories rules
     *  
     */
    this.getplaylistEdit = function ( record ) {
        scope.existingFlowObject.cancel();
        scope.errors = {};
        this.uniqueRoute = requestFactory.getUrl( 'playlists/' + record.id );
        this.playlist.name = record.name;
        this.playlist.category_id = record.category_id;
        this.playlist.playlist_image = record.playlist_image;
        this.playlist.is_active = String( record.is_active );
        this.playlist.playlist_order = String( record.playlist_order );
        this.playlist.id = record.id;
    }

    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addplaylist = function ( $event ) {
        scope.existingFlowObject.cancel();
        scope.errors = {};
        self.playlist = {};
        this.uniqueRoute = requestFactory.getUrl( 'playlists/playlists-unique' );
        this.playlist = {};
        this.playlist.is_active = String( 0 );
        this.playlist.playlist_order = String( 0 );
        this.playlist.playlist_image = '';
    }

    /**
     *  Function is used to save the playlist
     *  
     *  @param  $event, id
     */
    this.playlistSave = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.put( requestFactory.getUrl( 'playlists/' + id ), this.playlist, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    scope.getRecords( true );
                    this.closeplaylistEdit();
                }, this.fillError );
            } else {
                requestFactory.post( requestFactory.getUrl( 'playlists' ), this.playlist, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    scope.getRecords( true );
                    this.closeplaylistEdit();
                }, this.fillError );
            }
        }
    }

    /**
     * Function to update status of a preset,playlist,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function ( record ) {
        scope.routeName = 'playlists';
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

window.gridControllers = {PlaylistGridController : PlaylistGridController};
window.gridInitApp = angular.module( 'grid', ['flow'] );
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};