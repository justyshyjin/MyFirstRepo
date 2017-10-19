( function () {
    'use strict';
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controller.controller( 'dashboardController', [
            '$scope', 'requestFactory', '$state', '$rootScope', function ( $scope, requestFactory, $state, $rootScope ) {
                /*
                 * fetch videos of all categories with count on each category
                 */
                $scope.category = {};
                var successResponseData;
                var dataBinder = function () {
                    $scope.data = successResponseData.response;
                };
                var success = function ( success ) {
                $scope.data = success;
                };
                var fail = function ( fail ) {
                    return fail;
                };
                              requestFactory.get( requestFactory.getUrl( 'categoryVideos' ), success, fail );
                              $scope.showVideo = function () {
                
                                };
                $scope.videoOwlCarouselOptions = {
                        items : 1,
                          navigation: true,
                          pagination : false,  
                          navigationText: ["<span class='hopsprite hopsprite-left'></span>","<span class='hopsprite hopsprite-right'></span>"],
                          itemsDesktop : [1199,1],
                          itemsDesktopSmall : [979,1],
                          nav : false,
                          loop : true,
                          dots : false
                };
                $scope.clientOwlCarouselOptions = {
                    loop : true,
                    nav : false,
                    margin : 10,
                    dots : true,
                    autoplay : true,
                    mouseDrag : true,
                    pagination : true,
                    responsive : {
                        0 : {
                            items : 1
                        },
                        600 : {
                            items : 1
                        },
                        700 : {
                            items : 2
                        },
                        992 : {
                            items : 2,
                            loop : false
                        }
                    }
                };
                $( "#currentaffiars-slider,.playlist-collections-slider" ).owlCarousel( {
                    loop : true,
                    dots : false,
                    nav : true,
                    margin : 15,
                    autoplay : true,
                    mouseDrag : true,
                    responsive : {
                        0 : {
                            items : 1
                        },
                        600 : {
                            items : 2
                        },
                        700 : {
                            items : 3
                        },
                        992 : {
                            items : 4,
                            loop : false
                        }
                    }
                } );
                $( "#news-glance" ).owlCarousel( {
                    autoPlay : false,
                    nav : true,
                    loop : false,
                    dots : false,
                    items : 3,
                    margin : 30,
                    pagination : false,
                    itemsDesktop : [
                            1199, 3
                    ],
                    itemsDesktopSmall : [
                            979, 3
                    ]
                } );
 var navListItems = $('div.setup-panel div a'),
  allWells = $('.setup-content'),
  allNextBtn = $('.nextBtn');

allWells.hide();
navListItems.click(function (e) {
e.preventDefault();
var $target = $($(this).attr('href')),
      $item = $(this);

if (!$item.hasClass('disabled')) {
  navListItems.removeClass('btn-primary').addClass('btn-default');
  $item.addClass('btn-primary');
  allWells.hide();
  $target.show();
  $target.find('input:eq(0)').focus();
}
});
allNextBtn.click(function(){
var curStep = $(this).closest(".setup-content"),
  curStepBtn = curStep.attr("id"),
  nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
  curInputs = curStep.find("input[type='text'],input[type='url']"),
  isValid = true;

$(".form-group").removeClass("has-error");
for(var i=0; i<curInputs.length; i++){
  if (!curInputs[i].validity.valid){
      isValid = false;
      $(curInputs[i]).closest(".form-group").addClass("has-error");
  }
}

if (isValid)
  nextStepWizard.removeAttr('disabled').trigger('click');
});
$('div.setup-panel div a.btn-primary').trigger('click');
$(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
            }
    ] );
} )();