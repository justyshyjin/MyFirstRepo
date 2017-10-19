'use strict';

/*
 * AngularJS Directives Filters Factory functions
 */
( function () {
    
    var factoryCategory = angular.module( "app.routes" );
    factoryCategory.factory( 'requestFactory', requestFactory );
    factoryCategory.directive( 'categoryList', [
            '$http', '$rootScope', 'requestFactory', function ( $http, $rootScope, requestFactory ) {
                return {
                    restrict : 'A',
                    replace : true,
                    scope : {
                        list : '='
                    },
                    link : function ( $scope ) {
                        $scope.$watch( 'list', function ( list ) {
                            $http.get( requestFactory.getUrl( 'getCategoryForNav' ), {}, {
                                headers : requestFactory.getHeaders()
                            } ).then( function ( response ) {
                                $rootScope.categoriesList = response.data.response;
                            } );
                        } );
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
                        $scope.$emit("submitRootForm", "submiting");
                    })
                    elem.find('input.searchBox[type="search"]').bind('keyup',function(evt){
                        var code = (evt.keyCode || evt.which);
                        if(code == 37 || code == 38 || code == 39 || code == 40) {
                        }else if(code == 13){
                            $scope.$emit("submitRootForm", "submiting");
                        }else{
                        $scope.$emit("getSuggestionsForVideoSearch", this);
                        }
                    })
                },
                controller : function($scope,$rootScope,$state){ 
                $rootScope.allcategory = {};
                $rootScope.selectcategory = function(event) { 
                   var elementCount = parseInt($(event.target).prevAll().length);
                    $('.tab-content-inner').css('display','none');
                    $('.tab-content div').eq(elementCount).css('display','block');
                    $(event.target).siblings('a').removeClass('active');
                    $(event.target).addClass('active');
                    }
                   var success = function ( success ) {
                    $rootScope.allcategory = success;                    
                    };
                    var fail = function ( fail ) {
                        return fail;
                    };
                    requestFactory.get( requestFactory.getUrl( 'category' ), success, fail );
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
                        if(data.value){
                        requestFactory.post( requestFactory.getUrl( 'searchRelatedVideos' ),{'search':data.value}, function(success){
                            $rootScope.searchsuggesionlist  = success.response.videos;
                        }, function(fail){
                            $rootScope.searchsuggesionlist = [];
                        } );
                        }else{
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
                list : '='
            },
            link : function ( $scope,elem,attr ) {
                  $(elem).on('click', function() {
                    $('.dashboard-links').toggleClass('actives');
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
                    enable: true
                }
            });
            // the live options object
            var mObject = elem.data('mCS');
        }
    };
}).directive( 'initFlowPlayer', [
    '$rootScope','$state','requestFactory', function ( $rootScope, $state,requestFactory ) {
        return {
            restrict : 'A',
            replace : true,
            scope : {
                video : '='
            },
            link : function ( $scope,elem,attr ) {
                var video =  $scope.video;
                var hls = 'application/x-mpegurl';
                var mp4 = 'video/mp4';
                var webm = 'video/webm';
                var jsonData = {};
                angular.forEach(video.transcodedvideos, function(value, key) {
                      if(value.presets.format == 'mp4'){
                          jsonData['type'] = mp4;
                          jsonData['src'] = value.video_url;
                      }
                      if(value.presets.format == 'hls'){
                          jsonData['type'] = hls;
                          jsonData['src'] = value.video_url;
                      }
                      if(value.presets.format == 'webm'){
                          jsonData['type'] = webm;
                          jsonData['src'] = value.video_url;
                      }                     
                    });
                var videoplaytriggerapi = true;
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
                    logo :{file:requestFactory.getBaseTemplateUrl()+'/contus/base/images/logo.png',link:requestFactory.getBaseTemplateUrl(),position:"bottom-left",hide:"true"},
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
                return Date.parse( obj );
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
    } );
    ;
} )();
