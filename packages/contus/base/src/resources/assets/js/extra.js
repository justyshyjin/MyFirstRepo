'use strict';

/*
 * AngularJS Directives Filters Factory functions
 */
( function () {
    
    var factoryCategory = angular.module( "app.routes" );
    factoryCategory.factory( 'requestFactory', requestFactory );
    factoryCategory.directive( 'categoryList', [
            '$http', '$rootScope', 'requestFactory','$timeout','ngToast', function ( $http, $rootScope, requestFactory ,$timeout,ngToast) {
                return {
                    restrict : 'A',
                    replace : true,
                    scope : {
                        list : '='
                    },
                    link : function ( $scope ) {
                    	var elems = angular.element('.login_flash_message').length;
                        if (elems) {
                        	 ngToast.create( {className : 'danger',content : '<strong>You have already logged in  with other browser.  Please login again to continue.</strong>'} );
                        }
                    	if($(window).width() > 990){
                    		$('a#browse-videos').dropdown();
	                    	$('ul.nav li.dropdown').hover(function() {
	                    	 $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(300);
	                    	 $('ul.second-level>li:first').addClass('menuactiveli');
		                    }, function() {
		                      $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
		                    });
	                    	
	                    	$('ul.nav li.dropdown').find('.dropdown-menu').click(function(){
	                            $('ul.nav li.dropdown').find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
	                        })
                    	} else {
                            $(document).on("click","a.toggle-childs",function(e){
                            	e.preventDefault();
                            	var ul = $(this).next();
                            	
                            	if(ul.attr('style') == 'display:block !important') {
                            		ul.attr('style','display:none !important');
                            	} else {
                            		$('ul.third-level').each(function(){
                            			$(this).attr('style','display:none !important');
                            		});
                            		
                            		ul.attr('style','display:block !important');
                            	}
                            });
                            
                            $('li.dropdown.browseby a').on('click', function (event) {
                            	var li = $(this).parent();
                            	
                            	if(!li.hasClass("open")){
                            		li.addClass("open");
                            	} else if($(this).hasClass('dropdown-toggle')){
                            		li.removeClass("open");
                            	}
                            });

                            $('body').on('click', function (e) {
                                if (!$('li.dropdown.browseby').is(e.target) && $('li.dropdown.browseby').has(e.target).length === 0 && $('.open').has(e.target).length === 0) {
                                    $('li.dropdown.browseby').removeClass('open');
                                }
                            });
                    	}
                    
                        $rootScope.$on('$stateChangeStart', function () {
                            $http.get( requestFactory.getUrl( 'getCategoryForNav' ), {}, {
                                headers : requestFactory.getHeaders()
                            } ).then( function ( response ) {
                                $rootScope.categoriesList = response.data.response;
                                if(response.data.live){
                                $rootScope.notificationCount = response.data.live.notificationCount;
                                var temp = ($rootScope.countdownlive)?$rootScope.countdownlive:null;
                                $rootScope.countdownlive = response.data.live;
                                $rootScope.countdownlive.slug = (temp!==null)?temp.slug:$rootScope.countdownlive.slug;
                                var clock = $('.countdown').FlipClock($rootScope.countdownlive.timer, {
                                    clockFace: 'DailyCounter',
                                    countdown: true
                                });
                                }
                            } );
                        });
                    }
                }
            }
    ] ).directive( 'forceLoad',  function () {
        return {
            restrict : 'A',
            link : function ( $scope,elem,attr ) {
                $(elem).bind('click',function(e){
                    e.preventDefault();
                    window.location.href=$(elem).attr('href');
                })
            }
        }
    }).directive( 'forcesLoad',  function () {
        return {
            restrict : 'A',
            link : function ( $scope,elem,attr ) {
                $(elem).bind('click',function(e){
                    e.preventDefault();
                    alert('Payment Gateway not configured.');
                    //window.location.href=$(elem).attr('href');
                })
            }
        }
    }).directive( 'scrollToptag',  function () {
        return {
            restrict : 'A',
            link : function ( $scope,elem,attr ) {           
                $(elem).bind('click',function(e){
                    setTimeout(function(){
                        if(angular.element(elem).hasClass('active')){
                        $("html, body").animate({ scrollTop: 0 }, 1000);
                        }
                    }, 1000);})}
        }
    }).directive( 'logOut',  function () {
        return {
            restrict : 'A',
            link : function ( $scope,elem,attr ) {
                $(elem).bind('click',function(e){
                    e.preventDefault();
                    if(confirm("Are you sure do you want to Logout ?")){
                        window.location.href=$(elem).attr('href');
                    }
                })
            }
        }
    }).directive( 'slideshowhide',  function () {
            return {
                restrict : 'A',
                link : function ( $scope,elem ) {
                    $scope.$watch('countdownlive',function(live) { if(live && (typeof (live.slug) === 'string') && live.slug!==''){
                        $(elem).stop(true, true).delay(200).slideDown(300);
                    }else{
                        $(elem).stop(true, true).delay(200).slideUp(300);
                    }});
                    elem.find('#close-tpabel').click(function(){if($scope.countdownlive.slug == ''){
                        $(elem).stop(true, true).delay(200).slideUp(300);
                    }})
                }
            }
        }).directive( 'scrollfinder',  function () {
            return {
                restrict : 'A',
                link : function ( $scope,elem ) {
                    if($scope.$last){$('.scrollfinderrelated').mCustomScrollbar('update');
                        setTimeout(function(){
                            if(document.querySelector('div.media.active')!==null){
                            var off= document.querySelector('div.media.active').offsetTop;
                                $('.scrollfinderrelated').mCustomScrollbar("scrollTo", off, {
                                    scrollEasing:"easeOut",
                                    scrollInertia: 180
                                });
                            }
                        }, 500);
                    }
                }
            }
        }).directive( 'hoveractive', [
        '$http', '$rootScope', 'requestFactory', function ( $http, $rootScope, requestFactory ) {
            return {
                restrict : 'C',
                link : function ( $scope ) {
                	if($scope.$last && $(window).width() > 990){
                		$('.hoveractive').hover(function() {
	                         $('.hoveractive').removeClass('menuactiveli');
	                      	 $(this).addClass('menuactiveli');
                		});
                	}
                }
            }
        }
] ).directive( 'suggestedSearches',  function () {
        return {
            restrict : 'C',
            replace : true,
            link : function ( $scope,elem,attr ) {
                $scope.$watchGroup(['categoryFilter','tagsFilter','showmorefilterscattags'], function(newValues, oldValues, $scope) {
                    if($scope.showmorefilterscattags == 1){
                        $(elem).css('max-height','130px');
                    }else{
                        $(elem).css('max-height','inherit');
                    }
                    if($(elem).children('div').height()>$(elem).height()){
                        $('.cs-result-more').attr('style','display:block');
                    }else{
                        $('.cs-result-more').attr('style','display:none !important');
                    }
                });
            }
        }
}).directive( 'catNameRef',  function () {
    return {
        restrict : 'C',
        replace : true,
        link : function ( $scope,elem,attr ) {
            var elems = '.suggested-searches';
            if($(elems).children('div').height()>$(elems).height()){
                $('.cs-result-more').attr('style','display:block');
            }else{
                $('.cs-result-more').attr('style','display:none !important');
            }
        }
    }
}).directive( 'datetimePicker', [
    '$http', '$rootScope', 'requestFactory', function ( $http, $rootScope, requestFactory ) {
        return {
            restrict : 'A',
            link : function ( $scope,elem,attr ) {
              
                $(elem).datepicker({format:"dd-mm-yyyy",endDate: '+0d',"setDate": elem.val(),
                    viewMode: 'years',autoclose: true});
                $('.input-group.date').find('.input-group-addon').click(function(){
                    $(this).parent().find('input[type="text"][datetime-picker]').focus();
                });
            }
        }
    }
] ).directive( 'searchRoot', [
        '$rootScope','$state','requestFactory', function ( $rootScope,$state,requestFactory ) {
            return {
                restrict : 'A',
                replace : true,
                scope : {
                    list : '='
                },
                link : function ( $scope,elem,attr ) {
                    elem.bind('submit',function(){
                        if(elem.find('input[type="text"][list="searchsuggestions"]').val()){
                            $scope.$emit("submitRootForm", "submiting");
                        }
                    })
                },
                controller : function($scope,$rootScope,$state){
                    if($state.current.name !== 'categoryvideos'){
                        $rootScope.fields.search = '';
                        $rootScope.searchsuggesionlist = [];
                    }
                    $scope.$on("submitRootForm", function (evt, data) {
                        if($state.current.name === 'categoryvideos'){
                            $rootScope.filter();
                        }else{
                            $state.go('categoryvideos',{'slug':''},{reload:true});
                        }
                    });
                    $scope.$on("getSuggestionsForVideoSearch", function (evt, data) {
                        $rootScope.searchsuggesionlist = [];
                        if(data.value){}else{
                            $rootScope.searchsuggesionlist = [];
                        }
                    });
                }
            }
        }
] ).directive( 'mobileToggle', [
    '$rootScope', function ( $state ) {
        return {
            restrict : 'A',
            replace : true,
            scope : {
            	setclass : '='
            },
            link : function ( $scope,elem,attr ) {
            	  $(elem).on('click', function() {
            	    $('.'+attr.setclass).toggleClass('actives');
            	  });
            }
        }
    }
] ).directive('customScroll', function ($log) {
    return {
        restrict: 'A',
        scope: {
            config: '&customScroll'
        },
        link: function postLink(scope, iElement, iAttrs, controller, transcludeFn) {
            var config = scope.config();
            // create scroll elemnt
            var elem = iElement.mCustomScrollbar({
                autoHideScrollbar: (config.autoHide)?config.autoHide:false,
                theme: 'dark-thin',
                advanced: {
                    updateOnImageLoad: true
                },
                scrollButtons: {
                    enable: false
                }
            });
            // the live options object
            var mObject = elem.data('mCS');
        }
    };
}).directive('img', function(requestFactory) {
    return {
        restrict :'E',
          link: function(scope, element, attrs) {
             var defaultUrl = requestFactory.s3bucketurl;
            element.bind('error', function() {
              if (attrs.src != defaultUrl) {
                attrs.$set('src', (attrs.errSrc)?attrs.errSrc:defaultUrl);
              }
            });
            
            attrs.$observe('ngSrc', function(value) {
              if (attrs.src != defaultUrl) {
                attrs.$set('src', (attrs.errSrc)?attrs.errSrc:defaultUrl);
              }
            });
          }
        }
      }).directive( 'initFlowPlayer', [
    '$rootScope','$state','requestFactory', function ( $rootScope, $state,requestFactory ) {
        return {
            restrict : 'A',
            replace : true,
            scope : {
                video : '='
            },
            link : function ( $scope,elem,attr ) {
            $('.scrollfinderrelated').mCustomScrollbar('update');
                setTimeout(function(){
                    if(document.querySelector('div.media.active')!==null){
                    var off= document.querySelector('div.media.active').offsetTop;
                        $('.scrollfinderrelated').mCustomScrollbar("scrollTo", off, {
                            scrollEasing:"easeOut",
                            scrollInertia: 180
                        });
                    }
                }, 500);
                var video =  $scope.video;
                var videoplaytriggerapi = true;
                var timer,ct = 0;
                if(video.username){

                    flowplayer(elem, {
                        adaptiveRatio:true,
                        splash: false,
                        width: "100%",
                        duration: 2,
                        seekable: true,
                        keyboard:true,
                        poster : video.selected_thumb,
                        volume : 0.2,
                        speeds : [0.5, 1.0,1.25, 1.5, 2.0],
                        embed : true,
                        twitter: true,
                        share :true,
                        autoplay: true,
                        facebook :true,
                        live:1,
                        logo :requestFactory.getBaseTemplateUrl()+'/contus/base/images/logo-xs.png',
                        // optional: HLS levels offered for manual selection
                        hlsQualities: true,
                        clip: {
                            title: video.title, // updated on ready
                            sources: [{'type': "application/x-mpegurl",'src':video.hls_playlist_url}]
                        }
                    });
                    }else{
                	
                flowplayer(elem, {
                    adaptiveRatio:true,
                    splash: false,
                    width: "100%",
                    duration: 2,
                    seekable: true,
                    keyboard:true,
                    poster : video.selected_thumb,
                    volume : 0.2,
                    speeds : [0.5, 1.0, 1.25, 1.5, 2.0],
                    embed : false,
                    twitter: false,
                    share :false,
                    autoplay: true,
                    facebook :false,
                    analytics:document.querySelector('meta[name="ganalticsid"]').content,
                    key:'$130615278724628',
                    logo :requestFactory.getBaseTemplateUrl()+'/contus/base/images/logo-xs.png',
                    ads: [{
                        // pre-roll ...
                        time: 0,
                        // ... of type skippablevideo
                        ad_type: "skippablevideo",
                        // maximum trueview duration
                        sdmax: 40000
                      }],
                      adtest: true,
                      description_url: location.href,
                    // optional: HLS levels offered for manual selection
                    hlsQualities: true,
                    clip: {
                        title: video.title, // updated on ready
                        sources: [{'type': "application/x-mpegurl",'src':video.hls_playlist_url}]
                    }
                }).on("resume", function (e,api) {
                    if(video.is_demo){
                        $(".fp-buffer, .fp-progress", elem).on("mousedown touchstart", function (e) {
                            e.stopPropagation();
                         });
                        $(".fp-timeline", elem).unbind("mousedown touchstart click");
                        $(".fp-timeline.fp-bar", elem).on("click mousedown touchstart", function (e) {
                            api.unload();
                            $scope.video.isSubscription = true;
                            $scope.loading = true;
                            setTimeout(function () {
                                $scope.$apply(function(){
                                    $scope.loading = false;
                                });
                            }, 500);
                        });
                        $(elem).removeClass("is-touch");
                    }
                    if(videoplaytriggerapi){
                        $scope.$emit("videoResumeing", 'triggering');
                    }
                    videoplaytriggerapi = false;
                }).on('beforeseek',function(e,api){
                    if(video.is_demo){
                        if(api.video.time>60){
                            api.unload();
                        }
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }).on("seek", function (e, api) {
                    if (video.is_demo) {
                        if(api.video.time>60){
                            api.unload();
                        }
                        e.preventDefault();
                        e.stopPropagation();
                        if(! api.video.time>60){
                            api.seek(api.video.time);
                        }
                      }
                    }).on('progress',function(e,api){
                    if((video.is_demo) && (api.video.time>60)){
                        api.unload();
                        $scope.video.isSubscription = true;
                        $scope.loading = true;
                        setTimeout(function () {
                            $scope.$apply(function(){
                                $scope.loading = false;
                            });
                        }, 500);
                    }
                });
                }
                }
            }
    }
] ).directive('initTwitter', function ($log) {
    return {
        restrict: 'A',
        link:function ( $scope,elem,attr ) {
            var title = attr.title;
            if ( attr.hasOwnProperty( 'title' ) ) {
                title = angular.isObject( $scope[ attr.title ] ) ? $scope[ attr.title ] : attr.title;
            }
            var url = attr.url;
            if ( attr.hasOwnProperty( 'url' ) ) {
                url = angular.isObject( $scope[ attr.url ] ) ? $scope[ attr.url ] : attr.url;
            }
            var description  =  ((title) && (url))?'http://twitter.com/intent/tweet?text=' +title +'@'+ url:'javascript:;';
            $(elem).attr('href', description);
            $(elem).attr('target','_blank');
        }
    };
}).directive('initFavourite', function (requestFactory,ngToast) {
    return {
        restrict: 'A',
        link:function ( $scope,elem,attr) {
            if($scope.video.authfavourites !== undefined){
                $scope.video.is_favourite =  ($scope.video.authfavourites.length)?1:0;
            }
            $( elem ).bind( 'click', function () {if ( $scope.video.is_favourite === 1 ) {
                requestFactory.put( requestFactory.getUrl( 'favourite' ), {'video_slug' : $scope.video.slug}, function ( success ) {
                    ngToast.create( {className : 'success',content : '<strong>' + success.message + '</strong>'} );
                }, function(error){} );
            } else {
                requestFactory.post( requestFactory.getUrl( 'favourite' ), {'video_slug' : $scope.video.slug}, function ( success ) {
                    ngToast.create( {className : 'success',content : '<strong>' + success.message + '</strong>'} );
                }, function(error){} );
            }
            $scope.video.is_favourite = ( $scope.video.is_favourite === 1 )?0:1;
            $scope.$apply();
            });
        }
    };
}).directive('csDwnIcons', function ($rootScope,ngToast) {
    return {
        restrict: 'C',
        link:function ( $scope,elem,attr) {
        	$( elem ).bind( 'click', function () {
        	var elem = "";
        	if($scope.video.is_demo == '0'){
        	var mp3 = word = pdf = 'javascript:;';
        	var mp3download = pdfdownload = worddownload = "data-ng-click='$root.notavailables()'";
        	var mp3disabled = worddisabled = pdfdisabled = ' available';
        	if($scope.video.mp3 != "" && $scope.video.mp3 != null && $scope.video.mp3 != undefined){
        		mp3 = $scope.video.mp3;
        		mp3download = "download";
        		mp3disabled = "";
        	}
        	if($scope.video.pdf != "" && $scope.video.pdf != null && $scope.video.pdf != undefined){
        		pdf = $scope.video.pdf;
        		pdfdownload = "download";
        		pdfdisabled = "";
        	}
        	if($scope.video.word != "" && $scope.video.word != null && $scope.video.word != undefined){
        		word = $scope.video.word;
        		worddownload = "download";
        		worddisabled = "";
        	}
        	    elem += '<div class="modal-header login-title"><a href="javascript:;" class="close close-btn" data-dismiss="modal" aria-label="Close" data-ng-click="$root.closeDownload()"> </a><h4 class="modal-title text-center" id="">Download</h4></div>';
        		elem +='<div class="modal-body form-content"><div class="panel-heading">Click below icons to download.</div><div class="cs-dwd-options ">';
        			elem +='<a title="AUDIO" href="'+mp3+'" '+mp3download+' title="" class="green'+mp3disabled+'"><i class="icons-wd"></i><span>Audio</span></a></div>';
        			elem +='<div class="cs-dwd-options "><a title="PDF" href="'+pdf+'" '+pdfdownload+' title="" class="blue'+pdfdisabled+'"> <i class="icons-pd"></i> <span>PDF</span></a></div><div class="cs-dwd-options ">';
        			elem += '<a title="WORD" href="'+word+'" '+worddownload+' title="" class="red'+worddisabled+'">'; 
        			elem +='<i class="icons-ad"></i><span>word</span></a></div></div>';
        	}
        	else
        	{ 
        		elem += '<div class="modal-header login-title"><a href="javascript:;" class="close close-btn" data-dismiss="modal" aria-label="Close" data-ng-click="$root.closeDownload()"> </a><h4 class="modal-title text-center" id="">Subscription</h4></div>';
        		elem += '<div class="panel panel-default details-subscription">';
        		elem += '<div class="panel-heading" >';
        		elem += '<span class="validation-text"> <div class="panel-heading" >Get access to all Videos.';
        		elem += '</div></span></div>';
        		elem += '<div class="panel-body text-center">';
        		elem += ' <ul class="clearfix new-member-action">';
        		$.each($rootScope.subscriptions, function(index, value) {
        		    elem += '<li>';
            		elem += '  <strong class="name-card">'+value.name+'</strong>';
            		elem += '<strong class="prices-oranges"><i class="fa fa-inr"></i> '+value.amount+'</strong>';
            		elem += '   <span class="video-valid-text">'+value.duration+' days</span>';
            		elem += '  </li>';
        		});
        		
        		elem += ' </ul>';
        		elem += ' <a title="Subscribe Now to Download" ui-sref="subscribeinfo" class="btn btn-green ripple full-btn" ng-click="subscribe(randsub.slug)">Subscribe Now to Download</a>';
        		elem += '  </div>';
        		elem += '</div>';
        	}
        	$rootScope.documentDownload(elem);
            });
        }
    };
});

    angular.module( "app.filter" ).filter( 'getCount', function () {
        return function ( obj ) {
            if ( obj ) {
                return Object.keys( obj ).length;
            }
        };
    } ).filter( 'convertDate', function () {
        return function ( obj ) {
            if ( obj ) {
                var date = obj,
                values = date.split(/[^0-9]/),
                year = parseInt(values[0], 10),
                month = parseInt(values[1], 10) - 1, // Month is zero based,
                                                        // so subtract 1
                day = parseInt(values[2], 10),
                hours = parseInt(values[3], 10),
                minutes = parseInt(values[4], 10),
                seconds = parseInt(values[5], 10);
            return new Date(year, month, day, hours, minutes, seconds).getTime();
            }
        };
    } ).filter( 'convertAgoTime', function () {
        return function ( obj ) {
            if ( obj ) {
                var time = new Date().getTime();
                time = parseInt(time/1000) - parseInt(obj/1000);
                time = ( time < 1 ) ? 1 : time;
                var tokens = [
                        {'id' :'31536000' ,'text': 'year'},
                        {'id' :'2592000','text': 'month'},
                        {'id' :'604800','text' : 'week'},
                        {'id' : '86400','text' : 'day'},
                        {'id' :'3600','text': 'hour'},
                        {'id' :'60','text' : 'minute'},
                        {'id' :'1','text' : 'second'}
                ];
                
                for ( var unit in tokens ) {
                    var text = tokens[ unit ].text;
                    unit = parseInt( tokens[ unit ].id );
                    if ( time < unit ) {
                        continue;
                    }
                    var numberOfUnits = Math.floor( time / unit );
                    return numberOfUnits + ' ' + text + ( ( numberOfUnits > 1 ) ? 's' : '' ) + ' ago';
                }
            }
        };
    } ).filter( 'getByKey', function () {
        return function ( input, id, column, data, field ) {
            var column = angular.isUndefined( column ) ? 'id' : column;
            var i = 0;
            if ( angular.isArray( input ) ) {
                for ( ; i < input.length; i++ ) {
                    if ( input[ i ] && input[ i ][ column ] == id ) {
                        return ( data == 'key' ) ? i : ( field ) ? input[ i ][ field ] : input[ i ];
                    }
                }
            } else {
                var result = null;
                angular.forEach( input, function ( val, key ) {
                    if ( val[ column ] == id ) {
                        result = ( data == 'key' ) ? key : val;
                    }
                } );
                return result;
            }
            return null;
        };
    } ).filter( 'getByKeyInner', function () {
        return function ( input, id,innerChild, column, data, field ) {
            var column = angular.isUndefined( column ) ? 'id' : column;
            var i = 0;
            if ( angular.isArray( input ) ) {
                for ( ; i < input.length; i++ ) {
                    var j = 0;
                    if ( angular.isArray( input[i][innerChild] ) ) {
                        for ( ; i < input[i][innerChild].length; j++ ) {
                            if ( input[i][innerChild][j] && input[i][innerChild][ j ][ column ] == id ) {
                                return ( data == 'key' ) ? i : ( field ) ? input[i][innerChild][ j ][ field ] : input[i][innerChild][ j ];
                            }
                        }
                    }
                }
            }
        };
    } );
    ;
} )();

