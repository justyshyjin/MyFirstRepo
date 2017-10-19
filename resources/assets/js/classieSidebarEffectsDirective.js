'use strict';

/**
 * directive method for intialize sidebar effects
 * after data is feeded directive should be used
 */
var intializeSidebar = ['$timeout','$rootScope',function ($timeout,$rootScope) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            sidebarMenuEffectsInit();
        }
    }
}];

