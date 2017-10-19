'use strict';

var featuresController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.subscriptions_features = {};
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
  this.addSubscriptionsFeatures = function ($event){
    scope.errors = {};
    this.subscriptions_features={};
    this.subscriptions_features.is_active = String(0);
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editFeatures = function (records) {
    scope.errors = {};
    this.subscriptions_features.id = records.id;
    this.subscriptions_features.name = records.name;
    this.subscriptions_features.description = records.description;
    this.subscriptions_features.is_active = String(records.is_active);
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
        requestFactory.post(requestFactory.getUrl('subscriptions-features/edit/'+id),this.subscriptions_features,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeFeatureEdit();
          $timeout(function(){
            self.subscriptions_features = {};
          },100);
        });
        
      } else {
        requestFactory.post(requestFactory.getUrl('subscriptions-features/add'),this.subscriptions_features,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeFeatureEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeFeatureEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('subscriptions-features/info'),this.defineProperties,function(){});
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

window.gridControllers = {featuresController : featuresController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});