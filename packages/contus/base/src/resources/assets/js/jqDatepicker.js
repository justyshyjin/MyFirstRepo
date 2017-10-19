var datepicker = angular.module('mara.jqdatepicker', []);

datepicker.directive("datepicker", function () {
	var getDateObjectFromString = function(date,sperator) {
		var dateObject = new Date;

		if(angular.isString(date)){
			var dateArray = date.split('/');
			/**
			* date aray
			* dateArray[0] ==> year
			* dateArray[1] ==> month
			* dateArray[2] ==> day
			* 1 is added to date since end should be atleast the 1 after start date
			*/					
			if(dateArray.length == 3){
				dateObject.setFullYear(Number(dateArray[0]));
				dateObject.setMonth(Number(dateArray[1]) - 1);
				dateObject.setDate(Number(dateArray[2]) + 1);
			}
		}

		return dateObject;
	};	
	
  return {
    restrict: "A",
    require: "ngModel", 
    link: function (scope, elem, attrs, ngModelCtrl) {
      var self = this; 	 
      var updateModel = function (dateText) {
        scope.$apply(function () {
          ngModelCtrl.$setViewValue(dateText);
        });
      };
      var options = {
        dateFormat: "yy/mm/dd",
        minDate   : new Date(),
        onSelect  : function (dateText) {
          updateModel(dateText);
          
          $('input[name="'+attrs.dpStart+'"]').datepicker('change',{
		       maxDate  : getDateObjectFromString(dateText),
		  });    
          
          $('input[name="'+attrs.dpEnd+'"]').datepicker('change',{
		       minDate  : getDateObjectFromString(dateText),
		  });           
        }
      };      
      elem.datepicker(options);
    }
  }
});