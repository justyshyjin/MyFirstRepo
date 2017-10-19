'use strict';

var UserController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.user = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
  /**
   *  Function is used to add the user
   *  @param $event
   */ 
  this.addUser = function ($event){
    scope.errors = {};
    this.user={};
    this.user.is_active = String(0);
  }
   /**
   *  Function is used to edit the user
   *  
   *  @param records
   */ 
  this.editUser = function (records) {
    scope.errors = {};
    this.user.id = records.id;
    this.user.name = records.name;
    this.user.email = records.email;
    this.user.phone = records.phone;
    this.user.is_active = String(records.is_active);
    this.user.user_group_id = String(records.user_group_id);
    this.user.gender = records.gender;
  }

  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('message')){
      angular.forEach(response.data.message, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };

   /**
   *  Function is used to save the user
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('users/edit/'+id),this.user,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeUserEdit();
          $timeout(function(){
            self.user = {};
          },100);
        },this.fillError);
        
      } else {
        requestFactory.post(requestFactory.getUrl('users/add'),this.user,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeUserEdit();
        },this.fillError);
      }
    }
  }
  /**
   * Function to close the sidebar which is used to edit user information.
   */
  this.closeUserEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      this.allUserGroups = data.info.allUserGroups;
      //requestFactory.toggleLoader();
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('users/info'),this.defineProperties,function(){});
  };

  this.fetchInfo();

  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
    }
  });
}];

window.gridControllers = {UserController : UserController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});