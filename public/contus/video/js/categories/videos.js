'use strict';

var videoCategories = angular.module('videoCategories',[]);

videoCategories.directive('baseValidator',validatorDirective);

videoCategories.factory('requestFactory',requestFactory);

videoCategories.controller('ViewVideoCategoriesController',['$window','$scope','$rootScope','requestFactory','$timeout',function(win,scope,$rootScope,requestFactory,$timeout){
  var self = this;
  scope.errors = {};
  scope.currentPage = 1;
  this.videoCategories = {};
  this.showResponseMessage = false;
  this.gridLoadingBar = false;
  this.videoListView = true;
  requestFactory.setThisArgument(this);
  this.notFoundFlag = false;

  this.fetchData = function(id) { 
    requestFactory.get(requestFactory.getUrl('categories/video-categories/'+id,{page : scope.currentPage}),function(response){
      this.videoCategories = response.videoCategories.category;
      this.videoCategories.videos = response.videoCategories.videos.data;
      requestFactory.toggleLoader();
      scope.totalRecords = parseInt(response.videoCategories.videos.total);
      scope.rowsPerPage  = parseInt(response.videoCategories.videos.per_page);
      scope.currentPage  = parseInt(response.videoCategories.videos.current_page);
      this.paginate(Math.ceil(scope.totalRecords/scope.rowsPerPage));
    }, function(response){
  	  self.notFoundFlag = true;
	  requestFactory.toggleLoader();
    }); 
  }

  /**
   * Function is used to call getListRecord method to get required set or records
   * 
   * @param int pageNumber
   * @param boolean orderStatus
   * @return void
   */
  scope.loadRecords = function(pageNumber,orderStatus) {
    scope.currentPage = parseInt(pageNumber);
    self.fetchData(self.videoCategories.id);
    requestFactory.toggleLoader();
  }

  this.parentCategory = function(id) {
    requestFactory.post(requestFactory.getUrl('categories/parent-category/'+id),id,function(response){
      this.parentCategoryData = response.parentCategory.parentcategoryData;
      this.parentCategoryTitle = response.parentCategory.parentcategoryTitle;
    });
  }

  this.getChildCategories = function(id) {

    requestFactory.post(requestFactory.getUrl('categories/child-category/'+id),id,function(response){
      this.childCategory = response.childCategory.child_category;
    });
  }

  this.showGridView = function() {
    this.videoGridView = true;
    this.videoListView = false;
  }

  this.showListView = function() {
    this.videoGridView = false;
    this.videoListView = true;
  }

  this.paginate = function(totalLinks) {
      scope.links = [];
        if(scope.currentPage > totalLinks) {
          return false;
        }
          var counter = Math.floor(scope.currentPage/5);
        if(counter == 0 ) {
            counter = 1;
        }
        else {
            counter = counter * 5;          
        }
        if((totalLinks - counter) >= 5 ) {
            var counterLimit = counter + 5;
        }
        else {
            var counterLimit = totalLinks;
        }
        var initialCounter = counter + 5;
        if((scope.currentPage > 1 ) && (totalLinks > 1)) {
            scope.links.push({value:'Previous',pageNumber:scope.currentPage - 1, current:false }); 
        }
        /*if((counter >= 5 ) && (totalLinks > 1) ) {
            scope.links.push({value:'First',pageNumber:1, current:false });
        }*/
        if((counter >= 4 ) && (totalLinks > 1) ) {
            scope.links.push({value:'First',pageNumber:1, current:false });
        }
            for(counter; counter <= counterLimit; counter++) {
       
          if(scope.currentPage == counter ) {
              scope.links.push({value:counter,pageNumber:counter,current:true });
          }
          else {
              scope.links.push({value:counter,pageNumber:counter,current:false });
          }            
        }
       
        if((initialCounter < totalLinks - 1) && totalLinks > 1  ) {
            scope.links.push({value: '...',pageNumber: null, current:false });
            scope.links.push({value: totalLinks - 1,pageNumber: totalLinks - 1, current:false });
            scope.links.push({value: totalLinks,pageNumber: totalLinks , current:false });
            scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
        }
        /*latest*/
        else if((initialCounter == totalLinks - 1) && totalLinks > 1) {
            scope.links.push({value: totalLinks,pageNumber: totalLinks , current:false });
            scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
        }
        else if(scope.currentPage != totalLinks && totalLinks > 1 ) {
            scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
        }
        else {
          //
        }
    };
    
    this.getVideoPresets = function(record) {
    	var i;
    	var presetsString = '';
    	for(i = 0; i < record.transcodedvideos.length; i++) {
    		presetsString = presetsString + record.transcodedvideos[i].presets.name;
    		if(record.transcodedvideos.length > i+1) {
    			presetsString = presetsString + ', ';
    		}
    		
    		if(i == 2 && record.transcodedvideos.length > 3) {
    			presetsString = presetsString + '...';
    			break;
    		}
    	}
    	return presetsString;
    };
    
    this.getVideoCasts = function(record) {
    	var i;
    	var castsString = '';
    	for(i = 0; i < record.video_cast.length; i++) {
    		castsString = castsString + record.video_cast[i].name;
    		if(record.video_cast.length > i+1) {
    			castsString = castsString + ', ';
    		}
    		
    		if(i == 2 && record.video_cast.length > 3) {
    			castsString = castsString + '...';
    			break;
    		}
    	}
    	return castsString;
    };
    
    this.getTrimmedString = function(string, length) {
    	return string.length > length ? string.substring(0, length - 3) + "..." : string;
    };

  $timeout(function(){
    angular.element('[data-toggle="popover"]').popover();    
  },300);
}]);

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['videoCategories']);
});
