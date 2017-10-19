'use strict';

var UserGroupController = ['$scope','requestFactory','$window','$sce','$timeout',function(scope,requestFactory,$window,$sce,$timeout){
  var self = this;
  this.user = {};
  this.showResponseMessage = false;
  requestFactory.setThisArgument(this);
  this.uniqueRoute = requestFactory.getUrl('users/unique');
  /**
   *  To get the user rules
   *  
   */ 
  this.getUserRules = function() {
    requestFactory.get(requestFactory.getUrl('users/info'),function(response){
      baseValidator.setRules(response.rules);
    });
  }
  /**
   *  To get the auth id
   *  
   */ 
  this.setQuery = function($authId) {
    this.authId = $authId;
  }
  /**
   *  Function is used to add the user
   *  @param $event
   */ 
  this.addUser = function ($event){
    $timeout(function(){
      $("#tree").checktree();
    },500);
    scope.errors = {};
    this.getUserRules();
    this.user={};
    this.user.is_active = String(0);
    this.uniqueRoute = requestFactory.getUrl('users/unique');
  }
   /**
   *  Function is used to edit the user
   *  
   *  @param records
   */ 
  this.editUser = function (records) {
    scope.errors = {};
    this.getUserRules();
    this.user.id = records.id;
    this.user.name = records.name;
    this.user.email = records.email;
    this.user.phone = records.phone;
    this.user.is_active = String(records.is_active);
    this.user.gender = records.gender;
    this.uniqueRoute = requestFactory.getUrl('users/unique/'+records.id);
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

   /**
   *  Function is used to save the user
   *  
   *  @param $event,id
   */
  this.save = function ($event,id) {debugger;
    if (baseValidator.validateAngularForm($event.target,scope)) {
      if (id) { 
        requestFactory.post(requestFactory.getUrl('users/edit/'+id),this.user,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeUserEdit();
          $timeout(function(){
            self.user = {};
          },100);
        });
        
      } else {
        requestFactory.post(requestFactory.getUrl('users/add'),this.user,function(response){
          scope.getRecords(true);
          this.responseMessage = response.message;
          this.showResponseMessage = true;
          this.closeUserEdit();
        },this.fillError);
      }
    }
  }
  /**
   * Function to close the sidebar which is used to edit user information.
   */
  this.closeUserEdit = function() {
      var container = document.getElementById( 'st-container' )
      classie.remove( container, 'st-menu-open' );
  };


  this.checktree =  function(){debugger;
            $(this)
                .addClass('checktree-root')
                .on('change', 'input[type="checkbox"]', function(e){
                    e.stopPropagation();
                    e.preventDefault();

                    checkParents($(this));
                    checkChildren($(this));
                })
            ;
           
            
            

            var checkParents = function (c)
            {
                var parentLi = c.parents('ul:eq(0)').parents('li:eq(0)');

                if (parentLi.length)
                {
                    var siblingsChecked = parseInt($('input[type="checkbox"]:checked', c.parents('ul:eq(0)')).length),
                        rootCheckbox = parentLi.find('input[type="checkbox"]:eq(0)')
                    ;

                    if (c.is(':checked'))
                        rootCheckbox.prop('checked', true)
                    else if (siblingsChecked === 0)
                        rootCheckbox.prop('checked', false);

                    checkParents(rootCheckbox);
                }
            }

            var checkChildren = function (c)
            {
                var childLi = $('ul li input[type="checkbox"]', c.parents('li:eq(0)'));

                if (childLi.length)
                    childLi.prop('checked', c.is(':checked'));
            }
            
            $(this).find('input[type="checkbox"]:checked').each(function(){
                checkParents($(this));
                checkChildren($(this));
            });
        }

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

window.gridControllers = {UserGroupController : UserGroupController};
window.gridDirectives  = {
  baseValidator    : validatorDirective,
  intializeSidebar : intializeSidebar
};

$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
});