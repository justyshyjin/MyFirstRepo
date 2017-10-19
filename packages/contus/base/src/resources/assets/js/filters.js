'use strict';

var filters = angular.module('mara.filters',[]);

filters.filter('ucfirst', function() {
  return function(input,arg) {
    return input.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
  };
})

