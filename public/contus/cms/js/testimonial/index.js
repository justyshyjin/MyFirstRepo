'use strict';
var testimonialController = ['$scope','flowFactory','requestFactory','$window','$sce','$timeout',function(scope,flowFactory,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.testimonial = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  scope.existingFlowObject =  flowFactory.create ({
      target: document.querySelector('meta[name="base-api-url"]').getAttribute('content')+'/testimonial/testimonial-image',
      permanentErrors: [404, 500, 501],
      testChunks:false,
      maxChunkRetries: 1,
      chunkRetryInterval: 5000,
      simultaneousUploads: 4,
      singleFile: true
    });
scope.existingFlowObject.on('fileSuccess', function (event,message) {
    if(message){ 
        self.testimonial.image = message;
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
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
  
  /**
   *  Function is used to add the Testimonial news
   *  @param $event
   */ 
  this.addStaticContent = function ($event){

      scope.existingFlowObject.cancel();
    scope.errors = {};
    this.testimonial={};
    this.testimonial.is_active = String(0);
    this.testimonial.image = '';
  }
  
  /**
   *  Function is used to edit the Testimonial
   *  
   *  @param records
   */ 
  this.editStaticContent = function (records) {
      scope.existingFlowObject.cancel();
    scope.errors = {};
    this.testimonial.id = records.id;
    this.testimonial.name = records.name;
    this.testimonial.image = records.image;
    this.testimonial.description = records.description;
    this.testimonial.is_active = String(records.is_active);
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
   *  Function is used to save the Testimonial
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('testimonial/edit/'+id),this.testimonial,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
          $timeout(function(){
            self.testimonial = {};
          },100);
        },this.fillError);
        
      } else {
        requestFactory.post(requestFactory.getUrl('testimonial/add'),this.testimonial,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeStaticContentEdit();
        },this.fillError);
      }
    }
  }
  
  
  
  /**
   * Function to close the sidebar which is used to edit Testimonial information.
   */
  this.closeStaticContentEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };
  
  this.defineProperties = function(data) {
      this.info = data.info;
      baseValidator.setRules({
          name : "required",
          description : "required",
      });
  };
  
  this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('testimonial/info'),this.defineProperties,function(){});
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

window.gridControllers = {testimonialController : testimonialController};
window.gridInitApp = angular.module('grid',['flow']);
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});