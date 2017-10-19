'use strict';

var CollectionGridController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    this.info = {};
    this.collection = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    this.uniqueRoute = requestFactory.getUrl('collections/collection-unique');
    angular.element('.alert-success').fadeIn(1000).delay(5000).fadeOut(1000);
    
    this.fillError = function(response){
    	 if(response.status == 422 && response.data.hasOwnProperty('message')){
          angular.forEach(response.data.message, function(message,key) {
            if(typeof message == 'object' && message.length > 0){
              scope.errors[key] = {has : true , message : message[0]};
            }
          });
        }
      };
    
    this.closeCollectionEdit = function() {
      classie.remove( document.getElementById( 'st-container' ), 'st-menu-open' );
    };
    
    this.defineProperties = function(data) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules(data.info.rules);
    };

    this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('collections/info'),this.defineProperties,function(){});
    };

    this.fetchInfo();
    
    /**
     *  Function is used to get the categories rules
     *  
     */
    this.getCollectionEdit = function(record) {
      scope.errors = {};
      this.uniqueRoute = requestFactory.getUrl('collections/collection-unique/'+record.id);
      this.collection.title = record.title;
      this.collection.order = record.order;
      this.collection.is_active = String(record.is_active);
      this.collection.id = record.id;
    }
    
    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addCollection = function ($event){
      scope.errors = {};
      self.collection = {};
      this.uniqueRoute = requestFactory.getUrl('collections/collection-unique');
      this.collection={};
      this.collection.is_active = String(0);
    }

    /**
     *  Function is used to save the collection
     *  
     *  @param  $event, id
     */
    this.collectionSave = function ($event,id) {
      if (baseValidator.validateAngularForm($event.target,scope)) {
        if (id) { 
          requestFactory.post(requestFactory.getUrl('collections/edit/'+id),this.collection,function(response){
            this.responseMessage = response.message;
            this.showResponseMessage = true;
            scope.getRecords(true);
            this.closeCollectionEdit();
          },this.fillError);
        } else {
          requestFactory.post(requestFactory.getUrl('collections/create-collection'),this.collection,function(response){
          this.responseMessage = response.message;
          this.showResponseMessage = true;
            scope.getRecords(true);
            this.closeCollectionEdit();
          },this.fillError);
        }
      }
    }
    
    
    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function(record) {
    	scope.routeName = 'collections';
    	scope.updateStatus(record);
    };

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

window.gridControllers = {CollectionGridController : CollectionGridController};
window.gridDirectives  = {
	baseValidator    : validatorDirective,
	intializeSidebar : intializeSidebar
};