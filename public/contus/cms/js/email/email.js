$(document).ready(function(){
	baseValidator.initateThroughJquery($('form[name="emailtemplateForm"]'),'emailtemplateForm').setLocale(window.Mara.locale);
});
var emailPage = angular.module( 'emailPage', ["ui.tinymce"] );
emailPage.directive( 'baseValidator', validatorDirective );
emailPage.factory( 'requestFactory', requestFactory );
emailPage.controller( 'EmailController', ['$scope','$rootScope','requestFactory',function ( $scope, $rootScope, requestFactory ) {
    requestFactory.get( requestFactory.getUrl( 'emails/email-data/' + angular.element( 'span#inititate' ).html() ), function ( response ) {
        $scope.emailData = {name : response.response.name,content : response.response.content,subject:response.response.subject}
        requestFactory.toggleLoader();
    }, $scope.fillError );
    $scope.tinymceOptions = {
        plugins: 'link image code',
        height: 300,
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
          };
    $scope.errors = {};
    baseValidator.setRules( JSON.parse( angular.element( 'span#rules' ).html() ) );
    $scope.submitform = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
            if ( angular.element( 'span#inititate' ).html() ) {
                requestFactory.post( requestFactory.getUrl( 'emails/edit/' + angular.element( 'span#inititate' ).html() ), $scope.emailData, function ( response ) {
                    location.href = requestFactory.getTemplateUrl( 'admin/emails' ) ;
                }, 
                function ( resp ) {
                    $scope.fillError( resp );
                } );
            }
        }
    }
    /**
     *  Functtion is used to fill the error
     *  
     */
    $scope.fillError = function ( response ) { 
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    $scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };
}] );
