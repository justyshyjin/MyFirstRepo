'use strict';

var PresetGridController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    this.info = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    angular.element('.alert-success').fadeIn(1000).delay(5000).fadeOut(1000);
    
    this.defineProperties = function(data) {
        this.info = data.info;
        this.numberOfActivePresets = data.info.numberOfActivePresets;
        requestFactory.toggleLoader();
    };

    this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('presets/info'),this.defineProperties,function(){});
    };

    this.fetchInfo();

    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function(record) {
        if(record.is_active == 0) {
      	  // Increase the preset count by one.
      	  this.numberOfActivePresets++;
        }
        else {
      	  // Drecease the preset count by one
      	  this.numberOfActivePresets--;
        }
        scope.routeName = 'presets';
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

window.gridControllers = {PresetGridController : PresetGridController};
window.gridDirectives  = {
	
};   

