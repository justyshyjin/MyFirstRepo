'use strict';
var videogroups = angular.module('videogroups',[]);

videogroups.directive('baseValidator',validatorDirective);

videogroups.factory('requestFactory',requestFactory);

videogroups.directive('bootTooltip',function(){
	return {
		restrict: 'A',
		link    : function(scope, element, attrs){
			 try {
				$(element).tooltip(); 
			 } catch(error){}
		}
	}
});

videogroups.controller('ViewVideogroupsController',['$window','$scope','$rootScope','requestFactory','$timeout',function(win,scope,$rootScope,requestFactory,$timeout){
  var self = this;
  scope.errors = {};
  scope.currentPage = 1;
  this.videogroups = {};
  this.showResponseMessage = false;
  this.gridLoadingBar = false;
  this.videoListView = true;
  requestFactory.setThisArgument(this);
  this.notFoundFlag = false;
  this.routeURL='examgroups';

  this.fetchData = function(id) {
    requestFactory.get(requestFactory.getUrl('examgroups/videos/'+id,{page : scope.currentPage}),function(response){
      this.videogroups = {videos :  response.message.data};
      requestFactory.toggleLoader();
      scope.totalRecords = parseInt(response.message.total);
      scope.rowsPerPage  = parseInt(response.message.per_page);
      scope.currentPage  = parseInt(response.message.current_page);
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
    self.fetchData(self.videogroups.id);
    requestFactory.toggleLoader();
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
    
    this.removeVideosFromCollection = function(id) {
        this.videoId = [id];
        this.collectionId = self.videogroups.id;
    };
    
    this.confirmRemoveVideosFromCollection = function() { 
        if(this.videoId.length > 0 ) {
            this.deleteRecordsVideosFromCollection(this.videoId, this.collectionId);
            this.videoId = '';
            this.collectionId = '';
        }
    };

    this.deleteRecordsVideosFromCollection = function(videoId, playlistId) {
    	requestFactory.toggleLoader();
        var deleteIdLength = videoId.length;
        scope.deleteRequest = requestFactory.post(requestFactory.getUrl('examgroups/delete'),angular.extend({},{selectedVideos:videoId.join(','),id:playlistId},scope.requestParams),function(data){
        	if(self.videogroups.videos.length - deleteIdLength == 0 ) {
	            scope.currentPage = 1;
	        }
	        self.fetchData(self.videogroups.id);
        });
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
  angular.bootstrap(document, ['videogroups']);
});
