'use strict';

var bannerController = ['$scope','flowFactory','requestFactory','$window','$sce','$timeout',function(scope,flowFactory,requestFactory,$window,$sce,$timeout){
  
    var self = this;
  this.banner = {};
  this.banner_image='';
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  
  scope.existingFlowObject =  flowFactory.create ({
        target: document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/banner/banner-image',
        permanentErrors: [404, 500, 501],
        testChunks:false,
        maxChunkRetries: 1,
        chunkRetryInterval: 5000,
        simultaneousUploads: 4,
        singleFile: true
      });
scope.existingFlowObject.on('fileSuccess', function (event,message) {
    if(message){ 
      self.banner.banner_image = message;
      angular.element( '.loaders' ).hide();
                 angular.element( '.submitbutton' ).attr('disabled', false)
        }
      });
scope.existingFlowObject.on('fileAdded', function (file){
          if (file.size > 2097152){              
                  return false;
          }
          angular.element( '.loaders' ).show();                  
          angular.element( '.submitbutton' ).attr('disabled', true)
          });

scope.clearbannerImage = function(){
    self.banner.banner_image = "";
}
  
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
    this.banner={};
    this.banner.is_active = String(0);
    this.banner.banner_image = '';
  }
  
  /**
   *  Function is used to edit the latestnews
   *  
   *  @param records
   */ 
  this.editStaticContent = function (records) {
    scope.errors = {};
    this.banner.id = records.id;
    this.banner.title = records.title;
    this.banner.type = records.type;
    this.banner.extension = records.extension;
    this.banner.imageUrl = records.url;
    this.banner.category = records.category_title;
    this.banner.url = records.url;
    this.banner.is_active = String(records.is_active);
    this.banner.image = records.image;
    this.banner.banner_image = records.banner_image;
  }

  this.fillError = function(response){
   if(response.status == 422 && response.data.hasOwnProperty('message')){
      angular.forEach(response.data.message, function(message,key) {
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
        requestFactory.post(requestFactory.getUrl('banner/edit/'+id),this.banner,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
          $timeout(function(){
            self.latestnews = {};
          },100);
        });
        
      } else {
          
        requestFactory.post(requestFactory.getUrl('banner/add'),this.banner,function(response){
            
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
        },this.fillError);
      }
    }
  }
  
  this.resetUploadArea = function(){
	  document.getElementById( "video_error" ).style.display = "none";        
      document.getElementsByClassName( "upload_file_input" ) [0].style.display = "block";
      document.getElementById( "upload_percentage" ).style.display = "none";
      document.getElementById( 'upload_title' ).style.display = 'none';
      document.getElementById( 'video_upload_button_wrap' ).style.display = 'none';      
      document.getElementById( 'progress-bar' ).style.width = '0%';
      document.getElementById( 'progress-bar-wrap' ).style.display = 'none';
	  
  }
  
  /**
   * Function to close the sidebar which is used to edit latestnews information.
   */
  this.closeStaticContentEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
     // this.initializeUploader();
      this.resetUploadArea();
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      this.initializeUploader();
     // baseValidator.setRules(data.info.rules);
  };
  
  this.fetchInfo = function() {
      
      requestFactory.get(requestFactory.getUrl('banner/info'),this.defineProperties,function(){});
  };

  this.fetchInfo();
  
this.initializeUploader = function(){
this.videoUploader = new videoUploader();
this.videoUploader.initiate( {id : 'video',dropAreaId : 'fine-uploader-gallery',afterUpload : function ( response ) {
},beforeUpload : function ( totalVideosCount ) {
    self.totalVideosCount = totalVideosCount;
    // Reset the values because this upload might be after failure.
    self.videoUploadCompleteCount = 0;
    self.uploadIntervalFlag = false;
    self.videoUploadRequestCount = 0;
},} );
	
}
function videoUploader () {
    var self = this;
    
    this.initializeFineUploader = function () {
        window.fineUploader = new qq.FineUploaderBasic( {autoUpload : false,debug : true,element : document.getElementById( 'file_drop_area' ),request : {endpoint : document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/videos/uplaod-banner-video'},
            deleteFile : {enabled : true,endpoint : document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/videos/uplaod-banner-video'},chunking : {enabled : true,concurrent : {enabled : false},success : {endpoint :  document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/videos/uplaod-banner-video?done'}},
            resume : {enabled : true},retry : {enableAuto : false},button : document.getElementById( 'select-files-button' ),callbacks : {onComplete : function ( id, name, response, xhr ) {
                if ( response.success == true ) {
                    var uploadResponse = {};
                    angular.element( '.loaders' ).hide();
                    angular.element( '.submitbutton' ).attr('disabled', false);
                    document.getElementById( "upload_percentage" ).innerHTML = 'Uploaded';
                    uploadResponse.name = name;
                    uploadResponse.uuid = response.uuid;
                    scope.bannerCtrl.banner.banner_image = response.uploadName;
                    self.uploadedVideosDetails.push( uploadResponse );
                    self.options.afterUpload( uploadResponse );
                    self.banner.banner_image = response.uploadName;
                }
            },onProgress : function ( id, name, uploadedBytes, totalBytes ) {
                angular.element( '.loaders' ).show();                  
                angular.element( '.submitbutton' ).attr('disabled', true)
                var uploadedPercentage = parseInt( ( uploadedBytes * 100 ) / totalBytes );
                document.getElementById( 'progress-bar-wrap' ).style.display = 'block';
                var progressBar = document.getElementById( 'progress-bar' );
                progressBar.style.width = uploadedPercentage + '%';
                document.getElementById( "upload_percentage" ).innerHTML = uploadedPercentage + '% Uploaded';
                if ( uploadedPercentage == 100 ) {
                    document.getElementById( "upload_percentage" ).innerHTML = 'done';
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
                document.getElementById( "upload_title" ).innerHTML = self.currentFileCount + name;
            },},} );
    };

    this.isFileValid = function ( validFileTypes, fileType ) {
        if ( validFileTypes.indexOf( fileType ) != -1 ) {
            return true;
        } else {
            return false;
        }
    };

    this.handleFileSelect = function () {
        // When the file is selected using file select.
        var files = this.files;
        self.prepareUpload( files );
    };
    this.resetUploader = function () { 
        document.getElementById( "video_error" ).style.display = "none";        
        document.getElementsByClassName( "upload_file_input" ) [0].style.display = "block";
        document.getElementById( "upload_percentage" ).style.display = "none";
        document.getElementById( "google_drive_upload_button" ).style.display = "inline";

        // Add back the drop event listener to the drop area.
        var fileDropArea = document.querySelector( '#' + self.options.dropAreaId );
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
        var validFileTypes = ['video/mp4'];

        document.getElementById( "video_error" ).style.display = "none";
        document.getElementById( "upload_title" ).style.display = "block";
        document.getElementById( "upload_errors_wrap" ).style.display = "none";
        document.getElementById( "upload_percentage" ).innerHTML = '0% Uploaded';

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
        this.file = document.getElementById( options.id );console.log(document.getElementById( options.id ));
        this.file.addEventListener( 'change', this.handleFileSelect );
        this.initializeFineUploader();
    };
}

  /**
   *  Listen to the records to update property
   *  
   */ 
  scope.$on('afterGetRecords',function(e,data){ 
    if(angular.isUndefined(scope.searchRecords.is_active)){
        scope.searchRecords.is_active = 'all';
    }
    if(scope.records[0].type && scope.records[0].banner_image )
        scope.records[0].banner_image = $sce.trustAsResourceUrl( scope.records[0].banner_image);
  });
}];
window.gridInitApp = angular.module('grid',['flow']);
window.gridControllers = {bannerController : bannerController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});