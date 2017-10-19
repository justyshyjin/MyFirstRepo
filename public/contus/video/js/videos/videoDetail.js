'use strict';

var videoDetail = angular.module( 'videoDetail', ['flow'] );

videoDetail.directive( 'baseValidator', validatorDirective );

videoDetail.factory( 'requestFactory', requestFactory );

videoDetail.controller( 'VideoDetailController', ['flowFactory','$window','$scope','$rootScope','requestFactory','$timeout','$sce',function (flowFactory, win, scope, $rootScope, requestFactory, $timeout, strictContextual ) {
    var self = this;
    scope.errors = {};
    this.editVideo = {};
    this.allCategories = {};
    this.allExams = {};
    this.showResponseMessage = false;
    this.gridLoadingBar = false;
    requestFactory.setThisArgument( this );
    this.editVideo.cast = [];
    this.editVideo.posters = [];
    this.categorySuggestions = [];
    this.multipleCategories = [];
    this.multipleExams = [];
    this.editVideo.newPosters = [];

    window.VideoThumbnailUploadHandler = new uploadHandler;
    window.VideoThumbnailUploadHandler.initate( {file : 'thumb-image',previewer : 'thumb-preview',deleteIcon : 'thumb-delete',progress : 'image-progress',beforeUpload : function () {
        scope.errors = {};
        if ( !scope.$$phase ) {
            scope.$apply();
        }
    },afterUpload : function ( response ) {
        self.editVideo.thumbnail = response.info;
    }} );

    window.VideoSubtitleUploadHandler = new uploadHandler;
    window.VideoSubtitleUploadHandler.initate({
      file      : 'subtitle',
      previewer : 'subtitle-preview',
      deleteIcon : 'subtitle-delete',
      isImageFileType   : false ,
      validFile  : {
          fileSize :1048576000000000000,
          mime              : ["mp3"],
          fileLimit         : 1,
        },
      progress : 'file-progress',
      afterUpload : function(response){
        self.editVideo.mp3 = response.info;
      }
    });
    scope.existingFlowObject =  flowFactory.create ({
        target: document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/image',
        permanentErrors: [404, 500, 501],
        testChunks:false,
        chunkSize: 9007199254740992,
        maxChunkRetries: 1,
        chunkRetryInterval: 5000,
        simultaneousUploads: 4,
        singleFile: true
      });
  scope.existingFlowObject.on('fileSuccess', function (event,message) {
      if(message){ 
          self.editVideo.pdf = message;
          angular.element( '#loaderspdf' ).hide();
          angular.element( '.submitbutton' ).attr('disabled', false)
        }
      });
  scope.existingFlowObject.on('fileAdded', function (file){
          angular.element( '#loaderspdf' ).show();                  
          angular.element( '.submitbutton' ).attr('disabled', true)
          });
  scope.existingFlowObjectword =  flowFactory.create ({
      target: document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/image',
      permanentErrors: [404, 500, 501],
      testChunks:false,
      chunkSize: 9007199254740992,
      maxChunkRetries: 1,
      chunkRetryInterval: 5000,
      simultaneousUploads: 4,
      singleFile: true
    });
scope.existingFlowObjectword.on('fileSuccess', function (event,message) {
    if(message){ 
        self.editVideo.word = message;
        angular.element( '#loadersword' ).hide();
        angular.element( '.submitbutton' ).attr('disabled', false)
      }
    });
scope.existingFlowObjectword.on('fileAdded', function (file){
        angular.element( '#loadersword' ).show();                  
        angular.element( '.submitbutton' ).attr('disabled', true)
        });
    this.VideoPosterUploader = new uploadHandler;
    this.VideoCastImageUploader = new uploadHandler;
    /**
     *  To get the profile rules
     *  
     */
    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.allCategories = data.info.allCategories;
        this.allExams = data.info.allCollection;
        this.allCountries = data.info.allCountries;
        requestFactory.toggleLoader();
        baseValidator.setRules( this.info.video_edit_rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'videos/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();
    this.formatDate = function (date) {
    	var month = ("0" + (date.getMonth() + 1)).slice(-2);
    	  return ("0" + (date.getDate())).slice(-2) + "-" + month +"-"+date.getFullYear();
    	}

    	

    this.fetchData = function ( id ) {
        requestFactory.get( requestFactory.getUrl( 'videos/video-to-edit/' + id ), function ( response ) {
            var videoDetails = response.response;
            scope.editVideo = response.response;
            this.editVideo.id = videoDetails.id;
            this.editVideo.title = videoDetails.title;
            this.editVideo.short_description = videoDetails.short_description;
            this.editVideo.description = videoDetails.description;
            this.editVideo.is_featured = String( videoDetails.is_featured );
            this.editVideo.is_featured_time = String( videoDetails.is_featured_time);
            this.editVideo.is_active = String( videoDetails.is_active );
            this.editVideo.trailer_status = String( videoDetails.trailer_status );
            this.editVideo.thumbnail_image = videoDetails.thumbnail_image;
            this.editVideo.trailer = videoDetails.trailer;
            this.editVideo.subtitleName = videoDetails.subtitle;
            this.editVideo.disclaimer = videoDetails.disclaimer;
            this.editVideo.subscription = videoDetails.subscription;
            this.editVideo.videoposter = videoDetails.videoposter;
            this.editVideo.selected_thumb = videoDetails.selected_thumb;
            this.transcodedvideos = videoDetails.transcodedvideos;
            this.editVideo.pdf =  videoDetails.pdf;
            this.editVideo.word =  videoDetails.word;
            this.editVideo.presenter =  videoDetails.presenter;
            this.editVideo.video_order =  videoDetails.video_order;
            if (!videoDetails.published_on == true) {
                var getDate=  videoDetails.created_at;
                var date = new Date(getDate.split(" ")[0]);
                this.editVideo.published_on =  this.formatDate(date);
              
            }else{
            	var date = new Date(videoDetails.published_on);
                this.editVideo.published_on =  this.formatDate(date);
            }
            $('#published_on').datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true}).datepicker('setDate', this.editVideo.published_on);
            this.editVideo.country_id = [];

            angular.forEach( response.country_id, function ( value, key ) {
                self.editVideo.country_id.push( String( value ) );
            } );

            if ( Number( videoDetails.age ) != 0 ) {
                this.editVideo.age = Number( videoDetails.age );
            }
            scope.keywords = [];
            angular.forEach( videoDetails.tags, function ( value, key ) {
                scope.keywords.push( String( value.name ) );
            } );
            scope.collections = [];
            angular.forEach( videoDetails.collections, function ( value, key ) {
                scope.collections.push( String( value.title ) );
            } );

            this.editVideo.tags = scope.keywords;
            this.editVideo.collectionstitle = scope.collections;
            this.editVideo.videoPresets = [];
            videoDetails.transcodedvideos.forEach( function ( item, index ) {
                if ( angular.isObject( item.presets ) ) {
                    self.editVideo.videoPresets.push( item.presets.name + ' - ' + item.presets.format );
                }
            } );

            this.setCategoriesOfVideos( videoDetails );
            this.setExamsOfVideos( videoDetails );
            this.editVideo.thumbnail = '';
            this.editVideo.subtitle = '';
        }, function ( response ) {
            win.location = requestFactory.getTemplateUrl( 'admin/videos' );
        } );       
    }

    /*
     * Function to set categories of a video in the video edit form.
     */
    this.setCategoriesOfVideos = function ( videoDetails ) {
        self.editVideo.category_ids = [];
        self.multipleCategories = [];
        angular.forEach( videoDetails.videocategory, function ( value, key ) {
            self.editVideo.category_ids.push( value.category_id );
            self.multipleCategories.push( {id : value.category_id,name : value.category.title} );
        } );
    };
    this.setExamsOfVideos = function ( videoDetails ) {
        self.editVideo.exam_ids = [];
        self.multipleExams = [];
        angular.forEach( videoDetails.collections, function ( value, key ) {
            self.editVideo.exam_ids.push( value.id );
            self.multipleExams.push( {id : value.id,title : value.title} );
        } );
    };

    /*
     * Function to show categories suggestions in category field of video edit form.
     */
    this.showCategoriesSuggestions = function ( $event ) {
        var name = $event.target.value;
        self.categorySuggestions = [];
        if ( typeof name === 'string' && name != '' && name.length >= 1 ) {
            angular.forEach( self.allCategories, function ( value, key ) {
                key = Number( key );
                if ( value.toLowerCase().indexOf( name.toLowerCase() ) != -1 && self.editVideo.category_ids.indexOf( key ) == -1 ) {
                    self.categorySuggestions.push( {id : key,name : value} );
                }
            } );
        } else {
            self.categorySuggestions = [];
        }
    };

    var date = angular.element('#age');
    var checkValue = function (str, max) {
       if (str.charAt(0) !== '0' || str == '00') {
         var num = parseInt(str);
         if (isNaN(num) || num <= 0 || num > max) num = 1;
         str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
       };
       return str;
     };

     this.dateKeyup =  function(e,date) { 
       var input = date;
       if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
       var values = input.split('/').map(function(v) {
         return v.replace(/\D/g, '')
       });
       if (values[0]) values[0] = checkValue(values[0], 12);
       if (values[1]) values[1] = checkValue(values[1], 31);
       var output = values.map(function(v, i) {
         return v.length == 2 && i < 2 ? v + ' / ' : v;
       });
       self.user.age = output.join('').substr(0, 14);
     }
    /*
     * Function to show categories suggestions in category field of video edit form.
     */
    this.showExamsSuggestions = function ( $event ) {
        var title = $event.target.value;
        self.examSuggestions = [];
        if ( typeof title === 'string' && title != '' && title.length >= 1 ) {
            angular.forEach( self.allExams, function ( value, key ) {
                key = Number( key );
                if ( value.toLowerCase().indexOf( title.toLowerCase() ) != -1 && self.editVideo.exam_ids.indexOf( key ) == -1 ) {
                    self.examSuggestions.push( {id : key,title : value} );
                }
            } );
        } else {
            self.examSuggestions = [];
        }
    };
    /*
     * Function to add a category to the category field in video edit form.
     */
    this.addCategoriesToVideos = function ( id, categoryName ) {
        self.editVideo.category_ids = [];
        self.multipleCategories = [];
        self.editVideo.category_ids.push( id );
        self.multipleCategories.push( {id : id,name : categoryName} );
        self.categoryField = '';
        self.examField = '';
        self.categorySuggestions = [];
    };
    /*
     * Function to add a category to the category field in video edit form.
     */
    this.addExamToVideos = function ( id, examName ) {
        self.editVideo.exam_ids.push( id );
        self.multipleExams.push( {id : id,title : examName} );
        self.examField = '';
        self.examSuggestions = [];
    };

    /*
     * Function to remove a category from the category field in video edit form.
     */
    this.removeCategoriesFromVideos = function ( index ) {
        // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
        if ( self.editVideo.category_ids.length > 1 ) {
            var categoryId = self.multipleCategories [index].id;
            var categoryIdIndex = self.editVideo.category_ids.indexOf( categoryId );
            if ( categoryIdIndex > -1 ) {
                self.editVideo.category_ids.splice( categoryIdIndex, 1 );
            }
            self.multipleCategories.splice( index, 1 );
        }
    };

    /*
     * Function to remove a category from the category field in video edit form.
     */
    this.removeExamsFromVideos = function ( index ) {
        // Check if there are more than one category selected. If yes, allow to remove the category and if no, restrict from removing the category.
        if ( self.editVideo.exam_ids.length > 0 ) {
            var examId = self.multipleExams [index].id;
            var examIdIndex = self.editVideo.exam_ids.indexOf( examId );
            if ( examIdIndex > -1 ) {
                self.editVideo.exam_ids.splice( examIdIndex, 1 );
            }
            self.multipleExams.splice( index, 1 );
        }
    };
    /**
     *  Functtion is used to fill the error
     *  
     */
    
    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    this.removeThumbnailProperty = function () {
        self.editVideo.thumbnail = '';
    };

    this.removeSubtitleProperty = function () {
        self.editVideo.subtitle = '';
    };

    /*
     * Function to delete custom thumbnail of a video.
     */
    this.deleteThumbnail = function () {
        requestFactory.post( requestFactory.getUrl( 'videos/delete-thumbnail/' + this.editVideo.id ), this.editVideo, function () {
            self.resetVideoThumbnailUpload();
        }, function () {
        } );
    };

    this.resetVideoThumbnailUpload = function () {
        if ( typeof window.VideoThumbnailUploadHandler == 'object' ) {
            $timeout( function () {
                angular.element( '[data-dismiss="fileupload"]' ).trigger( "click" );
            }, 0, true );
            self.editVideo.thumbnail = '';
            self.editVideo.thumbnail_image = '';
        }
    };

    this.deleteSubtitle = function () {
        requestFactory.post( requestFactory.getUrl( 'videos/delete-subtitle/' + this.editVideo.id ), this.editVideo, function () {
            self.resetVideoSubtitleUpload();
        }, function () {
        } );
    };

    /*
     * Function to delete a poster of a video.
     */
    this.deletePoster = function ( poster, index ) {
        requestFactory.post( requestFactory.getUrl( 'videos/delete-poster/' + poster.id ), poster, function () {
            self.resetVideoPosterUpload( poster, index );
        }, function () {
        } );
    };

    this.deleteNewPoster = function ( index ) {
        self.editVideo.posters.splice( index, 1 );
        self.editVideo.newPosters.splice( index, 1 );
    };

    this.resetVideoPosterUpload = function ( poster, index ) {
        self.editVideo.videoposter.splice( index, 1 );
    };

    this.resetCastImageUpload = function ( cast, index ) {
        self.editVideo.cast [index].image_url = null;
        self.editVideo.cast [index].image_path = null;
    };

    // Update the video details
    this.saveVideoEdit = function ( $event, url) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            requestFactory.toggleLoader();
            requestFactory.post( requestFactory.getUrl( 'videos/edit/' + this.editVideo.id ), this.editVideo, function () {
                var parts = url.split('/');
                var lastSegment = parts.pop() || parts.pop();
                if(lastSegment == 'videos') {
                    win.location = requestFactory.getTemplateUrl('admin/videos');
                } else {
                    win.location = requestFactory.getTemplateUrl('admin/livevideos');
                }
            }, function ( response ) {
                requestFactory.toggleLoader();
                this.fillError( response );
            } );
        }
    };

    this.addOption = function () {
        this.editVideo.cast.push( {cast_name : null,cast_role : null} );
    };

    this.removeOption = function ( key ) {
        this.editVideo.cast.splice( key, 1 );
    };

    this.showPosterModel = function () {
        $( 'div#postersModal' ).modal( 'show' );

        this.VideoPosterUploader.initate( {file : 'poster-images',button : 'poster-image-upload-proceed',progress : 'poster-progress',afterUpload : function ( response ) {
            this.removeProgressListByIndex( response.file.index );
            self.editVideo.posters.push( {temp : response.info} );
            self.editVideo.newPosters.push( {file : response.file} );
            if ( !scope.$$phase ) {
                scope.$apply();
            }
        }} );

        $( 'div#postersModal' ).on( 'hidden.bs.modal', function () {
            self.VideoPosterUploader.emptyProgressList().cleanFileElement();
        } );
    };

    this.showCastModel = function ( cast, index ) {
        $( 'div#castImageModal' ).modal( 'show' );

        this.VideoCastImageUploader.initate( {file : 'cast-images',button : 'cast-image-upload-proceed',progress : 'cast-progress',afterUpload : function ( response ) {
            this.removeProgressListByIndex( response.file.index ).cleanFileElement();
            self.editVideo.cast [index].image = response.info;
            self.editVideo.cast [index].file = response.file;
            if ( !scope.$$phase ) {
                scope.$apply();
            }
            $( 'div#castImageModal' ).modal( 'hide' );
        }} );

        $( 'div#castImageModal' ).on( 'hidden.bs.modal', function () {
            self.VideoCastImageUploader.emptyProgressList().cleanFileElement();
        } );
    };

    this.createImageElementWithFileObject = function ( file ) {
        return strictContextual.trustAsHtml( '<img src="' + file.previewSrc + '" title="' + file.name + '" width="20" height="20">' );
    };

    this.createImageElementWithUrl = function ( image ) {
        return strictContextual.trustAsHtml( '<img src="' + image.image_url + '" title="' + image.cast_name + '" width="20" height="20">' );
    };

    var x = 0
    $( ".clone_table" ).delegate( '.add_row', 'click', function ( e ) {

        e.preventDefault();
        var thisRow = $( this ).closest( 'tr' ) [0];
        var cloneElement = $( thisRow ).clone().insertAfter( thisRow );

        cloneElement.find( 'input:text' ).val( '' );
        x = x + 1;
        cloneElement.find( 'input:text' ).each( function () {
            if ( $( this ).attr( 'cast-name' ) == 'cast_name' ) {
                $( this ).attr( 'name', 'cast_name_' + x );
                $( this ).attr( 'data-ng-model', 'vgridCtrl.editVideo.cast_name[' + x + ']' );
            }
            if ( $( this ).attr( 'cast-role' ) == 'cast_role' ) {
                $( this ).attr( 'name', 'cast_role_' + x );
                $( this ).attr( 'data-ng-model', 'vgridCtrl.editVideo.cast_role[' + x + ']' );
            }
        } );

        $( this ).html( "-" ).addClass( "rem_row" ).removeClass( "add_row" );

        $( '.rem_row' ).click( function () {
            $( this ).parent().parent().remove();
        } );
    } );

}] );

videoDetail.directive( 'selectTwo', function () {
    return {link : function ( scope, elm, attr ) {
        scope.keywords = [];
    }};
} ).directive( 'keywordEditable', ['$document','$timeout','$http',function ( $document, $timeout, $http ) {
    return {require : 'ngModel',link : function ( scope, elm, attrs, ctrl ) {
        var routeName = attrs.routeName;
        elm.on( 'keydown', function () {
            if ( event.keyCode == 13 || event.keyCode == 188 ) {
                if ( scope.keywords.indexOf( elm.text() ) == -1 ) {
                    if ( elm.text() != "" ) {
                        scope.keywords.push( elm.text().trim() );
                    }
                }
                ctrl.$setViewValue( '' );
                elm.text( '' );
                event.preventDefault();
            }
            if ( event.keyCode == 8 ) {
                if ( elm.text() == '' ) {
                    scope.keywords.splice( scope.keywords.length - 1, 1 );
                    ctrl.$setViewValue();
                }
            }
        } );
       
        scope.removeKeyword = function ( index ) {
            scope.keywords.splice( index, 1 );
        };
        scope.addTag = function ( name ) {
            scope.keywords.push( name );
            ctrl.$setViewValue( '' );
            elm.text( '' );
        }

        // load init value from DOM
        ctrl.$setViewValue( elm.text() );
    }};
}] );

/**
* Manually bootstrap the Angular module here
*/
angular.element( document ).ready( function () {
    angular.bootstrap( document, ['videoDetail'] );
} );
