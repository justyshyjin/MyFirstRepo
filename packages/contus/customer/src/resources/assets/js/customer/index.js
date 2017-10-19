'use strict';

var UserController = ['$scope','requestFactory','$window','$sce','$timeout',function ( scope, requestFactory, $window, $sce, $timeout ) {
    var self = this;

    this.user = {};
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
     *  Function is used to add the user
     *  @param $event
     */
         $timeout( function () {
          $('#filter_startdate').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
          $('#filter_enddate').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
         }, 1000 );

    this.addUser = function ( $event ) {
        scope.showhidepassword = 1;
        this.user.subsciptionform = 0;
        scope.errors = {};
        this.user = {};
        this.user.email = '';
        this.user.acesstype = 'web';
        this.user.password = '';
        this.user.exam = '';
        scope.examSelection = [];
        this.user.age = '';
        $('#age').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
        this.user.password_confirmation = '';
        this.user.is_active = String( 0 );
        baseValidator.setRules( self.ruleset );
    }
    /**
    *  Function is used to edit the user
    *
    *  @param records
    */
    this.editUser = function ( records ) {
        scope.errors = {};
        this.user.subsciptionform = 0;
        this.user.id = records.id;
        this.user.name = records.name;
        this.user.email = records.email;
        this.user.phone = records.phone;
        this.user.age = records.dob;
        $('#age').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true}).datepicker('setDate', records.dob);
        scope.examSelection = [];
        scope.showhidepassword = 0;
        var test = function ( exam ) {
            for ( var i = 0; i < exam.length; i++ ) {
                scope.examSelection.push( exam [i].id );
            }
        };
        test( records.exams );
        this.user.exam = scope.examSelection.join( ',' );
        this.user.is_active = String( records.is_active );
        this.user.acesstype = 'web';
        var rules = self.ruleset;
        rules.password = rules.password.replace( "required", "filled" );
        rules.password_confirmation = rules.password_confirmation.replace( "required", "filled" );
        baseValidator.setRules( rules );
    }
this.addSubscription = function(records){
	scope.errors = {};
	this.user.acesstype = 'web';
	this.user.subsciptionform = 1;
	this.user.id = records.id;
	this.user.start_date =  "";
	this.user.orderid = "";
	$('#start_date').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
	this.user.subscription_plan = String("");
	var rules = self.ruleset;
    baseValidator.setRules( rules );
}
    scope.selectexam = function ( slug ) {
        var idx = scope.examSelection.indexOf( slug );
        // Is currently selected
        if ( idx > -1 ) {
            scope.examSelection.splice( idx, 1 );
        }
        // Is newly selected
        else {
            scope.examSelection.push( slug );
        }
        self.user.exam = scope.examSelection.join( ',' );
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
    *  Function is used to save the user
    *
    *  @param $event,id
    */
    this.save = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                if ( $event.target.password && $event.target.password_confirmation ) {
                    this.user.password = $event.target.password.value;
                    this.user.password_confirmation = $event.target.password_confirmation.value;
                }
                requestFactory.put( requestFactory.getUrl( 'customers/' + id ), this.user, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeUserEdit();
                    $timeout( function () {
                        self.user = {};
                    }, 100 );
                } );

            } else {
                this.user.password = $event.target.password.value;
                this.user.password_confirmation = $event.target.password_confirmation.value;
                requestFactory.post( requestFactory.getUrl( 'customers' ), this.user, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeUserEdit();
                }, this.fillError );
            }
        }
    }
    this.saveSubcription = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'customer-subscription' ), this.user, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeUserEdit();
                    $timeout( function () {
                        self.user = {};
                    }, 100 );
                } );

            } else {
                requestFactory.post( requestFactory.getUrl( 'customers' ), this.user, function ( response ) {
                    scope.getRecords( true );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    this.closeUserEdit();
                }, this.fillError );
            }
        }
    }
    var date = angular.element('#age');
    var checkValue = function (str, max) {
       if (str.charAt(0) !== '0' || str == '00') {
         var num = parseInt(str);
         if (isNaN(num) || num <= 0 || num > max) num = 1;
         str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
       };
       return str;
     };

     scope.dateKeyup =  function(e,date) {
       var input = date;
       if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
       var values = input.split('/').map(function(v) {
         return v.replace(/\D/g, '')
       });
       if (values[0]) values[0] = checkValue(values[0], 12);
       if (values[1]) values[1] = checkValue(values[1], 31);
       var output = values.map(function(v, i) {
         return v.length == 2 && i < 2 ? v + ' / ' : v;
       });
       self.user.age = output.join('').substr(0, 14);
     }


    /**
     * Function to close the sidebar which is used to edit user information.
     */
    this.closeUserEdit = function () {
        var container = document.getElementById( 'st-container' )
        classie.remove( container, 'st-menu-open' );
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.allUserGroups = data.info.allUserGroups;
        requestFactory.toggleLoader();
        self.ruleset = data.info.rules;
        scope.exams = data.info.exams;
        scope.subcription_plans = data.info.subscription_plans;

    };

    this.fetchInfo = function () {
        requestFactory.toggleLoader();
        requestFactory.get( requestFactory.getUrl( 'customer/info' ), this.defineProperties, function () {
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


window.gridControllers = {UserController : UserController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};
window.gridInitApp = angular.module('grid',[]);
$( document ).ready( function () {
    var loader = $( '#preloader' );
    loader.find( '#status' ).css( 'display', 'none' );
    loader.css( 'display', 'none' );
} );
