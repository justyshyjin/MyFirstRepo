$( document ).ready( function () {
    baseValidator.initateThroughJquery( $( 'form[name="staticContentForm"]' ), 'staticContentForm' ).setLocale( window.Mara.locale );
} );
'use strict'; 
var staticPage = angular.module( 'staticPage',["ui.tinymce"] );
staticPage.directive( 'baseValidator', validatorDirective );
staticPage.factory( 'requestFactory', requestFactory );
staticPage.controller( 'StaticController', ['$window','$scope','$rootScope','requestFactory', function (win, $scope, $rootScope, requestFactory) {
    requestFactory.get( requestFactory.getUrl( 'staticContent/static-data/' + angular.element( 'span#inititate' ).html() ), function ( response ) {
        $scope.staticData = {title : response.response.title,content : response.response.content,banner : response.response.banner_image}
        requestFactory.toggleLoader();
        $scope.banner = {};
        $scope.banner_image='';
        $scope.tinymceOptions = {
                plugins: 'link image code',
                height: 300,
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
              };
    window.StaticImageUploadHandler = new uploadHandler;
    window.StaticImageUploadHandler.initate({
        file      : 'banner-image',
        previewer : 'banner-preview',
        progress : 'image-progress',
        deleteIcon : 'banner-image-delete',
        preview:{
            width:550,
            height:200
        },
        afterUpload : function(response){
            $scope.staticData.banner = response.info;
      }
      });
       
    }, $scope.fillError );
    
    $scope.errors = {};
    baseValidator.setRules( JSON.parse( angular.element( 'span#rules' ).html() ) );
    $scope.submitform = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, $scope ) ) {
            if ( angular.element( 'span#inititate' ).html() ) {
                requestFactory.post( requestFactory.getUrl( 'staticContent/edit/' + angular.element( 'span#inititate' ).html() ), $scope.staticData, function ( response ) {
                   location.href = requestFactory.getTemplateUrl( 'admin/staticContent' ) ;
                }, function ( resp ) {
                    $scope.fillError( resp );
                } );
            }
        }
    }
    /**
    *Function to clear the selected banner Image
    *
    */
    $scope.removeBannerImageProperty = function() {
        $scope.staticData.banner = '';
    };
    
    $scope.deleteBannerImage = function() {
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('staticContent/delete-banner-image/'+angular.element( 'span#inititate' ).html()),$scope.staticData,function(){
            win.location = requestFactory.getTemplateUrl('admin/staticContent/edit-static-content/'+angular.element( 'span#inititate' ).html());
        },function() {});
    };
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

/**
* Manually bootstrap the Angular module here
*/
