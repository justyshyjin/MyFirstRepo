'use strict';
var selection = angular.module('mara.selection',[]);

selection.directive('selectTwo', ['$controller','$http',function(controller,$http) {
	var request = controller('RequestController');
	  return {
		    restrict: 'A',
		    scope:{},
		    controller:['$rootScope','$scope',function($rootScope,$scope) {
		    	$scope.lists = [];
		    	$rootScope.selectedChildCategory = [];
		    	$rootScope.reloadCategory = function(id) {
		    		$http.post(request.getTemplateUrl('manufactures/category'),{parent_id:id}).then(function(data){
		    			$scope.category = data.data.data;
    		    		});
		    	}
		    	if($rootScope.hasOwnProperty('edit')) {
		    		angular.forEach($rootScope.editCategoryList,function(category,key){
		    			$scope.lists.push({id:category.category.id,name:category.category.name});
			    		$rootScope.selectedChildCategory = $scope.lists;
		    		});
		    	}
		    	$scope.getCategoryId = function() {
		    		
		    		return ($rootScope.hasOwnProperty('categoryId')) ? $rootScope.categoryId : '';
		    	}
		    	
		    	$scope.addDetails = function(id,name) {
		    		$scope.name = '';
		    		$scope.suggestions = [];
		    		$scope.lists.push({id:id,name:name});
		    		$rootScope.selectedChildCategory = $scope.lists;		
		    	}
		    	
		    	$scope.removeDetails = function(index) {
		    		$scope.lists.splice(index,1);
		    		$rootScope.selectedChildCategory = $scope.lists;
		    	}
		    	}],
		    link: function(scope,element,attr){
		    	
		    	scope.suggestions = [];		    	
		    	
                var categoryId = scope.getCategoryId();
                if(categoryId != '') {
                	scope.parent_id = {parent_id:categoryId};
    		    	$http.post(request.getTemplateUrl('manufactures/category'),scope.parent_id).then(function(data){
    		    		scope.category = data.data.data;
    		    		});
                }
		    	
		    	
		    	element.find('input[type="text"]').on('keyup',function(){
		    		var name = this.value;
		    		if(typeof name === 'string' && name != '' && name.length >= 1 && attr.selectTwo != 'category') {
		    			request.post(request.getUrl('homepage/product'),{name:name},function(data){
		    				scope.suggestions = data;
		    			},function(data){
		    				alert('error');
		    			});
		    		}
		    		else if(typeof name === 'string' && name != '' && name.length >= 1 && attr.selectTwo == 'category') {
		    			scope.suggestions = [];
		    			angular.forEach(scope.category,function(value,key){
		    				if(value[Object.keys(value)[0]].toLowerCase().indexOf(name.toLowerCase()) != -1) {
		    					scope.suggestions.push({id:Object.keys(value)[0],name:value[Object.keys(value)[0]]});
		    					scope.$apply();
		    				}
		    			});
		    		}
		    		else {
		    			scope.suggestions= [];
		    			scope.$apply();
		    		}
		    	});
		    	
		    },
		    templateUrl: request.getTemplateUrl('selecttwo'),
		  }}]);


