'use strict';

var smsController = ['$scope','requestFactory','$window','$sce','$timeout',function ( scope, requestFactory, $window, $sce, $timeout ) {
    var self = this;
    this.sms = {};
    this.showResponseMessage = false;
    requestFactory.setThisArgument( this );
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
    this.addSms = function ( $event ) {
        scope.errors = {};
        this.sms = {};
        this.sms.is_active = String( 0 );
    }

    /**
     *  Function is used to edit the Sms
     *  
     *  @param records
     */
    this.editSms = function ( records ) {
        scope.errors = {};
        this.sms.id = records.id;
        this.sms.name = records.name;
        this.sms.slug = records.slug;
        this.sms.subject = records.subject;
        this.sms.content = records.content;
        this.sms.is_active = String( records.is_active );
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
     *  Function is used to save the Sms
     *  
     *  @param $event,id
     */
    this.save = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'smsTemplate/edit/' + id ), this.sms, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeSmsEdit();
                    $timeout( function () {
                        self.Sms = {};
                    }, 100 );
                },this.fillError );

            } else {
                requestFactory.post( requestFactory.getUrl( 'smsTemplate/add' ), this.sms, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeSmsEdit();
                }, this.fillError );
            }
        }
    }

    /**
     * Function to close the sidebar which is used to edit Sms information.
     */
    this.closeSmsEdit = function () {
        var container = document.getElementById( 'st-container' )
        classie.remove( container, 'st-menu-open' );
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'smsTemplate/info' ), this.defineProperties, function () {
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

window.gridControllers = {smsController : smsController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};

$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );