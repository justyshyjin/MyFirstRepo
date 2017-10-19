
attribute.directive('uniqueAttributeName', function($q, $http) {
  return {
    require: 'ngModel',
    link: function(scope, elm, attrs, ctrl) {
      var params = attrs.uniqueAttributeName.split('|');

      ctrl.$asyncValidators.uniqueAttributeName = function(modelValue, viewValue) {

        if (ctrl.$isEmpty(modelValue) || typeof params != 'object' || params.length != 3) {
          // consider empty model valid
          return $q.when();
        }console.log(params);
        var url = params[0];
        var controller = params[1];
        var field = params[2];

        var def = $q.defer();

        $http.get(url).then(function(){
           if(scope.hasOwnProperty(controller) && scope[controller].errors.hasOwnProperty(field)){
             delete scope[controller].errors[field];
           }
          def.resolve();
        },function(){
          if(scope.hasOwnProperty(controller)){
              scope[controller].errors[field] = maraValidator.getErrorMessageByRule('unique').replace(/:attribute/g, maraValidator.ucfirst(field));

              scope.$apply()
          }

          def.reject();
        });

        return def.promise;
      };
    }
  };
});