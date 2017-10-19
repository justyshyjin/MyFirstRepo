'use strict';
var latestNewsController = ['$scope','flowFactory','requestFactory','$window','$sce','$timeout',function ( scope, flowFactory, requestFactory, $window, $sce, $timeout ) {
    var self = this;
    this.latestnews = {};
    this.showResponseMessage = false;
    requestFactory.setThisArgument( this );
    scope.init = function ( id ) {
        requestFactory.get( requestFactory.getUrl( 'cms/latestnews/' + id ), function ( response ) {
            this.editLatestNews( response.message )
        }, this.fillError );
    }
    scope.existingFlowObject = flowFactory.create( {target : document.querySelector( 'meta[name="base-api-url"]' ).getAttribute( 'content' ) + '/latest/latestnews-image',permanentErrors : [404,500,501],testChunks : false,maxChunkRetries : 1,chunkRetryInterval : 5000,simultaneousUploads : 4,singleFile : true} );
    scope.existingFlowObject.on( 'fileSuccess', function ( event,message ) {
        if ( message) {
            self.latestnews.post_image = message;
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

    /**
     *  Function is used to add the latest news
     *  @param $event
     */
    this.addLatestNews = function ( $event ) {
        scope.errors = {};
        this.latestnews = {};
        this.latestnews.is_active =1;
        this.latestnews.post_image = '';
    }

    /**
     *  Function is used to edit the latestnews
     *  
     *  @param records
     */
    this.editLatestNews = function ( records ) {
        scope.errors = {};
        this.latestnews.id = records.id;
        this.latestnews.title = records.title;
        this.latestnews.slug = records.slug;
        this.latestnews.content = records.content;
        this.latestnews.is_active = String( records.is_active );
        this.latestnews.post_creator = records.post_creator;
        this.latestnews.post_image = records.post_image;
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
    this.saveEditForm = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'latest/edit/' + id ), this.latestnews, function ( response ) {
                    location.href = requestFactory.getTemplateUrl( 'admin/latest' ) ;
                }, this.fillError );
            } else {
                requestFactory.post( requestFactory.getUrl( 'latest/add' ), this.latestnews, function ( response ) {
                  location.href = requestFactory.getTemplateUrl( 'admin/latest' ) ;
                }, this.fillError );
            }
        }
    }

    /**
     *  Function is used to save the latestnews
     *  
     *  @param $event,id
     */
    this.save = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'latest/edit/' + id ), this.latestnews, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeLatestNewsEdit();
                    $timeout( function () {
                        self.latestnews = {};
                    }, 100 );
                }, this.fillError );

            } else {
                requestFactory.post( requestFactory.getUrl( 'latest/add' ), this.latestnews, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeLatestNewsEdit();
                }, this.fillError );
            }
        }
    }

    /**
     * Function to close the sidebar which is used to edit latestnews information.
     */
    this.closeLatestNewsEdit = function () {
        var container = document.getElementById( 'st-container' )
        classie.remove( container, 'st-menu-open' );
    };
    this.closeImage = function () {
        this.latestnews.post_image = String();
    };
    this.defineProperties = function ( data ) {
        this.info = data.info;
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'latest/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();

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

window.gridControllers = {latestNewsController : latestNewsController};
window.gridInitApp = angular.module( 'grid', ['flow'] );
window.gridDirectives = {baseValidator : validatorDirective};

$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );