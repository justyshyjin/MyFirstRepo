'use strict';

var login = angular.module('mara.merchantheader',['mara.request']);

login.directive('maraValidator',validatorDirective);

login.controller('MerchantHeaderController',['$window','$scope','$controller','$rootScope',function($window,scope,controller,$rootScope){
  var self = this;
  scope.errors = {};
  $rootScope.showForgetForm = false;
  this.errormessage = null;
  this.request = controller('RequestController').setThisArgument(this);

  this.defineProperties = function(data){
    this.data = data;
    maraValidator.setRules({'email' : 'required|email', 'password' : 'required' });
    this.login = {};
  }
  this.defineProperties();
  this.fillError = function(response){  
	if(response.status == 403 && response.data.hasOwnProperty('message')){
		if(response.data.message.length > 0){
			$('#merchant_login_email').addClass('not-register');
			scope.errors.merchantlogin.email = {has : true , message : response.data.message};
        }
	}
    if(response.status == 422 && response.data.hasOwnProperty('messages')){
      angular.forEach(response.data.messages, function(message,key) {
        if(typeof message == 'object' && message.length > 0){
          scope.errors[key] = {has : true , message : message[0]};
        }
      });
    }
  };
  
  this.save = function($event){ 
    var isValidateOption = true;
    if(maraValidator.validateAngularForm($event.target,scope,'merchantlogin')){
    	this.errormessage = '';
		this.request.post(this.request.getUrl('login'),this.login,function(response){
			$window.location = "";
	    },this.fillError);
    }  
  };  
}]);

login.controller('ForgotPasswordController',['$window','$scope','$controller','$element',function($window,scope,controller,$element){
	  var self = this;
	  scope.errors = {};
	  this.errormessage = null;
	  this.request = controller('RequestController').setThisArgument(this);

	  this.defineProperties = function(data){
	    this.data = data;
	    maraValidator.setRules({'email' : 'required|email', 'password' : 'required' });
	    this.forgotpassword = {};
	  }
	  this.defineProperties();
	  this.fillError = function(response){  
		if(response.status == 403 && response.data.hasOwnProperty('message')){
			if(response.data.message.length > 0){
				$('#merchant_forgopassword_email').addClass('not-register');
				scope.errors.merchantforgotpassword.email = {has : true , message : response.data.message};
	        }
		}
	    if(response.status == 422 && response.data.hasOwnProperty('messages')){
	      angular.forEach(response.data.messages, function(message,key) {
	        if(typeof message == 'object' && message.length > 0){
	          scope.errors[key] = {has : true , message : message[0]};
	        }
	      });
	    }
	  };
	  
	  this.save = function($event){ 
	    var isValidateOption = true;
	    if(maraValidator.validateAngularForm($event.target,scope,'merchantforgotpassword')){
	    	this.errormessage = '';
			this.request.post(this.request.getUrl('merchantuser/forgotpassword'),this.forgotpassword,function(response){
				//$window.location = "";
				$('#forgot_password_block').addClass('success');
				this.errormessage = response.message;
				setTimeout(function(){
					$window.location = "";
				}, 3000);
		    },this.fillError);
	    }  
	  };  
	}]);