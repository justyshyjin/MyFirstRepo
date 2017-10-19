$(document).ready(function(){
	baseValidator.initateThroughJquery($('form[name="contactusForm"]'),'contactusForm').setLocale(window.Mara.locale);
});

var emailPage = angular.module( 'contactPage', ["ui"] );
emailPage.directive( 'baseValidator', validatorDirective );
emailPage.factory( 'requestFactory', requestFactory );
emailPage.controller( 'ContactController', ['$scope','$rootScope','requestFactory',function ( $scope, $rootScope, requestFactory ) {
requestFactory.get( requestFactory.getUrl('contactus/contact-view/' + angular.element( 'span#inititate' ).html() ), function ( response ) {
$scope.emailData = {name : response.response.name,phone : response.response.phone,email:response.response.email,message:response.response.message}
tinymce.init( {selector : 'textarea',height : 400,plugins : 'image media codesample imagetools',toolbar : 'image media codesample',image_caption : true,media_live_embeds : true,imagetools_cors_hosts : ['tinymce.com','codepen.io'],content_css : ['//fonts.googleapis.com/css?family=Lato:300,300i,400,400i','//cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.css','//www.tinymce.com/css/codepen.min.css']} );
        requestFactory.toggleLoader();
    }, $scope.fillError );
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
