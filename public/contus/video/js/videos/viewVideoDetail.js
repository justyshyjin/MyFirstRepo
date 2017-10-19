'use strict';

var viewVideoDetail = angular.module('viewVideoDetail',["ui.bootstrap"]);

viewVideoDetail.directive('baseValidator',validatorDirective);

viewVideoDetail.filter( 'getCount', function () {
    return function ( obj ) {
        if ( obj ) {
            return Object.keys( obj ).length;
        }
    };
} ).filter( 'convertDate', function () {
    return function ( obj ) {
        if ( obj ) {
            return Date.parse( obj );
        }
    };
} ).filter( 'convertAgoTime', function () {
    return function ( obj ) {
        if ( obj ) {
            var time = new Date().getTime();
            time = parseInt(time/1000) - parseInt(obj/1000);
            time = ( time < 1 ) ? 1 : time;
            var tokens = [
                    {'id' :'31536000' ,'text': 'year'},
                    {'id' :'2592000','text': 'month'},
                    {'id' :'604800','text' : 'week'},
                    {'id' : '86400','text' : 'day'},
                    {'id' :'3600','text': 'hour'},
                    {'id' :'60','text' : 'minute'},
                    {'id' :'1','text' : 'second'}
            ];
            
            for ( var unit in tokens ) {
                var text = tokens[ unit ].text;
                unit = parseInt( tokens[ unit ].id );
                if ( time < unit ) {
                    continue;
                }
                var numberOfUnits = Math.floor( time / unit );
                return numberOfUnits + ' ' + text + ( ( numberOfUnits > 1 ) ? 's' : '' ) + ' ago';
            }
        }
    };
} ).filter( 'getByKey', function () {
    return function ( input, id, column, data, field ) {
        var column = angular.isUndefined( column ) ? 'id' : column;
        var i = 0;
        if ( angular.isArray( input ) ) {
            for ( ; i < input.length; i++ ) {
                if ( input[ i ] && input[ i ][ column ] == id ) {
                    return ( data == 'key' ) ? i : ( field ) ? input[ i ][ field ] : input[ i ];
                }
            }
        } else {
            var result = null;
            angular.forEach( input, function ( val, key ) {
                if ( val[ column ] == id ) {
                    result = ( data == 'key' ) ? key : val;
                }
            } );
            return result;
        }
        return null;
    };
} );
viewVideoDetail.factory('requestFactory',requestFactory);

var initflowplayer = function ( $rootScope,requestFactory ) {
    return {
        restrict : 'A',
        replace : true,
        scope : {
            video : '='
        },
        link : function ( scope,elem,attr ) {
            scope.$watch('video', function (video) {
                if (video) {
                    if(video && video.youtube_live == 0 && video.job_status == 'Complete') {
                    flowplayer(elem, {
                        adaptiveRatio:true,
                        splash: false,
                        width: "100%",
                        duration: 2,
                        seekable: true,
                        keyboard:true,
                        poster : video.selected_thumb,
                        volume : 0.2,
                        speeds : [0.5, 1.0,1.25, 1.5, 2.0],
                        embed : false,
                        twitter: false,
                        share :false,
                        autoplay: true,
                        facebook :false,
                        logo :requestFactory.getBaseTemplateUrl()+'/contus/base/images/logo-xs.png',
                        // optional: HLS levels offered for manual selection
                        hlsQualities: true,
                        clip: {
                            title: video.title, // updated on ready
                            sources: [{'type': "application/x-mpegurl",'src':video.hls_playlist_url}]
                        }
                    });
                }else if(video.youtube_id){
                        elem.html('<iframe width="100%" height="349" src="http://www.youtube.com/embed/' + video.youtube_id + '?rel=0" frameborder="0" allowfullscreen ></iframe>');
                    }else if(video.username){
                        flowplayer(elem, {
                            adaptiveRatio:true,
                            splash: false,
                            width: "100%",
                            duration: 2,
                            seekable: true,
                            keyboard:true,
                            poster : video.selected_thumb,
                            volume : 0.2,
                            speeds : [0.5, 1.0,1.25, 1.5, 2.0],
                            embed : false,
                            twitter: false,
                            share :false,
                            autoplay: true,
                            facebook :false,
                            live:1,
                            logo :requestFactory.getBaseTemplateUrl()+'/contus/base/images/logo-xs.png',
                            // optional: HLS levels offered for manual selection
                            hlsQualities: true,
                            clip: {
                                title: video.title, // updated on ready
                                sources: [{'type': "application/x-mpegurl",'src':video.hls_playlist_url}]
                            }
                        });
                }
                }
            });
        }
    }
};
viewVideoDetail.controller('ViewVideoDetailsController',['$window','$scope','$rootScope','requestFactory','$sce','$timeout',function(win,scope,$rootScope,requestFactory,$sce,$timeout){
    var self = this;
    scope.errors = {};
    this.editVideo = {};
    this.allCategories = {};
    this.showResponseMessage = false;
    this.gridLoadingBar = false;
    requestFactory.setThisArgument(this);
    this.notFoundFlag = false;
    var commenturl = '';
    var questionurl = '';
    this.fetchData = function(id) {
      requestFactory.get(requestFactory.getUrl('videos/complete-video-details/'+id),function(response){
        requestFactory.toggleLoader();
        var videoDetails = response.response;
        scope.video = response.response;
        this.fetchComments();
        initflowplayer();
        this.editVideo.id = videoDetails.id;
        this.editVideo.title = videoDetails.title;
        this.editVideo.short_description = videoDetails.short_description;
        this.editVideo.description = videoDetails.description;
        this.editVideo.trimmed_description = this.getTrimmedString(videoDetails.description, 300);
        this.setDescriptionData(300);
        this.editVideo.is_featured = String(videoDetails.is_featured);
        this.editVideo.is_active = String(videoDetails.is_active);
        this.editVideo.thumbnail_image = videoDetails.thumbnail_image;
        this.editVideo.transcodedvideos = videoDetails.transcodedvideos;
        this.editVideo.subscription = videoDetails.subscription;
        this.editVideo.video_cast = videoDetails.video_cast;
        this.editVideo.age = videoDetails.age;
        this.editVideo.videocategory = videoDetails.videocategory;
        this.editVideo.tags = videoDetails.tags;
        this.editVideo.subtitle = videoDetails.subtitle;
        this.editVideo.videoposter = videoDetails.videoposter;
        this.editVideo.categories = videoDetails.categories;
        
        this.editVideo.relatedVideos = [];
        commenturl = requestFactory.getUrl( 'comments/' + scope.video.id );
        questionurl = requestFactory.getUrl( 'qa/' + scope.video.id );
        
        $timeout(function(){
          $('[data-toggle="popover"]').popover();
        },100);
        
        this.editVideo.videoPresets = [];
        this.setCategoriesOfVideos(videoDetails);
        this.editVideo.thumbnail = '';
        
        // Check trailer field and get the youtube id of the trailer.
        var videoId = videoDetails.trailer.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
    	if(videoId != null) {
    		// Trailer URL is a valid Youtube Video URL.
    	   this.editVideo.trailerId = videoId[1];
    	   this.editVideo.trailerEmbedUrl = $sce.trustAsResourceUrl('https://www.youtube.com/embed/' + this.editVideo.trailerId);
    	} else { 
    		// Trailer URL is not valid.
    		this.editVideo.trailerId = '';
    	}
    	
    	// Form the allowed countries string.
    	this.editVideo.videocountry = '';
    	$('#trailerModal').on('shown.bs.modal', function() {
    		document.querySelector('#trailerModal .modal-body').innerHTML = '<iframe width="560" height="315" src="'+self.editVideo.trailerEmbedUrl+'" frameborder="0" allowfullscreen></iframe>';
    	});
    	$('#trailerModal').on('hidden.bs.modal', function () {
    	    document.querySelector('#trailerModal .modal-body').innerHTML = '';
    	});
      }, function(response){
    	  self.notFoundFlag = true;
    	  requestFactory.toggleLoader();
      }); 
    }
    this.fetchComments= function(){
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('comments/'+scope.video.id),{},function(response){
            this.fetchQuestions();
            requestFactory.toggleLoader();//question
            scope.comment = response.response;
        },function(response){
            requestFactory.toggleLoader();
            angular.element('.comments-answer-tab').hide();
        });
    }
    this.fetchQuestions= function(){
        requestFactory.toggleLoader();
        requestFactory.post(requestFactory.getUrl('qa/'+scope.video.id),{},function(response){
            requestFactory.toggleLoader();
            scope.question = response.response;
        },function(response){
            requestFactory.toggleLoader();
            angular.element('.comments-answer-tab').hide();
        });
    }

    this.isAlreadyExistInRelatedItem = function(currentRelatedItem) {
      var isAlreadyNotExist = true;

      this.editVideo.relatedVideos.forEach(function(relatedItem, index){
        if (currentRelatedItem.id == relatedItem.id) {
          isAlreadyNotExist = false;
        }
      });

      return isAlreadyNotExist;
    }

    this.parentCategory = function(id) {
      requestFactory.post(requestFactory.getUrl('category/parent-category/'+id),id,function(response){
        this.parentCategoryTitle = response.parentCategory.parentcategoryTitle;
        
      });
    }


    /*
     * Function to set categories of a video in the video edit form.
     */
    this.setCategoriesOfVideos = function(videoDetails){
      self.editVideo.category_ids = [];
      self.multipleCategories = [];
      angular.forEach(videoDetails.videocategory,function(value,key){
        self.editVideo.category_ids.push(value.category_id);
        self.multipleCategories.push({id:value.category_id, name:self.allCategories[value.category_id]});
      });
    };

    $('#myTabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    })
scope.updateStatus = function ( record,status ) {
    	requestFactory.post(requestFactory.getUrl('comments/updatestatus/'+record.id+'?status='+status),{},function(response){
    		var data = {comment : "",parent_id : ""};
            PostComment();
          });
        
    };
    scope.updateQAStatus = function ( record,status ) {
    	requestFactory.post(requestFactory.getUrl('qa/updatestatus/'+record.id+'?status='+status),{},function(response){
    		var data = {question : ""};
    		PostQuestion();
          });
        
    };
    /**
     *  Functtion is used to fill the error
     *  
     */ 
    this.fillError = function(response) { 
      if(response.status == 422 && response.data.hasOwnProperty('messages')) {         
        angular.forEach(response.data.messages, function(message,key) {
          if(typeof message == 'object' && message.length > 0){         
            scope.errors[key] = {has : true , message : message[0]};
          }
        });
      }
    };
    
    this.setDescriptionData = function(length) {
    	if(self.editVideo.description.length > length) {
    		self.editVideo.trimFlag = true;
    		self.editVideo.descriptionContent = self.editVideo.trimmed_description;
    	}
    	else {
    		self.editVideo.trimFlag = false;
    		self.editVideo.descriptionContent = self.editVideo.description;
    	}
    };
    
    this.getTrimmedString = function(string, length) {
    	return string.length > length ? string.substring(0, length - 3) + "..." : string;
    };
    
    this.showFullDescription = function() {
    	self.editVideo.trimFlag = false;
    	self.editVideo.descriptionContent = self.editVideo.description;
    };

    scope.postparentcomment = function ( val ) {
        if ( val !== '' && val !== undefined ) {
            var data = {comment : val};
            PostComment( data );
            scope.parentcomment = "";
        }
    }
    scope.postparentquestion = function ( val ) {
        if ( val !== '' && val !== undefined ) {
            var data = {question : val};
            PostQuestion( data );
            scope.parentquestion = "";
        }
    }

    scope.postchildcomment = function ( id, comment ) {
        if ( comment !== '' && comment !== undefined ) {
            var data = {comment : comment,parent_id : id};
            PostComment( data );
            angular.element( '.childcomment' ).value = "";
        }
    }
    scope.postchildquestion = function ( id, question ) { 
        if ( question !== '' ) {
            var data = {question : question,parent_id : id};
            PostQuestion( data );
            angular.element( '.childquestion' ).value = "";
        }
    }

    var commentsuccess = function ( response ) {
        dataBinderComments( response.response );
        //ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
        angular.element( 'textarea' ).val( '' );
        setTimeout( function () {
            $( 'button.btn-cancel[type="button"][ng-click="parentcomment=\'\'"]' ).trigger( 'click' );
        }, 500 );
    }
    var dataBinderComments = function ( response ) {
        scope.comment = response;
    }
    var dataBinderQuestions = function ( response ) {
        scope.question = response;
    }
    var questionsuccess = function ( response ) {
        dataBinderQuestions( response.response );
        //ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
        angular.element( 'textarea' ).val( '' );
    }

    var commentfail = function ( response ) {
        //ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

    }
    var questionfail = function ( response ) {
        //ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

    }
    var PostComment = function ( data = null) { 
        requestFactory.post( commenturl, data, commentsuccess, commentfail );
    }
    var PostQuestion = function ( data = null ) {
        requestFactory.post( questionurl, data, questionsuccess, questionfail );
    }

    scope.loadprevcomment = function () {
        commenturl = scope.comment.prev_page_url;
        requestFactory.post( commenturl, {}, commentsuccess, commentfail );
    }
    scope.loadprevquestion = function () {
        questionurl = scope.question.prev_page_url;
        requestFactory.post( questionurl, {}, questionsuccess, questionfail );
    }

    scope.loadmorecomment = function () {
        commenturl = scope.comment.next_page_url;
        requestFactory.post( commenturl, {}, commentsuccess, commentfail );
    }
    scope.loadmorequestion = function () {
        questionurl = scope.question.next_page_url;
        requestFactory.post( questionurl, {}, questionsuccess, questionfail );
    }
}]);
viewVideoDetail.directive( 'initFlowPlayer', initflowplayer );
/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['viewVideoDetail']);
});
