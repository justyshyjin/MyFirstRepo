'use strict';

var subscriptionPlanController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.subscriptions_plans = {};
  var parentCategoryIds = [];
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
   *  Function is used to add the subscription
   *  @param $event
   */ 
  this.addSubscriptionsPlans = function ($event){
    scope.errors = {};
    this.subscriptions_plans={};
    this.subscriptions_plans.is_active = String(0);
    this.subscriptions_plans.parent_id = {};
  }
  
  /**
   *  Function is used to edit the subscription
   *  
   *  @param records
   */ 
  this.editSubscriptionsPlans = function (records) {debugger;
	  angular.element(".categoryList li").show();
      angular.element("#category_id_"+records.id).hide();
    scope.errors = {};
    this.subscriptions_plans.id = records.id;
    this.subscriptions_plans.name = records.name;
    this.subscriptions_plans.type = records.type;
    this.subscriptions_plans.description = records.description;
    this.subscriptions_plans.amount = records.amount;
    this.subscriptions_plans.duration = records.duration;
    this.subscriptions_plans.is_active = String(records.is_active);
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
  this.mycategory = function($event){
	  parentCategoryIds.push($event.target.value);
  }
  
  /**
   *  Function is used to save the subscription
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
	this.subscriptions_plans.parent_id = parentCategoryIds;
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/edit/'+id),this.subscriptions_plans,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeSubscriptionEdit();
          $timeout(function(){
            self.subscriptions_plans = {};
          },100);
        });
        
      } else {
        requestFactory.post(requestFactory.getUrl('subscriptions-plans/add'),this.subscriptions_plans,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeSubscriptionEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeSubscriptionEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('subscriptions-plans/info'),this.defineProperties,function(){});
  };

  this.fetchInfo();
  
//Update categories in add/edit Subscription form
  requestFactory.get(requestFactory.getUrl('subscriptions-plans/feature'),function(data){
		this.allCategoriesHTML = $sce.trustAsHtml(data.allCategoriesHTML);
		
		$timeout(function(){    
			$compile(angular.element(".categoryList").contents())(scope);
		},100);
	},function(){});
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

window.gridControllers = {subscriptionPlanController : subscriptionPlanController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});