(function() {
    'use strict';
    var controllers = angular.module("app.controllers");
    controllers.directive('baseValidator', validatorDirective);
    controllers.controller('forgotController', [
            '$rootScope',
            '$state',
            '$scope',
            'ngToast',
            function($rootScope, $state, $scope, ngToast) {
                baseValidator.setRules({
                    password : "required|confirmed|min:6",
                    password_confirmation : "required|same:password|min:6",
                });
                $scope.cancel = function () {
                    $rootScope.closePopUp($state);
                };
                $scope.forgotpassword = function($event) {
                    if (baseValidator.validateAngularForm($event.target, $scope)) {
                        var form = document.querySelector('form[name="forgotformreset"]');
                        $scope.setname = 0;
                        form.action = form.getAttribute('id');
                        $rootScope.httpLoaderLocalElement = 1;
                        form.submit()
                    }
                }
            } ])

})();