'use strict';

var VideoGridController = ['flowFactory','$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function (flowFactory,scope, requestFactory, $window, $sce, $timeout, $compile, $interval ) {
    var self = this;
    this.info = {};
    this.category = {};
    this.collection = {};
    this.playlist = {};
    this.allPlaylists = {};
    this.allCollections = {};
    this.selectedRecords = [];
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument( this );
    this.showcreateCollection = false;
    this.showcreatePlaylist = false;
    this.video = {};
    this.editVideo = {};
    scope.videoConfirmationDeleteBox = false;
    angular.element( '.alert-success' ).fadeIn( 1000 ).delay( 5000 ).fadeOut( 1000 );
    this.categorySuggestions = [];
    this.multipleCategories = [];
    this.videoUploadCompleteCount = 0;
    this.uploadIntervalFlag = false;
    this.videoUploadRequestCount = 0;
    this.totalVideosCount = 0;
    this.videoGridView = true;
    scope.addVideoFields =[];
    self.editVideo.category_ids = [];
	self.editVideo.exam_ids = [];
	this.allExams = {};
    this.multipleExams = [];
    this.showGridView = function () {
        this.videoGridView = true;
        this.videoListView = false;
    }

    this.showListView = function () {
        this.videoGridView = false;
        this.videoListView = true;
    }
    scope.toggleTab = function ( tab ) {
        if ( scope.tabSelected == tab ) {
            scope.filters.tab = '';
            scope.tabSelected = '';
            scope.currentPage = 1;
            scope.showRecords = false;
            scope.gridLoadingBar = true;
            scope.getRecords( true );
        } else {
            scope.selectTab( 'live_videos' );
        }
    }
    this.setupVideoUploader = function () {
        this.videoUploader = new videoUploader(scope);
        this.videoUploader.initiate( {id : 'video',dropAreaId : 'file_drop_area',afterUpload : function ( response ) {
            self.video.video_details = response;
			
            self.saveSingleVideo();
        },beforeUpload : function ( totalVideosCount ) {
            self.totalVideosCount = totalVideosCount;
            // Reset the values because this upload might be after failure.
            self.videoUploadCompleteCount = 0;
            self.uploadIntervalFlag = false;
            self.videoUploadRequestCount = 0;
        },} );
    };
    this.setupGoogleDriveUploader = function () {
        this.googleDriveUploader = new googleDriveUploader();
        this.googleDriveUploader.initiate( {id : 'google_drive_upload_button',afterUpload : function ( response ) {
            self.video.video_details = response;
            self.saveSingleVideo();
        },beforeUpload : function ( totalVideosCount ) {
            self.totalVideosCount = totalVideosCount;
            // Reset the values because this upload might be after failure.
            self.videoUploadCompleteCount = 0;
            self.uploadIntervalFlag = false;
            self.videoUploadRequestCount = 0;
        },} );
    };   
    this.saveSingleVideo = function () {
        self.videoUploadRequestCount++;
        // Check if this video is the last video in the current upload. If yes, then show the loader.        
       requestFactory.post( requestFactory.getUrl( 'videos/add' ), this.video, function (response) {
		if(response[0] != 'undefined' && response[0]){
			document.getElementById('video_id'+self.videoUploadRequestCount++).value = response[0];
		}
        }, function () {
        } );
    };
    scope.startlivestream = function(record){
        requestFactory.toggleLoader();
        requestFactory.post( requestFactory.getUrl( 'startlivestream' ), record, function () {
            requestFactory.toggleLoader();
            scope.getRecords(true);
        }, function () {
            requestFactory.toggleLoader();
        } );
    }
    scope.getStatusLive = function(){
        console.log(scope.selectId);
        for (var i = 0, len = scope.selectId.length; i < len; i++) {
            getStatusLiveUpdating(scope.selectId[i]);
        }
    }
    var getStatusLiveUpdating = function(record){
        setTimeout(function(){
            requestFactory.post( requestFactory.getUrl( 'satuslivestream' ), record, function (response) {
                console.log(record);
                if(response.response === 'starting'){
                    getStatusLiveUpdating(record);
                }else{
                    scope.getRecords(true);
                }
            }, function (response) {
                scope.getRecords(true);
            } ); 
        }, 15000);
    }
    scope.stoplivestream = function(record){
        requestFactory.toggleLoader();
        requestFactory.post( requestFactory.getUrl( 'stoplivestream' ), record, function () {
            requestFactory.toggleLoader();
            scope.getRecords(true);
        }, function () {
            requestFactory.toggleLoader();
        } );
    }
    this.saveVideo = function () {
        requestFactory.toggleLoader();
        requestFactory.post( requestFactory.getUrl( 'videos/add' ), this.video, function () {
            $window.location = requestFactory.getTemplateUrl( 'admin/videos' );
        }, function () {
        } );
    };

    this.showUploadOption = function () {
        document.querySelector( '.video_grid' ).style.display = 'none';
        document.querySelector( '.add_video_container' ).style.display = 'block';        
    };

    this.hideUploadOption = function () {
        document.querySelector( '.video_grid' ).style.display = 'block';
        document.querySelector( '.add_video_container' ).style.display = 'none';
        document.querySelector('#video_form_fields').style.display = 'none';
    };

    /*
     * Function to pause the video if it is playing when video edit sidebar is closed.
     */
    this.pauseVideo = function () {
        if ( document.getElementsByClassName( 'st-menu-open' ).length > 0 ) {
            var myPlayer = videojs( 'video_player' );
            if ( !myPlayer.paused() ) {
                myPlayer.pause();
            }
        }
    };

    this.editVideoForm = function ( videoDetails ) {
        // Reset the thumbnail image uploader
        self.resetVideoThumbnailUpload();
        // Reset the video edit tabs
        $( '.pop_over_continer .nav-tabs a:first' ).tab( 'show' );
        // Clear form errors for any previous video.
        scope.errors = {};
        // Set rules for edit video form
        baseValidator.setRules( this.info.video_edit_rules );
        if ( videoDetails.job_status == 'Complete' ) {
            scope.transcodeMessage = false;
            document.getElementById( "video_player" ).style.display = "block";
            // Change video url and preview image url.
            var myPlayer = videojs( 'video_player' );
            myPlayer.src( [{type : "video/mp4",src : $sce.trustAsResourceUrl( videoDetails.transcodedvideos [0].video_url )}] );
            if ( angular.isString( videoDetails.thumbnail_image ) && videoDetails.thumbnail_image.length > 0 ) {
                myPlayer.poster( $sce.trustAsResourceUrl( videoDetails.thumbnail_image ) );
            } else {
                myPlayer.poster( $sce.trustAsResourceUrl( videoDetails.selected_thumb ) );
            }
        } else {
            scope.transcodeMessage = true;
            document.getElementById( "video_player" ).style.display = "none";
        }
        this.editVideo.id = videoDetails.id;
        this.editVideo.title = videoDetails.title;
        this.editVideo.short_description = videoDetails.short_description;
        this.editVideo.description = videoDetails.description;
        this.editVideo.is_featured = String( videoDetails.is_featured );
        this.editVideo.is_active = String( videoDetails.is_active );
        this.editVideo.thumbnail_image = videoDetails.thumbnail_image;
        this.editVideo.videoPresets = [];
        videoDetails.transcodedvideos.forEach( function ( item, index ) {
            if ( angular.isObject( item.presets ) ) {
                self.editVideo.videoPresets.push( item.presets.name + ' - ' + item.presets.format );
            }
        } );
        this.setCategoriesOfVideos( videoDetails );
    };

    /*
     * Function to set categories of a video in the video edit form.
     */
    this.setCategoriesOfVideos = function ( videoDetails ) {
        self.editVideo.category_ids = [];
        self.multipleCategories = [];
        angular.forEach( videoDetails.videocategory, function ( value, key ) {
            self.editVideo.category_ids.push( value.category_id );
            self.multipleCategories.push( {id : value.category_id,name : self.allCategories [value.category_id]} );
        } );
    };

    this.saveVideoEdit = function ( $event ) {
	var videoId = $event.currentTarget.childNodes[1].childNodes[1].value;
	$event.currentTarget.childNodes[1].childNodes[5].childNodes[1].className = 'form-group wid-50';
    $event.currentTarget.childNodes[1].childNodes[5].childNodes[1].childNodes[5].style.display = 'none';
        if ( baseValidator.validateAngularForm( $event.target, scope ) &&  self.editVideo.category_ids.length > 0) {

            requestFactory.toggleLoader();
            requestFactory.post( requestFactory.getUrl( 'videos/edit/' + videoId ), this.editVideo, function ( response ) {
                requestFactory.toggleLoader();
                this.responseMessage = response.message;
                this.showResponseMessage = true;
                scope.getRecords( true );
                this.closeVideoEdit();
                self.resetVideoThumbnailUpload();
				$window.location = requestFactory.getTemplateUrl( 'admin/videos' );
            }, function ( response ) {
                requestFactory.toggleLoader();
                this.fillError( response );
            } );
        }else{
		$event.currentTarget.childNodes[1].childNodes[5].childNodes[1].className += ' has-error';
		$event.currentTarget.childNodes[1].childNodes[5].childNodes[1].childNodes[5].style.display = 'block';
		}
    };

    this.setVideoEditRules = function () {
        // Set rules for edit video form
        baseValidator.setRules( this.info.video_edit_rules );
    };

    this.setThumbUploadRules = function () {
        // Set rules for thumbnail upload form
        baseValidator.setRules( this.info.thumb_upload_rules );
    };

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'messages' ) ) {
            angular.forEach( response.data.messages, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };
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
    this.thumbnailUpload = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            requestFactory.toggleLoader();
            requestFactory.post( requestFactory.getUrl( 'videos/upload-thumbnail/' + this.editVideo.id ), this.editVideo, function ( response ) {
                requestFactory.toggleLoader();
                this.responseMessage = response.message;
                this.showResponseMessage = true;
                scope.getRecords( true );
                this.closeVideoEdit();
                self.resetVideoThumbnailUpload();
            }, function ( response ) {
                requestFactory.toggleLoader();
                this.fillError( response );
            } );
        }
    };

    this.removeThumbnailProperty = function () {
        self.editVideo.thumbnail = '';
    };
    /*
     * Function to delete custom thumbnail of a video.
     */
    this.deleteThumbnail = function () {
        requestFactory.toggleLoader();
        requestFactory.post( requestFactory.getUrl( 'videos/delete-thumbnail/' + this.editVideo.id ), this.editVideo, function ( response ) {
            requestFactory.toggleLoader();
            self.responseMessage = response.message;
            self.showResponseMessage = true;
            scope.getRecords( true );
            self.closeVideoEdit();
            self.resetVideoThumbnailUpload();
        }, function () {
        } );
    };

    this.closeVideoEdit = function () {
        self.pauseVideo();
        classie.remove( document.getElementById( 'st-container' ), 'st-menu-open' );
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

    this.defineProperties = function ( data ) {
        this.info = data.info;
        this.allCollections = data.info.allCollection;
		this.allExams = data.info.allCollection;
        this.allCategories = data.info.allCategories;
        scope.livedetails = data.info.livesyncdata[0];
        this.numberOfActivePresets = data.info.numberOfActivePresets;
		baseValidator.setRules( this.info.video_edit_rules );
		this.editVideo.is_featured = String(0);
        this.editVideo.is_featured_time = String(0);
		this.editVideo.trailer_status = String(0);
		this.editVideo.is_active = String(0);
		this.editVideo.tags = scope.keywords;
		angular.element( '#move_collection' ).removeAttr( 'data-toggle' );
        this.setupVideoUploader();
        this.setupGoogleDriveUploader();
        requestFactory.toggleLoader();
    };
    this.resetFormData = function ( event ) {
        this.collection = {};
        scope.errors = {};
        requestFactory.get( requestFactory.getUrl( 'videos/collection-update' ), function ( response ) {
            this.allCollections = response.info.allCollection;
            baseValidator.setRules( this.info.video_edit_rules );
        } );
        this.showcreateCollection = true;
        this.collection.id = String( 0 );
    }

    this.resetFormDataPlaylist = function ( event ) {
        this.playlist = {};
        scope.errors = {};
        requestFactory.get( requestFactory.getUrl( 'playlists/playlists-all' ), function ( response ) {
            this.allPlaylists = response.info.allPlaylists;
            baseValidator.setRules( this.info.video_edit_rules );
        } );
        this.showcreatePlaylist = true;
        this.playlist.id = String( 0 );
    }

    this.deleteSingleRecordVideos = function ( id ) {
        scope.deleteParams = [id];
        scope.videoConfirmationDeleteBox = true;
    };

    this.deleteBulkRecord = function () {
        scope.deleteParams = this.selectedRecords;
        this.isDeactivateBulkRecord = false;
        this.isActivateBulkRecord = false;
        this.isDeleteBulkRecord = true;
    }

    this.activateOrDeactivateBulkRecord = function ( $isActivateOrDeactivate ) {
        scope.activateParams = this.selectedRecords;
        if ( $isActivateOrDeactivate == 'activate' ) {
            this.isDeleteBulkRecord = false;
            this.isDeactivateBulkRecord = false;
            this.isActivateBulkRecord = true;
        } else if ( $isActivateOrDeactivate == 'deactivate' ) {
            this.isDeleteBulkRecord = false;
            this.isActivateBulkRecord = false;
            this.isDeactivateBulkRecord = true;
        }
    }

    this.cancelDeleteVideos = function () {
        scope.videoConfirmationDeleteBox = false;
        scope.deleteParams = '';
    };

    this.confirmDeleteVideos = function ( videoStatus ) {
        if ( scope.deleteParams.length > 0 ) {
            self.deleteRecordsVideos( scope.deleteParams, videoStatus );
            scope.videoConfirmationDeleteBox = false;
            if ( videoStatus == 'bulk-video' ) {
                this.selectedRecords = [];
            }
            scope.deleteParams = '';
        } else {
            scope.videoConfirmationDeleteBox = false;
            scope.deleteParams = '';
        }
    };

    this.confirmActivateOrDeactivateVideos = function ( is_status ) {
        if ( is_status == 1 ) {
            this.isActivateBulkRecord = false;
        } else if ( is_status == 0 ) {
            this.isDeactivateBulkRecord = false;
        }
        self.activateOrDeactivateRecordsVideos( scope.activateParams, is_status );
    }

    this.deleteRecordsVideos = function ( id, videoStatus ) {
        scope.deleteParams = '';
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        var deleteIdLength = id.length;

        scope.deleteRequest = requestFactory.post( requestFactory.getUrl( 'videos/delete-action' ), angular.extend( {}, {selectedCheckbox : id,videoStatus : videoStatus}, scope.requestParams ), function ( data ) {
            this.responseMessage = data.message;
            this.showResponseMessage = true;
            scope.deleteId = [];
            angular.element( '#selectall' ).removeAttr( 'checked' );
            if ( scope.records.length - deleteIdLength > 0 ) {
                scope.getRecords( true );
            } else {
                scope.currentPage = ( scope.currentPage - 1 == 0 ) ? 1 : scope.currentPage - 1;
                scope.getRecords( true );
            }
        } );
    };

    this.activateOrDeactivateRecordsVideos = function ( id, is_status ) {
        scope.activateParams = '';
        scope.showRecords = false;
        scope.gridLoadingBar = true;
        var activateIdLength = id.length;

        if ( is_status == 1 ) {
            scope.deleteRequest = requestFactory.post( requestFactory.getUrl( 'videos/bulk-update-status' ), angular.extend( {}, {selectedCheckbox : id,isStatus : 'activate'}, scope.requestParams ), function ( data ) {
                this.responseMessage = data.message;
                this.showResponseMessage = true;
                this.selectedRecords = [];
                angular.element( '#selectall' ).removeAttr( 'checked' );
                if ( scope.records.length - activateIdLength > 0 ) {
                    scope.getRecords( true );
                } else {
                    scope.currentPage = ( scope.currentPage - 1 == 0 ) ? 1 : scope.currentPage - 1;
                    scope.getRecords( true );
                }
            } );
        } else if ( is_status == 0 ) {
            scope.deleteRequest = requestFactory.post( requestFactory.getUrl( 'videos/bulk-update-status' ), angular.extend( {}, {selectedCheckbox : id,isStatus : 'deactivate'}, scope.requestParams ), function ( data ) {
                this.responseMessage = data.message;
                this.showResponseMessage = true;
                this.selectedRecords = [];
                angular.element( '#selectall' ).removeAttr( 'checked' );
                if ( scope.records.length - activateIdLength > 0 ) {
                    scope.getRecords( true );
                } else {
                    scope.currentPage = ( scope.currentPage - 1 == 0 ) ? 1 : scope.currentPage - 1;
                    scope.getRecords( true );
                }
            } );
        }

    }

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'videos/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();

    window.VideoThumbnailUploadHandler = new uploadHandler;
    window.VideoThumbnailUploadHandler.initate( {file : 'thumb-image',previewer : 'thumb-preview',progress : 'thumb-progress',deleteIcon : 'thumb-delete',beforeUpload : function () {
        scope.errors = {};
        if ( !scope.$$phase ) {
            scope.$apply();
        }
    },afterUpload : function ( response ) {
        self.editVideo.thumbnail = response.info;
		self.editVideo.selected_thumb = response.info;
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

    this.addFullScreenEventListener = function () {
        var myPlayer = videojs( 'video_player' );
        myPlayer.on( 'fullscreenchange', function () {
            if ( myPlayer.isFullscreen() ) {
                // Change transition property to none to avoid layout shake while exit.
                document.getElementById( "menu-7" ).style.transitionProperty = "none";
                document.querySelector( ".st-pusher" ).style.transitionProperty = "none";
            } else {
                // Remove back the transition value none so that the video edit sidebar closes and opens smoothly.
                document.getElementById( "menu-7" ).style.removeProperty( 'transition' );
                document.querySelector( ".st-pusher" ).style.removeProperty( 'transition' );
            }
        } );
    };
    this.addFullScreenEventListener();

    /**
     *  Function is used to select the move collection Button
     *  
     *  @param $event, id
     * 
     */
    this.selectRecord = function ( $event, id ) {
        var isCheckboxSelected = false;
        var eventCheckbox = $event.target || $event.srcElement;

        if ( angular.isObject( eventCheckbox ) ) {
            if ( angular.element( eventCheckbox ).is( ':checked' ) ) {

                angular.element( '#move_collection' ).attr( "data-toggle", "modal" );
                angular.element( '#move_playlist' ).attr( "data-toggle", "modal" );

                if ( this.selectedRecords.indexOf( id ) == -1 ) {
                    this.selectedRecords.push( id );
                }
            } else if ( this.selectedRecords.indexOf( id ) > -1 ) {
                this.selectedRecords.splice( this.selectedRecords.indexOf( id ), 1 );
            }
        }

        if ( this.selectedRecords.length == 0 ) {
            angular.element( '#move_collection' ).removeAttr( 'data-toggle' );
            angular.element( '#move_playlist' ).removeAttr( 'data-toggle' );
        }
        this.checkMasterCheckbox();

    }
    /**
     * Function to check and uncheck master checkbox when all the checkboxes are checked or not.
     */
    this.checkMasterCheckbox = function () {
        var mainCheckbox = true;
        angular.element( '.checkbox' ).each( function () {
            if ( angular.element( this ).prop( 'checked' ) == false ) {
                mainCheckbox = false;
            }
        } );

        if ( mainCheckbox == false ) {
            // Uncheck the main checkbox
            angular.element( '#selectall' ).prop( 'checked', false );
        } else {
            // Check the main checkbox
            angular.element( '#selectall' ).prop( 'checked', true );
        }
    };
    /**
     * Function to select and unselect all checkboxes.
     */
    this.selectAllRecords = function () {
        if ( angular.element( '#selectall' ).prop( 'checked' ) ) {
            self.selectedRecords = [];
            angular.element( '.checkbox' ).each( function () {
                angular.element( this ).prop( 'checked', true );
                var id = Number( angular.element( this ).val() );
                self.selectedRecords.push( id );
            } );
            angular.element( '#move_collection' ).attr( "data-toggle", "modal" );
            angular.element( '#move_playlist' ).attr( "data-toggle", "modal" );
        } else {
            angular.element( '.checkbox' ).each( function () {
                angular.element( this ).prop( 'checked', false );
                var id = Number( angular.element( this ).val() );
                self.selectedRecords.splice( self.selectedRecords.indexOf( id ), 1 );
            } );
        }
        if ( this.selectedRecords.length == 0 ) {
            angular.element( '#move_collection' ).removeAttr( 'data-toggle' );
            angular.element( '#move_playlist' ).removeAttr( 'data-toggle' );
        }
    };

    /**
     *  Function is used to select the create collection
     *  
     *  @param string collection
     *  @return void
     */
    this.createCollection = function ( collection ) {
        if ( parseInt( collection ) === 0 ) {
            this.showcreateCollection = true;
        } else {
            scope.errors = {};
            this.showcreateCollection = false;
        }
    }

    /**
     *  Function is used to select the create playlist
     *  
     *  @param string playlist
     *  @return void
     */
    this.createPlaylist = function ( playlist ) {
        if ( parseInt( playlist ) === 0 ) {
            this.showcreatePlaylist = true;
        } else {
            scope.errors = {};
            this.showcreatePlaylist = false;
        }
    }
    /**
     *  Function is used to save the collection
     *  
     *  @param $event
     * 
     */
    this.save = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            this.collection.selectedVideos = this.selectedRecords;
            requestFactory.post( requestFactory.getUrl( 'collections/add' ), this.collection, function ( response ) {
                this.fetchInfo();
                angular.element( ".close" ).click();
                requestFactory.toggleLoader();
                angular.element( ".checkbox" ).attr( "checked", false );
                angular.element( '#selectall' ).prop( 'checked', false );
                this.selectedRecords = [];
                this.responseMessage = response.message;
                this.showResponseMessage = true;
            }, this.fillError );
        }
    }
    /**
     *  Function is used to save the collection
     *  
     *  @param $event
     * 
     */
    this.Playlistsave = function ( $event ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
            this.collection.selectedVideos = this.selectedRecords.join( ',' );
            this.collection.id = parseInt( $event.target.radio.value );
            if ( parseInt( this.collection.id ) === 0 ) {
                this.collection.name = $event.target.name.value;
            }
            requestFactory.post( requestFactory.getUrl( 'playlist/add' ), this.collection, function ( response ) {
                this.fetchInfo();
                angular.element( ".close" ).click();
                requestFactory.toggleLoader();
                angular.element( ".checkbox" ).attr( "checked", false );
                angular.element( '#selectall' ).prop( 'checked', false );
                this.selectedRecords = [];
                this.responseMessage = response.message;
                this.showResponseMessage = true;
            }, this.fillError );
        }
    }
    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function ( record ) {
        scope.routeName = 'videos';
        scope.updateStatus( record );
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
    this.addExamToVideos = function ( id, examName ) {
        self.editVideo.exam_ids.push( id );
        self.multipleExams.push( {id : id,title : examName} );
        self.examField = '';
        self.examSuggestions = [];
    };

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
    /*
     * Function to display presets of a video in bootstrap modal in the videos grid page.
     */
    this.showVideoPresetsInModal = function ( transcodedvideos ) {
        self.commonVideoPresets = [];
        transcodedvideos.forEach( function ( item, index ) {
            if ( angular.isObject( item.presets ) ) {
                self.commonVideoPresets.push( item.presets.name + ' - ' + item.presets.format );
            }
        } );
        jQuery( '#videoPresetsModal' ).modal( 'show' );
    };

    this.viewDetailsVideoCollection = function ( filters ) {
        scope.filters.collectionId = filters.collectionId;
        scope.filters.collectionName = filters.collectionName;
    }

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.is_active ) ) {
            scope.searchRecords.is_active = 'all';
        }
        scope.selectId=[];
        for (var i = 0, len = data.data.data.length; i < len; i++) {
          console.log(data.data.data[i]);
          if(data.data.data[i].liveStatus === 'starting'){
            scope.selectId.push(data.data.data[i]);
          }
        }
        angular.element( '#move_collection' ).removeAttr( 'data-toggle' );
        self.selectedRecords = [];
        angular.element( ".checkbox" ).attr( "checked", false );
        scope.getStatusLive();
    } );
}];

function googleDriveUploader () {
    var self = this;

    this.initializeFineUploader = function () {
        window.fineUploaderGoogleDrive = new qq.FineUploaderBasic( {autoUpload : false,debug : true,element : document.getElementById( 'file_drop_area' ),request : {endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader'},
            deleteFile : {enabled : true,endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader'},chunking : {enabled : true,concurrent : {enabled : true},success : {endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader?done'}},
            resume : {enabled : true},retry : {enableAuto : false},callbacks : {onComplete : function ( id, name, response, xhr ) {
                if ( response.success == true ) {
                    var uploadResponse = {};
                    uploadResponse.name = name;
                    uploadResponse.uuid = response.uuid;
                    self.uploadedVideosDetails.push( uploadResponse );
                    self.options.afterUpload( uploadResponse );
                }
            },onProgress : function ( id, name, uploadedBytes, totalBytes ) {
                var uploadedPercentage = parseInt( ( uploadedBytes * 100 ) / totalBytes );
                document.getElementById( 'progress-bar-wrap' ).style.display = 'block';
                var progressBar = document.getElementById( 'progress-bar' );
                progressBar.style.width = uploadedPercentage + '%';
                document.getElementById( "upload_percentage" ).innerHTML = uploadedPercentage + '% Uploaded';				
                if ( uploadedPercentage == 100 ) {
                    document.getElementById( "upload_percentage" ).innerHTML = 'Processing...';
                }
            },onError : function ( id, name, errorReason, xhr ) {
                document.getElementById( "upload_errors_wrap" ).style.display = "block";
                document.getElementById( "upload_title" ).style.display = "none";
                self.resetUploader();
                self.initializeFineUploader();
                // Display alert message for the videos which are uploaded successfully before this error.
                var uploadedVideosCount = self.uploadedVideosDetails.length;
                if ( uploadedVideosCount > 0 ) {
                    var videoListString = '';
                    var videoText = '';
                    if ( uploadedVideosCount == 1 ) {
                        videoText = 'video was';
                    } else {
                        videoText = 'videos were';
                    }

                    for ( var i = 0; i < uploadedVideosCount; i++ ) {
                        videoListString = videoListString + self.uploadedVideosDetails [i].name;
                        if ( i + 1 != uploadedVideosCount ) {
                            videoListString = videoListString + ", ";
                        }
                    }
                    document.getElementById( "upload_staus_when_error" ).innerHTML = "But " + uploadedVideosCount + " " + videoText + " uploaded successfully(" + videoListString + ").";
                } else {
                    document.getElementById( "upload_staus_when_error" ).innerHTML = '';
                }
            },onUpload : function ( id, name ) {
                self.currentFileCount++;
                document.getElementById( "upload_title" ).innerHTML = "Uploading File(" + self.currentFileCount + " of " + self.uploadFiles.length + ") : " + name;
            },},} );
    };

    // Use the API Loader script to load google.picker and gapi.auth.
    this.onApiLoad = function () {
        gapi.load( 'auth', {'callback' : self.onAuthApiLoad} );
        gapi.load( 'picker', {'callback' : self.onPickerApiLoad} );
    }

    this.onAuthApiLoad = function () {
        window.gapi.auth.authorize( {'client_id' : self.clientId,'scope' : self.googleScope,'immediate' : false}, self.handleAuthResult );
    }

    this.onPickerApiLoad = function () {
        self.pickerApiLoaded = true;
        self.createPicker();
    }

    this.handleAuthResult = function ( authResult ) {
        if ( authResult && !authResult.error ) {
            self.oauthToken = authResult.access_token;
            self.createPicker();
        }
    }

    // Create and render a Picker object for picking user Photos.
    this.createPicker = function () {
        if ( self.pickerApiLoaded && self.oauthToken ) {
            var view = new google.picker.DocsView( google.picker.ViewId.DOCS_VIDEOS ).setMimeTypes( "video/mp4,video/quicktime,video/avi,video/x-ms-wmv,video/msvideo,video/x-msvideo" );
            var picker = new google.picker.PickerBuilder().enableFeature( google.picker.Feature.MULTISELECT_ENABLED ).addView( view ).setOAuthToken( self.oauthToken ).setDeveloperKey( self.developerKey ).setCallback( self.pickerCallback ).build();
            picker.setVisible( true );
        }
    }

    // A simple callback implementation.
    this.pickerCallback = function ( data ) {
        var url = 'nothing';
        if ( data [google.picker.Response.ACTION] == google.picker.Action.PICKED ) {
            var doc = data [google.picker.Response.DOCUMENTS] [0];
            self.getDownloadurl( doc );
        }
    }

    this.getAsBlob = function ( file ) {
        var blob = null;
        var blobWrapper;
        var xhr = new XMLHttpRequest();
        xhr.open( "GET", file.downloadUrl );
        xhr.setRequestHeader( "Authorization", "Bearer " + gapi.auth.getToken().access_token );
        xhr.responseType = "blob";
        xhr.onload = function () {
            self.uploadFiles = [];
            self.uploadFiles.push( file );

            // Prepare Upload
            document.getElementById( "upload_title" ).innerHTML = "No. of selected files : " + self.uploadFiles.length;
            document.getElementById( "video_error" ).style.display = "none";
            document.getElementById( "upload_title" ).style.display = "block";
            document.getElementById( "upload_errors_wrap" ).style.display = "none";
            document.getElementById( "upload_percentage" ).innerHTML = '0% Uploaded';
            document.getElementById( "google_drive_upload_button" ).style.display = "none";

            blob = xhr.response;// xhr.response is now a blob object
            blobWrapper = {blob : blob,name : file.title,};

            var files = [];
            files.push( blobWrapper );
            self.currentFileCount = 0;
            self.uploadedVideosDetails = [];

            // Hide add video close button, File selection container div and video upload button
            document.querySelector( '.add_video_container .fa-times' ).style.display = "none";
            document.getElementsByClassName( "upload_file_input" ) [0].style.display = "none";
            document.getElementById( "video_upload_button_wrap" ).style.display = "none";

            document.getElementById( "upload_percentage" ).style.display = "block";
            self.options.beforeUpload( self.uploadFiles.length );

            window.fineUploaderGoogleDrive.addFiles( files );
            window.fineUploaderGoogleDrive.uploadStoredFiles();
        }
        xhr.send();
    };

    // Before executing following client request you must include
    this.getDownloadurl = function ( file ) {
        var request = gapi.client.request( {'path' : '/drive/v2/files/' + file.id,'params' : {'maxResults' : '1000'},callback : function ( responsejs, responsetxt ) {
            self.getAsBlob( responsejs );
        }} );
    };

    this.resetUploader = function () {
        document.getElementById( "video_error" ).style.display = "none";
        document.querySelector( '.add_video_container .fa-times' ).style.display = "inline";
        document.getElementsByClassName( "upload_file_input" ) [0].style.display = "block";
        document.getElementById( "upload_percentage" ).style.display = "none";
        document.getElementById( 'progress-bar-wrap' ).style.display = 'none';
        document.getElementById( 'progress-bar' ).style.width = '0%';
        document.getElementById( "google_drive_upload_button" ).style.display = "inline";
    };

    this.initiate = function ( options ) {
        this.options = options;

        // The Browser API key obtained from the Google Developers Console.
        this.developerKey = window.VPlay.developerKey;

        // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
        this.clientId = window.VPlay.clientId;

        // Scope to use to access user's photos.
        this.googleScope = ['https://www.googleapis.com/auth/drive.readonly'];

        this.pickerApiLoaded = false;
        this.oauthToken;

        document.getElementById( options.id ).addEventListener( 'click', this.onApiLoad );
        this.initializeFineUploader();
    };
}

function videoUploader (scope) {
    var self = this;


    this.initializeFineUploader = function () {
        window.fineUploader = new qq.FineUploaderBasic( {autoUpload : false,debug : true,element : document.getElementById( 'file_drop_area' ),request : {endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader'},
            deleteFile : {enabled : true,endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader'},chunking : {enabled : true,concurrent : {enabled : true},success : {endpoint : window.VPlay.route.siteUrl + '/api/admin/videos/handle-fine-uploader?done'}},
            resume : {enabled : true},retry : {enableAuto : false},button : document.getElementById( 'select-files-button' ),callbacks : {onComplete : function ( id, name, response, xhr ) {
                if ( response.success == true ) {
                    var uploadResponse = {};
                    uploadResponse.name = name;
                    uploadResponse.uuid = response.uuid;
                    self.uploadedVideosDetails.push( uploadResponse );
                    self.options.afterUpload( uploadResponse );
                }
            },onProgress : function ( id, name, uploadedBytes, totalBytes ) {
				document.getElementById("video_frame").style.display = 'none';
				$('#published_on'+self.currentFileCount).datepicker({format:"dd-mm-yyyy",viewMode: 'years',autoclose: true});
                var uploadedPercentage = parseInt( ( uploadedBytes * 100 ) / totalBytes );
                document.getElementById( 'progress-bar-wrap'+self.currentFileCount ).style.display = 'block';
                var progressBar = document.getElementById( 'progress-bar'+self.currentFileCount );
                progressBar.style.width = uploadedPercentage + '%';
                document.getElementById( "upload_percentage"+self.currentFileCount ).innerHTML = uploadedPercentage + '% Uploaded';
				document.getElementById( "upload_title"+self.currentFileCount ).innerHTML = name;
                if ( uploadedPercentage == 100 ) {
                    document.getElementById( "upload_percentage"+self.currentFileCount).innerHTML = 'Done...';
                }
            },onError : function ( id, name, errorReason, xhr ) {
				//document.getElementById("video_frame").style.display = 'block';
                document.getElementById( "upload_errors_wrap" ).style.display = "block";
                document.getElementById( "upload_title" ).style.display = "none";
                self.resetUploader();
                self.initializeFineUploader();
                // Display alert message for the videos which are uploaded successfully before this error.
                var uploadedVideosCount = self.uploadedVideosDetails.length;
                if ( uploadedVideosCount > 0 ) {
                    var videoListString = '';
                    var videoText = '';
                    if ( uploadedVideosCount == 1 ) {
                        videoText = 'video was';
                    } else {
                        videoText = 'videos were';
                    }

                    for ( var i = 0; i < uploadedVideosCount; i++ ) {
                        videoListString = videoListString + self.uploadedVideosDetails [i].name;
                        if ( i + 1 != uploadedVideosCount ) {
                            videoListString = videoListString + ", ";
                        }
                    }
                    document.getElementById( "upload_staus_when_error" ).innerHTML = "But " + uploadedVideosCount + " " + videoText + " uploaded successfully(" + videoListString + ").";
                } else {
                    document.getElementById( "upload_staus_when_error" ).innerHTML = '';
                }
            },onUpload : function ( id, name ) {
                self.currentFileCount++;
                //document.getElementById( "upload_title" ).innerHTML = "Uploading File(" + self.currentFileCount + " of " + self.uploadFiles.length + ") : " + name;
                document.getElementById('dynamic_content'+self.currentFileCount).style.display = 'block';



            },},} );
    };

    this.isFileValid = function ( validFileTypes, fileType ) {
        if ( validFileTypes.indexOf( fileType ) != -1 ) {
            return true;
        } else {
            return false;
        }
    };

    this.fileDragOver = function ( event ) {
        // When the file is dragged over the drop area.
        this.style.boxShadow = "0px 0px 50px 10px rgba(0,0,0,0.75)";
        event.preventDefault();
        event.stopPropagation();
    };

    this.fileDragLeave = function ( event ) {
        // When the file is dragged out of the drop area.
        this.style.boxShadow = "none";
        event.preventDefault();
        event.stopPropagation();
    };

    this.handleFileDrop = function ( event ) {
        // When the file is dropped in the drop area.
        this.style.boxShadow = "none";
        var files = event.dataTransfer.files;
        self.prepareUpload( files );
        event.preventDefault();
        event.stopPropagation();
    };

    this.handleFileSelect = function () {
        // When the file is selected using file select.
        var files = this.files;
        self.prepareUpload( files );
    };
    this.resetUploader = function () {
        document.getElementById( "video_error" ).style.display = "none";
        document.querySelector( '.add_video_container .fa-times' ).style.display = "inline";
        document.getElementsByClassName( "upload_file_input" ) [0].style.display = "block";
        document.getElementById( "upload_percentage" ).style.display = "none";
        document.getElementById( "google_drive_upload_button" ).style.display = "inline";

        // Add back the drop event listener to the drop area.
        var fileDropArea = document.querySelector( '#' + self.options.dropAreaId );
        fileDropArea.addEventListener( 'drop', self.handleFileDrop );
        document.getElementById( 'progress-bar-wrap' ).style.display = 'none';
        document.getElementById( 'progress-bar' ).style.width = '0%';
        // Reset the input tag so that the same file can be selected again for upload.
        this.file.value = '';
    };

    this.startVideoUpload = function () {
        var files = self.uploadFiles;
        self.currentFileCount = 0;
        self.uploadedVideosDetails = [];

        // Hide add video close button, File selection container div and video upload button
        document.querySelector( '.add_video_container .fa-times' ).style.display = "none";
        document.getElementsByClassName( "upload_file_input" ) [0].style.display = "none";
        document.getElementById( "video_upload_button_wrap" ).style.display = "none";

        // Remove Drop event listener for file drop area.
        var fileDropArea = document.querySelector( '#' + self.options.dropAreaId );
        fileDropArea.removeEventListener( 'drop', self.handleFileDrop );

        document.getElementById( "upload_percentage" ).style.display = "block";
        self.options.beforeUpload( self.uploadFiles.length );

        window.fineUploader.addFiles( files );
        window.fineUploader.uploadStoredFiles();
    };

    this.prepareUpload = function ( files ) {
        self.uploadFiles = [];
        var validFileTypes = ['video/mp4','video/quicktime','video/avi','video/x-ms-wmv','video/msvideo','video/x-msvideo'];

        document.getElementById( "upload_title" ).innerHTML = "No. of selected files : " + files.length;
        document.getElementById( "video_error" ).style.display = "none";
        document.getElementById( "upload_title" ).style.display = "block";
        document.getElementById( "upload_errors_wrap" ).style.display = "none";
        document.getElementById( "upload_percentage" ).innerHTML = '0% Uploaded';
        document.getElementById( "google_drive_upload_button" ).style.display = "none";

        for ( var i = 0; i < files.length; i++ ) {
            if ( files [i] && self.isFileValid( validFileTypes, files [i].type ) ) {
                self.uploadFiles.push( files [i] );
            }
        }
        var uploadButton = document.getElementById( "video_upload_button_wrap" );
        if ( self.uploadFiles.length == 0 ) {
            // There are no valid files selected.
            document.getElementById( "video_error" ).style.display = "block";
            uploadButton.style.display = "none";
        } else {
            // Enable upload button
            uploadButton.addEventListener( 'click', this.startVideoUpload );
            uploadButton.style.display = "block";
        }
    };

    this.initiate = function ( options ) {
        this.options = options;
        this.file = document.getElementById( options.id );
        this.file.addEventListener( 'change', this.handleFileSelect );
        var fileDropArea = document.querySelector( '#' + options.dropAreaId );
        fileDropArea.addEventListener( 'dragover', this.fileDragOver );
        fileDropArea.addEventListener( 'dragleave', this.fileDragLeave );
        fileDropArea.addEventListener( 'drop', this.handleFileDrop );
        this.initializeFineUploader();
    };
}
window.gridInitApp = angular.module('grid',['flow']);
window.gridInitApp.directive( 'selectTwo', function () {
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
window.gridControllers = {VideoGridController : VideoGridController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};
