( function () {
    'use strict';

    var controllers = angular.module( "app.controllers", [] );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'StaticContentController', ['$filter','$rootScope','requestFactory','$stateParams','$scope','ngToast','data','$state','$sce',function ( $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, $state, $sce ) {
        var successResponseData;
        var dataBinder = function ( response ) {
            $scope.staticcontent = response;
        };
        dataBinder( data.data.response );
        $scope.errors = {};
        $scope.getcontactusrules = function () {
            requestFactory.get( requestFactory.getUrl( 'getstaticcontentrules' ), function ( response ) {
                baseValidator.setRules( response.response.rules );
            }, this.fillError );
        }
        $scope.to_trusted = function ( html_code ) {
            return $sce.trustAsHtml( html_code );
        }
        $scope.savecontactus = function ( event ) {
            // if ( grecaptcha.getResponse() === '' ) {
            //     $scope.recaptchaerror = 'Please Check the Captcha'
            // } else {
            //     $scope.recaptchaerror = ''
            // }
            // if ( baseValidator.validateAngularForm( event.target, $scope ) && grecaptcha.getResponse() ) {
            if ( baseValidator.validateAngularForm( event.target, $scope )) {
                requestFactory.post( requestFactory.getUrl( 'staticContent/contactus' ), this.staticcontent, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
                    $state.reload();
                }, $scope.fillError );

            }
        };

        $scope.authContactUs = function(authUserData) {
            this.staticcontent.name = authUserData.name;
            this.staticcontent.phone = authUserData.phone;
            this.staticcontent.email = authUserData.email;
        }
        
        $scope.fillError = function ( response ) {
            if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
                angular.forEach( response.data.message, function ( message, key ) {
                    if ( typeof message == 'object' && message.length > 0 ) {
                        $scope.errors [key] = {has : true,message : message [0]};
                    }
                } );
            }
        };
    },] )
} )();
