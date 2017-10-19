'use strict';

var emailController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.email = {};
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
   *  Function is used to add the latest news
   *  @param $event
   */ 
  this.addEmail = function ($event){
    scope.errors = {};
    this.email={};
    this.email.is_active = String(0);
  }
  
  /**
   *  Function is used to edit the email
   *  
   *  @param records
   */ 
  this.editEmail = function (records) {
    scope.errors = {};
    this.email.id = records.id;
    this.email.name = records.name;
    this.email.slug = records.slug;
    this.email.subject = records.subject;
    this.email.content = records.content;
    this.email.is_active = String(records.is_active);
  }

  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('messages')){
      angular.forEach(response.data.messages, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };
  
  /**
   *  Function is used to save the email
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('emails/edit/'+id),this.email,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeEmailEdit();
          $timeout(function(){
            self.email = {};
          },100);
        });
        
      } else {
        requestFactory.post(requestFactory.getUrl('emails/add'),this.email,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeEmailEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit email information.
   */
  this.closeEmailEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('emails/info'),this.defineProperties,function(){});
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

window.gridControllers = {emailController : emailController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});