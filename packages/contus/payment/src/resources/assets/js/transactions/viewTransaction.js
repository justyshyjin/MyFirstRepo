'use strict';

var viewTransactionDetail = angular.module('viewTransactionDetail',[]);

viewTransactionDetail.directive('baseValidator',validatorDirective);

viewTransactionDetail.factory('requestFactory',requestFactory);

viewTransactionDetail.controller('viewTransactionController',['$window','$scope','$rootScope','requestFactory','$sce','$timeout',function(win,scope,$rootScope,requestFactory,$sce,$timeout){
    var self = this;
    scope.errors = {};
    this.transaction = {};
    this.showResponseMessage = false;
    this.gridLoadingBar = false;
    requestFactory.setThisArgument(this);
    this.notFoundFlag = false;
    
    this.fetchData = function(id) {  
      requestFactory.get(requestFactory.getUrl('transactions/complete-transaction-details/'+id),function(response){
        requestFactory.toggleLoader();
        var transactionDetails = response.response;
        
        console.log(transactionDetails);
        this.transaction.id = transactionDetails.id;
        
        this.transaction.customer = transactionDetails.get_transaction_user.name;
        this.transaction.transaction_id = transactionDetails.transaction_id;        
        this.transaction.payment_method = transactionDetails.get_payment_method.name;
        this.transaction.status = transactionDetails.status;        
        this.transaction.message = transactionDetails.transaction_message;
        this.transaction.created_at = transactionDetails.created_at;     

      }, function(response){
    	  self.notFoundFlag = true;
    	  requestFactory.toggleLoader();
      }); 
    }

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

}]);

/**
* Manually bootstrap the Angular module here
*/
angular.element(document).ready(function() {
  angular.bootstrap(document, ['viewTransactionDetail']);
});
