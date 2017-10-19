'use strict';

var staticContentController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.static_content = {};
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
  this.addStaticContent = function ($event){
    scope.errors = {};
    this.static_content={};
    this.static_content.is_active = String(0);
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editStaticContent = function (records) {
    scope.errors = {};
    this.static_content.id = records.id;
    this.static_content.title = records.title;
    this.static_content.slug = records.slug;
    this.static_content.content = records.content;
    this.static_content.is_active = String(records.is_active);
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
   *  Function is used to save the latestnews
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('staticContent/edit/'+id),this.static_content,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
          $timeout(function(){
            self.latestnews = {};
          },100);
        });
        
      } else {
        requestFactory.post(requestFactory.getUrl('staticContent/add'),this.static_content,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeStaticContentEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('staticContent/info'),this.defineProperties,function(){});
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

window.gridControllers = {staticContentController : staticContentController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});