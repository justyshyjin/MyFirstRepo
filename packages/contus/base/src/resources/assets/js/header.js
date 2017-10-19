'use strict';

var header = angular.module('mara.header',['mara.request']);
var PHONE_REGEXP = /^\d{10}$/;
var EMAIL_REGEXP = /^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
var STRING_REGEXP = /[a-z|A-Z]/;

header.factory('addToWishlist',['$controller','$q',function(controller,$q){
	return function(merchant_product_id,event) {
		event.preventDefault();
		var request = controller('RequestController');
		if(typeof merchant_product_id == 'undefined' || typeof merchant_product_id !== 'number') {
    		throw new TypeError("merchant_product_id must be number");
    	}
		var deferred = $q.defer();
    	request.post(request.getUrl('wishlist/register'),{merchant_product_id : merchant_product_id},function(result){
    		 deferred.resolve(result);
    	},function(result){
    		deferred.reject(result);
    	});
    	return deferred.promise;
	};
}]);

header.factory('cartCount',['$rootScope','$controller',function(rootscope,controller){
	return function() {
		var request = controller('RequestController');
	};
	
}]);

header.controller('headerController',[
	'$scope',
	'$http',
	'$controller',
	'headersFactory',
	'$q',
	'cartCount',
	'$rootScope',
	'$filter',
	'$timeout',
	'$window',
	'$sce',
	'$document'
,function($scope,$http,controller,headersFactory,$q,cartCount,$root,$filter,$timeout,$window,strictContextual,$document){
	if(sessionStorage.getItem('news-letter') == null ) {
		sessionStorage.setItem('news-letter','1');
		$("#newpopupBox").show();		
		$('#newpopupBox #close').click(function(){
			setTimeout(function(){ $('.login_dropdown_menu').show();setTimeout(function(){ $('.login_dropdown_menu').hide(); }, 2000); }, 1000);
		});
	}
	else {
		$("#newpopupBox").hide();
		setTimeout(function(){ $('.login_dropdown_menu').show();setTimeout(function(){ $('.login_dropdown_menu').hide(); }, 2000); }, 3000);
		
	}
	$root.hidePreLoader = true;
	var self = this;
	self.request = controller('RequestController').setThisArgument(this);
	angular.element('.loginLink').click();
	$scope.login_name_error = '';
	$scope.emailorphoneResponse = false;
    $scope.signUpBox = true;
	$scope.showOtpForm = false;
	$scope.signUpForms = true;
	this.showSearchContainer = false;
	this.searchResults = {};
	this.searchElementIds = ['header-search','do-search','search-container'];
	this.searchCategory = 'all';
	self.cartItemCount,self.totalPrice,self.cartNotificationBoxProductName;
	self.cartNotificationBoxProductImage = "#";
	self.cartDetails = {};
	$scope.getRecords = function(path,record) {
		  var deferred = $q.defer();
		  $http.post(headersFactory.getBaseTemplateUrl() +'/user/'+path,record).then(function(result) {
			  if(result.data.error == false ) {
				  deferred.resolve(result); 
			  }
			  else {
				  deferred.reject(result);
			  }
	 },function(errorData){
		 deferred.reject(errorData.data);
	 } );
		  return deferred.promise;
	  }
	self.buyNow = function(identifier){
		this.request.post(this.request.getUrl('cart/add'),{'merchant_product_id' : identifier,'quantity' : 1},function(data){
			$window.location.href = this.request.getTemplateUrl('shopping-cart');
		},function(){});
	};
	/**
	 * do catalog search
	 * and load the search result for suggestions
	 * 
	 * @param object $event
	 * @param string term
	 * @return void
	 */
	this.doCatalogSearch = function($event,term){
		var searchTerm = (angular.isString(term)) ? term : this.searchTerm;
		
		if((($event.keyCode == 13 && $event.type == 'keypress') || $event.type == 'click') && angular.isString(searchTerm)){
			$window.location.href = this.request.getTemplateUrl('search',{term : encodeURIComponent(searchTerm)});
		}
	};
	/**
	 * do catalog search by category
	 * and load the search result for suggestions
	 * 
	 * @param object category
	 * @param string term
	 * @return void
	 */
	this.doCatalogSearchByCategory = function(category,term){
		if(angular.isObject(category) && angular.isString(category.slug) && angular.isString(term)){
			$window.location.href = this.request.getTemplateUrl('products/'+category.slug,{term : encodeURIComponent(term)});
		}
	};	
	/**
	 * do search
	 * and load the search result for suggestions
	 * 
	 * @return void
	 */
	this.doSearch = function(){
		if(angular.isString(this.searchTerm)){
			this.request.get(this.request.getUrl('headersearch',{term : this.searchTerm,'cat' : this.searchCategory}),function(data){
				if(
					angular.isObject(data) 
					&& angular.isObject(data.response) 
					&& angular.isArray(data.response.result) 
					&& angular.isArray(data.response.suggestions)
				){
					/**
					 * since the result will always have single out
					 * and  since category iterated property is formmated
					 */
					data.response.result = data.response.result.length > 0 ? data.response.result[0] : data.response.result;
					this.searchResults = data.response;
					this.showSearchContainer = true;
				} else {
					this.searchResults = {};
					this.showSearchContainer = false;
				}
			},function(){
				this.searchResults = {};
				this.showSearchContainer = false;
			});
		}
	};
	/**
	 * get matched content from searchresult
	 * 
	 * @param object searchResult
	 * @return object
	 */
	this.setSearchCategory = function(searchCategory){
		this.showSearchContainer = false;
		this.searchCategory = searchCategory;
	};	
	/**
	 * get matched content from searchresult
	 * 
	 * @param object searchResult
	 * @return object
	 */
	this.getMatchedContent = function(searchResult){
		if(angular.isObject(searchResult) && angular.isObject(searchResult.highlight) && angular.isArray(searchResult.highlight.name) && searchResult.highlight.name.length > 0){
			return strictContextual.trustAsHtml(searchResult.highlight.name[0]);
		} else if(angular.isObject(searchResult) && angular.isObject(searchResult._source) && angular.isString(searchResult._source.name)){
			return strictContextual.trustAsHtml(searchResult._source.name);
		}
	};	
	/**
	 * Listen body click event to hide the 
	 * search result container
	 * if the search container element is clicked it is ignored
	 * 
	 */
	angular.element($document).on('click','body:not(div.search-content)',function(e){
		if(self.searchElementIds.indexOf(e.target.id) == -1){
			self.showSearchContainer = false;
			
			
			if(!$scope.$$phase){
				$scope.$apply();
			}
		}
	}).on('focus','input#header-search',function(e){
		if(self.searchResults.length > 0){
			self.showSearchContainer = true;
			
			if(!$scope.$$phase){
				$scope.$apply();
			}
		}
	});
	
	self.removeCartProduct = function(index) {
		
		/*var quantity = this.cartDetails[index].quantity;
		var price = this.cartDetails[index].price;
		delete this.cartDetails[index];
		self.totalPrice = self.totalPrice - (price * quantity);
		self.cartItemCount = Object.keys(this.cartDetails).length;*/
	}
	self.getProductLink = function(product){
		return (
			angular.isObject(product) 
			&& product.hasOwnProperty('identifier') 
			&& product.hasOwnProperty('slug')
		) ? self.request.getTemplateUrl('pd/'+product.identifier+'/'+product.slug) : '#';
    };
    
    self.deleteCartDetails = function(id) {
    	self.request.delete(self.request.getUrl('cart/',{'merchant_product_id': id}),this.data,function(response){
    		self.initializeCartPopup();
    	});
    }
	
	self.initializeCartPopup = function() {	
		self.cartDetails = {};
		self.request.get(self.request.getUrl('cart'),function(data){
			var quantity,price,currentDate,cart,cartProducts,isImageExists,image,actual_price;
			var totalPrice = 0;
			currentDate = $filter('date')(new Date(), 'yyyy-MM-dd');
			cart = (data.hasOwnProperty('response')) ? data.response : (function(){throw new Error('No cart response is found')}());
			cartProducts = (cart.hasOwnProperty('cart_product')) ? cart.cart_product : (function(){throw new Error('No cart_product found on cart response')}());
			self.cartItemCount = cartProducts.length;
			if(self.cartItemCount > 3 ) {
				$('#cartList').css('height','300px');
				$('#cartList').css('overflow-y','scroll');
			}
			if(cartProducts.length > 0 ) {
				angular.forEach(cartProducts,function(cartProduct,key){
					try {
						if(cartProduct.merchant_product.inventory != null ) {
					        price  = (cartProduct.merchant_product.inventory.discount_percentage > 0 ) ? cartProduct.merchant_product.inventory.final_price : cartProduct.merchant_product.inventory.price;
					    }
						else {
							throw new Error("We didn't get price,product name is " +cartProduct.merchant_product.name );
						}	
						actual_price = price;
						quantity = (cartProduct.hasOwnProperty('sessionDetails')) ? cartProduct.sessionDetails.quantity : cartProduct.quantity;
						price = price * quantity;
						totalPrice = totalPrice + price;
						if(cartProduct.hasOwnProperty('sessionDetails')) {
							isImageExists = (cartProduct.merchant_product.hasOwnProperty('variant_image') && cartProduct.merchant_product.variant_image != null) ? false : true;
							if(isImageExists) {
								image = ( cartProduct.merchant_product.image != null && cartProduct.merchant_product.image.hasOwnProperty('small')) ? cartProduct.merchant_product.image.thumb : '#';	
							}
							else {
								image = ( cartProduct.merchant_product.variant_image != null && cartProduct.merchant_product.variant_image.hasOwnProperty('small')) ? cartProduct.merchant_product.variant_image.thumb : '#';	
							}
							
						}
						else {
							isImageExists = (cartProduct.merchant_product.hasOwnProperty('variant_image') && cartProduct.merchant_product.variant_image != null) ? false : true;
							if(isImageExists) {
								image = (cartProduct.merchant_product.image != null && cartProduct.merchant_product.image.hasOwnProperty('small')) ? cartProduct.merchant_product.image.thumb : '#';
							}
							else {
								image = (cartProduct.merchant_product.variant_image != null && cartProduct.merchant_product.variant_image.hasOwnProperty('small')) ? cartProduct.merchant_product.variant_image.thumb : '#';
							}
						}
						
						self.cartDetails[cartProduct.merchant_product_id] = {id:cartProduct.merchant_product_id,name:cartProduct.merchant_product.name,price:Number(actual_price.toFixed(2)),quantity:quantity,totalQuantity:cartProduct.merchant_product.quantity,image:image,identifier:cartProduct.merchant_product.identifier,slug:cartProduct.merchant_product.url_slug};
					}
					catch(error) {	
						self.cartItemCount = 0;
						self.cartDetails = {};
						console.log('forEach error on initializeCartPopup method' + error);
					}
				});
				if(typeof self.cartItemCount !== 'undefined' && self.cartItemCount > 0 ) {
					self.totalPrice = Number(totalPrice.toFixed(2)); 
				}				
			}
			},function(){
				self.cartItemCount = 0;
			});
	}

	
	this.addToCart = function(identifier,title,imageUrl,type){
		var merchant_product_id;
		merchant_product_id = Object.keys(self.cartDetails);
		identifier = identifier.toString();
		if(merchant_product_id.indexOf(identifier) != -1) {			
			self.request.post(self.request.getUrl('cart/add'),{'merchant_product_id' : Number(identifier),'quantity' : 1},function(data){
				if(type == 'modal') {
					self.cartNotificationBoxProductName = title;
					self.cartNotificationBoxProductImage = imageUrl;
					document.getElementsByClassName('cartModal')[0].click();
					$timeout(function(){document.getElementsByClassName('close-cartModal')[0].click();},4000);	
				}
				else {
					self.cartAddedMsg = 'Product added to cart';
					$timeout(function(){self.cartAddedMsg='';},4000);
				}
				
				self.cartDetails[Number(identifier)].quantity = self.cartDetails[Number(identifier)].quantity + 1;
				self.totalPrice = Number((self.totalPrice + self.cartDetails[Number(identifier)].price).toFixed(2));
				//self.cartDetails[Number(identifier)].price = Number((self.cartDetails[Number(identifier)].price + self.cartDetails[Number(identifier)].price).toFixed(2));				
			},function(){});
		}
		else {
			self.request.post(self.request.getUrl('cart/add'),{'merchant_product_id' : Number(identifier),'quantity' : 1},function(data){
				if(type == 'modal') {
					self.cartNotificationBoxProductName = title;
					self.cartNotificationBoxProductImage = imageUrl;
					document.getElementsByClassName('cartModal')[0].click();
					$timeout(function(){document.getElementsByClassName('close-cartModal')[0].click();},1500);	
				}
				else {
					self.cartAddedMsg = 'Product added to cart';
					$timeout(function(){self.cartAddedMsg='';},1500);
				}
				self.initializeCartPopup();
			},function(){});
		}
	};
	this.deleteToCart = function(identifier,index){		
		self.cartItemCount = self.cartItemCount - 1;
		jQuery('#mini_cart_'+index).hide();
	};
    this.goToDetail = function(product){
		if (
			angular.isObject(product) 
			&& angular.isString(product.identifier) 
			&& angular.isString(product.url_slug)
		) {
			var url = headersFactory.getBaseTemplateUrl()+'/pd/'+product.identifier+'/'+product.url_slug;
			$window.location.href =  url ? url : '#';
		}
    };
}]);
header.controller('signUpController',['$scope','headersFactory','$location',function($scope,headersFactory,$location){
	$scope.emailPopupBox = true;
	$scope.verificationPopupBox = false;
	$scope.signupPasswordBox = false;
	$scope.signupPasswordError = false;
	$scope.signupRetypePasswordError = false;
	$scope.signupPasswordErrorMsg = '';
	$scope.signupRetypePasswordErrorMsg = '';
	$scope.signUpEmailBlock = false;
	
	$scope.login_name;
	$scope.otpForm = {};
	$scope.successBlock = false;
	$scope.successMsg = '';
	$scope.allowSingUp = false;
	$scope.signupOtpError = false;
	$scope.signupOtpErrorMsg = '';
	$scope.signupPasswordError = false;
	$scope.signupPasswordErrorMsg = '';
	
	$scope.closeSignupPopup = function() {
		$scope.login_name = '';
		$scope.successBlock = false;
		$scope.successMsg = '';
		$scope.otpForm.otp = '';
		$scope.signupOtpError = false;
		$scope.signupOtpErrorMsg = '';
		$scope.signupPasswordError = false;
		$scope.signupPasswordErrorMsg = '';
		$scope.signupPasswordError = false;			
		$scope.signupPasswordErrorMsg = '';
		$scope.signupRetypePasswordError = false;
		$scope.signupRetypePasswordErrorMsg = '';
		$scope.otpForm.password = '';
		$scope.otpForm.retypepassword = '';
	}
	
	$scope.changeSingUpField = function() {
		$scope.emailPopupBox = true;
		$scope.verificationPopupBox = false;
		$scope.signupPasswordBox = false;
		$scope.signupOtpError = false;
		$scope.signupOtpErrorMsg = '';
		$scope.successMsg = '';
		
		$scope.showOtpForm = false;
		$scope.signUpForms = true;
		$scope.allowSingUp = false;
		$scope.login_name;
		$scope.login_name_error = '';
		$scope.otpForm = {};
	}
	
	$scope.checkVerificationCode = function() {
		if($scope.otpForm.otp == '' || $scope.otpForm.otp == undefined ) {
			$scope.signupOtpError = true;
			$scope.signupOtpErrorMsg = 'Enter Verification Code';
			return false;
		}
		var response = $scope.getRecords('check-verification-code',{code:$scope.otpForm.otp});
		response.then(function(data){
			$scope.emailPopupBox = false;
			$scope.verificationPopupBox = false;
			$scope.signupPasswordBox = true;
			$scope.signupOtpErrorMsg = '';
		},function(data){
			$scope.signupOtpError = true;
			$scope.signupOtpErrorMsg = 'Invalid code';
		});
	}
	
	$scope.generateOTP = function() {
		if( typeof $scope.login_name !== "undefined" || $scope.login_name !== null || $scope.login_name != '') {
			
			if(EMAIL_REGEXP.test($scope.login_name)) {
				var response = $scope.getRecords('generateotp',{login_name:$scope.login_name});
				response.then(function(data){
					$scope.emailPopupBox = false;
					$scope.verificationPopupBox = true;
					$scope.signupPasswordBox = false;
					$scope.successBlock = true;
					$scope.successMsg = 'Verification code send to '+$scope.login_name;	
					$scope.emailorphoneResponse = false;
					$scope.showEmailField = true;
					$scope.showPhoneField = false;
					$scope.showOtpForm = true;
					$scope.signupError = false;
					$scope.allowSingUp = true;
					$scope.signUpForms = false;
					$scope.otpForm.optRecordField = $scope.login_name;
					$scope.login_name = '';
				},function(data){
					$scope.allowSingUp = false;
					$scope.showOtpForm = false;
					console.log('error occurred');
				});
			}
			if(PHONE_REGEXP.test($scope.login_name)) {
				var response = $scope.getRecords('generateotp',{login_name:$scope.login_name});
				response.then(function(data){
					$scope.emailPopupBox = false;
					$scope.verificationPopupBox = true;
					$scope.signupPasswordBox = false;
					$scope.successBlock = true;
					$scope.successMsg = 'Verification code send to '+$scope.login_name;					
					$scope.emailorphoneResponse = false;
					$scope.showEmailField = false;
					$scope.showPhoneField = true;
					$scope.showOtpForm = true;
					$scope.signupError = false;
					$scope.allowSingUp = true;
					$scope.signUpForms = false;
					$scope.otpForm.optRecordField = $scope.login_name;
					$scope.login_name = '';
				},function(data){
					$scope.allowSingUp = false;
					$scope.showOtpForm = false;
					console.log('error occurred');
				});
			}
		}
	}
	$scope.storePassword = function() {
		if($scope.otpForm.password == undefined || $scope.otpForm.password == '') {
			$scope.signupPasswordError = true;			
			$scope.signupPasswordErrorMsg = 'Enter password';
			$scope.signupRetypePasswordError = false;
			$scope.signupRetypePasswordErrorMsg = '';
			return false;
		}
		
		if($scope.otpForm.password.length <= 5) {
			$scope.signupPasswordError = true;			
			$scope.signupPasswordErrorMsg = 'Password atleast 6 characters long';
			$scope.signupRetypePasswordError = false;
			$scope.signupRetypePasswordErrorMsg = '';
			return false;
		}
		
		if($scope.otpForm.retypepassword == '' || $scope.otpForm.retypepassword == undefined ) {
			$scope.signupPasswordError = false;			
			$scope.signupPasswordErrorMsg = '';
			$scope.signupRetypePasswordError = true;
			$scope.signupRetypePasswordErrorMsg = 'Enter Retype Password';
			return false;
		}
		
		if($scope.otpForm.retypepassword !=  $scope.otpForm.password) {
			$scope.signupPasswordError = false;			
			$scope.signupPasswordErrorMsg = '';
			$scope.signupRetypePasswordError = true;
			$scope.signupRetypePasswordErrorMsg = 'Entered passsword does not match';
			return false;
		}
		
		var response = $scope.getRecords('savepassword',{password:$scope.otpForm.password});
		response.then(function(data){
			window.location.href = $location.absUrl();
		},function(data){
			$scope.showOtpForm = false;
			console.log('error occurred');
		});
	}
}]);
header.controller('forgotController',['$scope','headersFactory','$location',function($scope,headersFactory,$location){
	$scope.forgotPopupBox = true;
	$scope.forgotVerificationPopupBox = false;
	$scope.forgotPasswordBox = false;
	$scope.forgotOtpError = false;
	$scope.forgotOtpErrorMsg = '';
	$scope.successBlock = false;
	$scope.successMsg = '';
	
	
	$scope.forgotRecord='';	
	$scope.forgotErrorMsg='';
	$scope.forgotKeyError = false;
	$scope.forgotForm = true;
	$scope.forgotOtpForm = false;
	$scope.forgotPhoneField = false;
	$scope.forgotEmailField = false;
	$scope.forgotOtp = {};
	$scope.allowOtp = false;
	$scope.closeForgotPopup = function() {
		$scope.forgotRecord='';	
		$scope.forgotErrorMsg='';
		$scope.forgotKeyError = false;
		$scope.successBlock = false;
		$scope.successMsg = '';
		$scope.forgotOtpError = false;
		$scope.forgotOtpErrorMsg = '';
		$scope.forgotOtp.otp = '';
		$scope.forgotOtp.password = '';
		$scope.forgotOtp.retypepassword = '';
		$scope.forgotPasswordError = false;			
		$scope.forgotPasswordErrorMsg = '';
		$scope.forgotRetypePasswordError = false;
		$scope.forgotRetypePasswordErrorMsg = '';
		
	}
	
	$scope.changeForgotField = function() {
		$scope.forgotOtpError = false;
		$scope.forgotOtpErrorMsg = '';
		$scope.successMsg = '';
		$scope.forgotPopupBox = true;
		$scope.forgotVerificationPopupBox = false;
		$scope.forgotPasswordBox = false;
		$scope.forgotForm = true;
		$scope.forgotOtpForm = false;
		$scope.forgotRecord = '';
		$scope.forgotErrorMsg='';
		$scope.forgotKeyError = false;
		$scope.allowOtp = false;
		$scope.forgotOtp = {};
	}
	
	$scope.forgotVerificationCode = function() {
		if($scope.forgotOtp.otp == '' || $scope.forgotOtp.otp == undefined) {
			$scope.forgotOtpError = true;
			$scope.forgotOtpErrorMsg = 'Enter Verification Code';
			return false;
		}
		
		var response = $scope.getRecords('forgot-verification-code',{code:$scope.forgotOtp.otp});
		response.then(function(data){
			$scope.forgotPopupBox = false;
			$scope.forgotVerificationPopupBox = false;
			$scope.forgotPasswordBox = true;
			$scope.forgotOtpErrorMsg = '';
		},function(data){
			$scope.forgotOtpError = true;
			$scope.forgotOtpErrorMsg = 'Invalid code';
		});
	}
	
	$scope.saveForgot = function() {

		if($scope.forgotOtp.password == undefined || $scope.forgotOtp.password == '') {
			$scope.forgotPasswordError = true;			
			$scope.forgotPasswordErrorMsg = 'Enter password';
			$scope.forgotRetypePasswordError = false;
			$scope.forgotRetypePasswordErrorMsg = '';
			return false;
		}
		
		if($scope.forgotOtp.password.length <= 5 ) {
			$scope.forgotPasswordError = true;			
			$scope.forgotPasswordErrorMsg = 'Password atleast 6 characters long';
			$scope.forgotRetypePasswordError = false;
			$scope.forgotRetypePasswordErrorMsg = '';
			return false;
		}
		
		if($scope.forgotOtp.retypepassword == '' || $scope.forgotOtp.retypepassword == undefined ) {
			$scope.forgotPasswordError = false;			
			$scope.forgotPasswordErrorMsg = '';
			$scope.forgotRetypePasswordError = true;
			$scope.forgotRetypePasswordErrorMsg = 'Enter Retype Password';
			return false;
		}
		
		if($scope.forgotOtp.retypepassword !=  $scope.forgotOtp.password) {
			$scope.forgotPasswordError = false;			
			$scope.forgotPasswordErrorMsg = '';
			$scope.forgotRetypePasswordError = true;
			$scope.forgotRetypePasswordErrorMsg = 'Entered password does not match';
			return false;
		}
		var response = $scope.getRecords('saveforgot',{password:$scope.forgotOtp.password});
		response.then(function(data){
			window.location.href = $location.absUrl();
		},function(data){
			$scope.showOtpForm = false;
			console.log('error occurred');
		});
	}
	$scope.getForgotOtp = function() {
		if($scope.forgotRecord == '' || $scope.forgotRecord === undefined || $scope.forgotRecord === null ) {
			$scope.forgotKeyError = true;
			$scope.forgotErrorMsg = 'Enter email or phone number';
			return false;
		}
       if ( STRING_REGEXP.test($scope.forgotRecord) == true ) {
        	if(EMAIL_REGEXP.test($scope.forgotRecord)) {	
        		var response = $scope.getRecords('forgototp',{login_name:$scope.forgotRecord});
        		response.then(function(result){
        			$scope.forgotPopupBox = false;
        			$scope.forgotVerificationPopupBox = true;
        			$scope.successBlock = true;
        			$scope.successMsg = 'Verification send to '+$scope.forgotRecord;
        			
        			$scope.forgotForm = false;
        			$scope.forgotOtpForm = true;
        			$scope.forgotOtpError = false;
        			$scope.allowOtp = true;
        			$scope.forgotPhoneField = false;
        			$scope.forgotEmailField = true;
        			$scope.forgotOtp.recordField = $scope.forgotRecord;
        			$scope.forgotRecord = null;
        		},function(result){
        			console.log('error');
        			$scope.allowOtp = false;
        			$scope.forgotKeyError = true;
        			$scope.forgotErrorMsg = 'Invalid email';
        			return false;
        		});
        	}
        	else {
        		$scope.forgotKeyError = true;
        		$scope.allowOtp = false;
    			$scope.forgotErrorMsg = 'Enter valid email';
    			return false;
        	}
        }
        
		if( STRING_REGEXP.test($scope.forgotRecord) == false ) {
        	if(PHONE_REGEXP.test($scope.forgotRecord)) {
        		var response = $scope.getRecords('forgototp',{login_name:$scope.forgotRecord});
        		response.then(function(){
        			$scope.forgotPopupBox = false;
        			$scope.forgotVerificationPopupBox = true;
        			$scope.successBlock = true;
        			$scope.successMsg = 'Verification send to '+$scope.forgotRecord;
        			$scope.forgotForm = false;
        			$scope.forgotOtpForm = true;
        			$scope.forgotOtpError = false;
        			$scope.allowOtp = true;
        			$scope.forgotPhoneField = true;
        			$scope.forgotEmailField = false;
        			$scope.forgotOtp.recordField = $scope.forgotRecord;
        			$scope.forgotRecord = null;
        		},function(){
        			$scope.allowOtp = false;
        			$scope.forgotKeyError = true;
        			$scope.forgotErrorMsg = 'Invalid phone number';
        			return false;
        		});
        	}
        	else {
        		$scope.allowOtp = false;
        		$scope.forgotKeyError = true;
    			$scope.forgotErrorMsg = 'Enter valid phone number';
    			return false;
        	}
        }
		
		
	}
}]);

header.controller('loginController',['$scope','$http','$controller','headersFactory','$location',function($scope,$http,controller,headersFactory,$location){
	
	$scope.loginKey = '';
	$scope.loginPassword = '';
	$scope.loginKeyError = false;
	$scope.loginErrorMsg = '';
	$scope.loginEmailBlock = false;
	$scope.loginPasswordBlock = false;
	$scope.loginPasswordErrorMsg = '';
	
	$scope.login = function() {

		
		if($scope.loginKey == '' || $scope.loginKey === undefined || $scope.loginKey === null ) {
			$scope.loginEmailBlock = true;
			$scope.loginErrorMsg = 'Enter Email or Phone number';
			$scope.loginPasswordBlock = false;
			$scope.loginPasswordErrorMsg = '';
			return false;
		}
		else if($scope.loginPassword == '' || $scope.loginPassword === undefined || $scope.loginPassword === null) {
			$scope.loginErrorMsg = '';
			$scope.loginEmailBlock = false;
			$scope.loginPasswordBlock = true;
			$scope.loginPasswordErrorMsg = 'Enter Password';
			return false;
		}
		
		if ( STRING_REGEXP.test($scope.loginKey) == true ) {
        	
        	if(EMAIL_REGEXP.test($scope.loginKey)) {	
        		var response = $scope.getRecords('login',{login_name:$scope.loginKey,password:$scope.loginPassword});
        		response.then(function(result){
        			window.location.href = $location.absUrl();
        		},function(result){
        			$scope.loginErrorMsg = '';
        			$scope.loginEmailBlock = false;
        			$scope.loginPasswordBlock = true;
        			$scope.loginPasswordErrorMsg = 'Invalid Details';
        			return false;
        		});
        	}
        	else {
        		$scope.loginEmailBlock = true;
    			$scope.loginErrorMsg = 'Enter valid email';
    			$scope.loginPasswordBlock = false;
    			$scope.loginPasswordErrorMsg = '';
    			return false;
        	}
        }
        
		if( STRING_REGEXP.test($scope.loginKey) == false ) {
        	if(PHONE_REGEXP.test($scope.loginKey)) {
        		var response = $scope.getRecords('login',{login_name:$scope.loginKey,password:$scope.loginPassword});
        		response.then(function(){
        			window.location.href = $location.absUrl();
        		},function(){
        			$scope.loginErrorMsg = '';
        			$scope.loginEmailBlock = false;
        			$scope.loginPasswordBlock = true;
        			$scope.loginPasswordErrorMsg = 'Invalid Details';
        			return false;
        		});
        	}
        	else {
        		$scope.loginErrorMsg = 'Enter 10 digit mobile number';
    			$scope.loginEmailBlock = true;
    			$scope.loginPasswordBlock = false;
    			$scope.loginPasswordErrorMsg = '';
    			return false;
        	}
        }

		
		if($scope.emailFormResponse) {
			var config = {
				type       : $scope.storeType,
				loginKey : $scope.loginKey,
				loginPassword   :  $scope.loginPassword,
			};
			 $http.post(headersFactory.getBaseApiUrl() +'/login',config).then(function(result) {
			 } );

		}

	}
	
}]);
header.controller('trackOrderController',['$scope','$controller','headersFactory','$timeout','$window',function($scope,$controller,headersFactory,$timeout,$window) {
	var self = this;
	$scope.orderNumber;
	$scope.orderNumberError;
	$scope.request = $controller('RequestController');
	$scope.trackOrder = function() {
		if(($scope.orderNumber !== undefined) && $scope.orderNumber !='') {
			$scope.request.post($scope.request.getUrl('homepage/trackorder'),{orderNumber:$scope.orderNumber},
				function(data){
				$window.location.href = $scope.request.getTemplateUrl('order/trackdetail/'+$scope.orderNumber);
				},
				function(data){
					$scope.orderNumber = '';
					$scope.orderNumberError = data.data.message;
					$scope.clear();
					});
			
		}
		else {
			$scope.orderNumberError = "Enter Order Number";
			$scope.clear();
		}		
	}
	$scope.clear = function() {
		$timeout(function(){
			$scope.orderNumber = '';
			$scope.orderNumberError = '';
		},1500);
	}
}]);

header.directive('emailorphone', ['$q','$http','headersFactory',function($q,$http,headersFactory) {
	  return {
	    require: 'ngModel',
	    link: function(scope, elm, attrs, ctrl) {
	      ctrl.$asyncValidators.emailorphone = function(modelValue, viewValue) {
	    	var def = $q.defer();	    	  
	        if (ctrl.$isEmpty(modelValue) ) {
	        	scope.signUpEmailBlock = true;
	        	scope.login_name_error = 'Enter Email Or Phone Number';
	        	def.reject();
	        }
	        if ( viewValue != undefined && STRING_REGEXP.test(viewValue) == true ) {
	        	if(EMAIL_REGEXP.test(viewValue)) {	        		
	        		var status = scope.getRecords('checkavailability',{login_name:viewValue});
	        		status.then(function(data){
	        			def.resolve();
	        		},function(data){
	        			scope.signUpEmailBlock = true;
	        			scope.login_name_error = 'Email Already exists';
	        			def.reject();
	        		});
	        	}
	        	else {
	        		scope.signUpEmailBlock = true;
	        		scope.login_name_error = 'Enter Valid Email';
	        		def.reject();
	        	}
	        }
	        else if( viewValue != undefined && viewValue != '' && STRING_REGEXP.test(viewValue) == false ) {
	        	if(PHONE_REGEXP.test(viewValue)) {
	        		var status = scope.getRecords('checkavailability',{login_name:viewValue});
	        		status.then(function(data){
	        			
	        			def.resolve();
	        		},function(data){
	        			scope.signUpEmailBlock = true;
	        			scope.login_name_error = 'Phone Number Already exists';
	        			def.reject();
	        		});
	        	}
	        	else {
	        		scope.signUpEmailBlock = true;
	        		scope.login_name_error = 'Enter 10 digit Mobile Number';
	        		def.reject();
	        	}
	        }
	        else {
	        	def.reject();
	        }	
	        return def.promise;
	      };
	    }
	  };
	}]);

function categoryRedirect() {
	var meta = $("meta[name=base-template-url]");  
	if($('.nav-search-label').text().indexOf('Categories') != -1 ) {
		return false;
	}
	else {
		window.location.href = meta.attr('content') +'/products/'+ $('.nav-search-label').text();
	}
	
}
$(document).ready(function(){
	
	$("#back-to-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-to-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
	
	$('.login_dropdown_menu').hide();
	var demoTabs = new SimpleTabs($('#mobile-menu-tabs'));
	 $(".mobile_footer_nav").click(function() {
         $(".footer_menu .grid_3.policy ul").slideToggle("slow");
     });
     $(".mobile_footer_nav1").click(function() {
         $(".footer_menu .grid_3.business ul").slideToggle("slow");
     });
     $(".mobile_footer_nav2").click(function() {
         $(".footer_menu .grid_4.buyers_mobile ul").slideToggle("slow");
     });
     $(".mobile_footer_nav3").click(function() {
         $(".footer_menu .grid_4.buyers.gutterwidth ul").slideToggle("slow");
     });
     $(".mobile_footer_nav4").click(function() {
         $(".footer_menu .grid_2 ul").slideToggle("slow");
     });
     
     $('.mobile #category li ul').hide();
     
     $('.mobile #category li').click(function(e){
    	 
    	 
    	 e.stopPropagation();
    	 $(' > ul',this).toggle();
    	 if($(' > ul',this).css('display') == 'block') {
    		 $(this).addClass('menu-open');
    	 }
    	 else {
    		 $(this).removeClass('menu-open');
    	 }
    	 $('body').addClass('pushy-active');
    	 $('#container').addClass('container-push');
    	 $('.mobile').removeClass('pushy-left');
    	 $('.mobile').addClass('pushy-open');
    	 
     });     
     
});
//setTimeout(function(){ $('.login_dropdown_menu').hide(); }, 2500);
$("body").click(function(){
	$(".nav-search-dropdown").hide();
	$('#mini-cart').hide();
	});

$(".mini-cart").click(function(e){
	e.stopPropagation();
	$('#mini-cart').toggle();
});

$(".nav-search").click(function(e){
	e.stopPropagation();
	$(".nav-search-dropdown").toggle();	
	});
$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $('.header-menu').addClass("fixed");
    } else {
        $('.header-menu').removeClass("fixed");
    }
});
$(".nav-search-value").click(function(e){
	e.stopPropagation();
     var value = $(this).text();
     $('.nav-search-label').text(value);
     $(".nav-search-dropdown").toggle();
	});