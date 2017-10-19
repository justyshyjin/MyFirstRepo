'use strict';

var paymentController = ['$scope','requestFactory','$window','$sce','$timeout',function ( scope, requestFactory, $window, $sce, $timeout ) {
    var self = this;
    this.payment = {};
    scope.rules = {};
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
     *  Function is used to edit the latestnews
     *  
     *  @param records
     */
    this.editPayment = function ( records ) {
        scope.errors = {};
        this.payment.id = records.id;
        this.payment.name = records.name;
        this.payment.type = records.type;
        this.payment.description = records.description;
        this.payment.is_test = String( records.is_test );
        this.payment.setting = {};
        scope.payment = {child : records.paymentsettings};
        var rules = scope.rules [0];
        for ( var i = 0; i < records.paymentsettings.length; i++ ) {
            self.payment.setting [records.paymentsettings [i] ['key']] = records.paymentsettings [i] ['value'];
            rules [records.paymentsettings [i] ['key']] = records.paymentsettings [i] ['validation'];
        }
        baseValidator.setRules( rules );
        this.payment.is_active = String( records.is_active );
    }

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key.replace( "setting.", "" )] = {has : true,message : message [0]};
                }
            } );
        }
    };
    scope.getspacekey = function(title){
        title = title.replace(/([A-Z])/g, ' $1').trim();
        title.charAt(0).toUpperCase() + title.slice(1);
        return title;
    }
    this.subscribeCust = function ( records ) {
        scope.errors = {};
        this.payment.id = records.id;
        this.payment.name = records.name;
        this.payment.type = records.type;
        this.payment.description = records.description;
        this.payment.is_test = String( records.is_test );
        this.payment.is_active = String( records.is_active );
    }

    /**
     *  Function is used to save the latestnews
     *  
     *  @param $event,id
     */
    this.save = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'payments/edit/' + id ), this.payment, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closePaymentEdit();
                    $timeout( function () {
                        self.payment = {};
                    }, 100 );
                }, this.fillError );

            }
        }
    }

    /**
     * Function to close the sidebar which is used to edit latestnews information.
     */
    this.closePaymentEdit = function () {
        var container = document.getElementById( 'st-container' )
        classie.remove( container, 'st-menu-open' );
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        scope.rules = data.info.rules;
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'payments/info' ), this.defineProperties, function () {
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

window.gridControllers = {paymentController : paymentController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};

$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );