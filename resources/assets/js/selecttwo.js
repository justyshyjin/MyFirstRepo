var app = angular.module('myapp',[]);

app.controller('myctrl',['$scope',function($scope){
$scope.variants = [];
$scope.variantsArray = [];
$scope.tempArray = [];
$scope.result = [];

$scope.addVariants = function() {
	$scope.variants.push({variantTitle:'',variantOptions:[]});	
}; 

$scope.update = function() {
	

	$scope.variantsArray = [];
	$scope.tempArray = [];
	$scope.result = [];
	$scope.common = [];
var z = 0;

	for(i = 0;i<$scope.variants.length;i++) {
		
		if($scope.variants[i].variantOptions) {
			$scope.variantsArray.push($scope.variants[i].variantOptions);
		}
	}
	
	if($scope.variantsArray.length > 0 ) {
		for(var index = 0 ; index < $scope.variantsArray[0].length ;  index++) {
			$scope.result.push([$scope.variantsArray[0][index]]);
		}
	}
	


	for(var index = 1; index < $scope.variantsArray.length; index++ ) {
		
		$scope.common = [];
		for(var subIndex=0; subIndex < $scope.variantsArray[index].length;subIndex++) {
			
			if($scope.variantsArray[index][subIndex] != '' ) {

				for(var resultArrayIndex = 0; resultArrayIndex < $scope.result.length; resultArrayIndex++) {
					
					
					if(index == $scope.result[resultArrayIndex].length - 1 ) {
						$scope.tempArray = $scope.result[resultArrayIndex].slice(0);
						$scope.tempArray.splice($scope.tempArray.length -1 ,1,$scope.variantsArray[index][subIndex]);
						$scope.common.push($scope.tempArray);
					
					}
					else {
						$scope.result[resultArrayIndex].push($scope.variantsArray[index][subIndex]);
					}
				}

			}
		}
		for(var zz = 0; zz < $scope.common.length;zz++ ) {
			$scope.result.push($scope.common[zz]);
		}
	}
	
	
console.log($scope.result);
	
};



$scope.delete = function(index) {
	console.log(index);
	$scope.variants.splice(index,1);
	$scope.variants = $scope.variants;
};

}]);

app.directive('selectTwo',function(){
	return {	
		link: function(scope,elm,attr) {
			//scope.tags = [];
			showTagsPopup = 'false';
		}
			};
		});

app.directive('contenteditable',['$document','$timeout','$http', function($document,$timeout,$http) {
	  return {
		    require: 'ngModel',
		    link: function(scope, elm, attrs, ctrl) {
	    	var routeName = attrs.routeName;	
	    	var parentIndex='';
		      elm.on('keydown', function() {
			      
		    	  if(event.keyCode == 13 ) {
		    		  parentIndex = event.target.getAttribute('data-index');
		    		  if(scope.variants[parentIndex].variantOptions.indexOf(elm.html()) == -1 ) {
		    			  scope.variants[parentIndex].variantOptions.push(elm.html());
		    		  }
		    		  ctrl.$setViewValue('');
		    		  elm.html('');   		  
		    		  event.preventDefault();
		    	  }
		    	  
		    	  if(event.keyCode == 8 ) {
		    		  if(elm.html() == '' ) {
		    			  parentIndex = event.target.getAttribute('data-index');
		    			  //scope.tags.splice(scope.tags.length - 1,1);
		    			  scope.variants[parentIndex].variantOptions.splice(scope.variants[parentIndex].variantOptions.length -1,1);
		    			  ctrl.$setViewValue();
		    		  }
		    	  }

		    	  if(event.keyCode != 13) {
		    		  if(elm.html() != '' ) {
		    			  $timeout(function(){
	$http.get(routeName+'/records/0').success(function(data){
		scope.extTags = data;
		scope.showTagsPopup = true;
	});
	ctrl.$setViewValue();
			    			  },500); 
		    		  }
		    	  }
		    	  
		      });
		      
		      scope.remove = function(parentIndex,optionIndex) {
		    	  //scope.tags.splice(index,1);
		    	  scope.variants[parentIndex].variantOptions.splice(optionIndex,1)
		      };
		      
		      scope.addTag = function(name) {
		    	  scope.tags.push(name);
		    	  ctrl.$setViewValue('');
	    		  elm.html('');
		      }

		      // model -> view
		      ctrl.$render = function() {
		        elm.html(ctrl.$viewValue);
		      };

		      // load init value from DOM
		      ctrl.$setViewValue(elm.html());

		      $document.on('click',function(){
		    	  if(document.getElementsByClassName('tagsPopup').length > 0 ) {
		    		  popup = document.getElementsByClassName('tagsPopup')[0];
		    		  if(popup.contains(event.target) === false) {
		    			  scope.showTagsPopup = false;
		    			  ctrl.$setViewValue();
		    		  }
		    	  }
			      });
		      
		    }
		  };
		}]);