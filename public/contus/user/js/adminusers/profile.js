'use strict';

var profile = angular.module('profile',[]);

profile.directive('baseValidator',validatorDirective);

profile.factory('requestFactory',requestFactory);

profile.controller('ProfileController',['$window','$scope','$rootScope','requestFactory',function(win,scope,$rootScope,requestFactory){
    var self = this;
    this.user = {};
    scope.errors = {};
    this.showResponseMessage = false;
    requestFactory.setThisArgument(this);

    window.ProfileImageUploadHandler = new uploadHandler;
    window.ProfileImageUploadHandler.initate({
        file      : 'profile-image',
        previewer : 'profile-preview',
        progress : 'image-progress',
        deleteIcon : 'profile-image-delete',
        afterUpload : function(response){
          self.user.profile = response.info;
          self.user.profile_image = response.info;
      }
      });
    /**
     *  To get the profile rules
     *  
     */ 
    this.getProfileRules = function() {
      requestFactory.get(requestFactory.getUrl('users/info'),function(response){
          baseValidator.setRules({
              phone:"required|numeric|min:10",
          });
        requestFactory.toggleLoader();
      });
    }
    this.getProfileRules();
    
    this.fetchData = function() {
      requestFactory.get(requestFactory.getUrl('users/edit'),function(response){
        this.user = response.response;
        this.user.is_active = String(response.response.is_active);
        this.showResponseMessage = false;
      }); 
    }
    /**
     *  Functtion is used to fill the error
     *  
     */ 
    this.fillError = function(response) { 
      if(response.status == 422 && response.data.hasOwnProperty('message')) {
          requestFactory.toggleLoader();
           angular.forEach(response.data.message, function(message,key) {
          if(typeof message == 'object' && message.length > 0){         
            scope.errors[key] = {has : true , message : message[0]};
          }
        });
      }
    };
    
    /**
     *  Functtion is used to save the new password
     *  
     */ 
    this.save = function($event){
       if (baseValidator.validateAngularForm($event.target,scope)) {
    	  requestFactory.toggleLoader();
          requestFactory.post(requestFactory.getUrl('users/edit/'+this.user.id),this.user,function(response){
            this.responseMessage = response.message;
            this.showResponseMessage = true;
            win.location = requestFactory.getTemplateUrl('admin/users/profile');
          },this.fillError);
        }
    };
    
    this.removeProfileImageProperty = function() {
    	self.user.profile = '';
    	self.user.profile_image = '';
    };
    
    this.deleteProfileImage = function() {
    	requestFactory.toggleLoader();
		requestFactory.post(requestFactory.getUrl('users/delete-profile-image/'+this.user.id),this.user,function(){
			win.location = requestFactory.getTemplateUrl('admin/users/profile');
		},function() {});
    };
}]);

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['profile']);
});
