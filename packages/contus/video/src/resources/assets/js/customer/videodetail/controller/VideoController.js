( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'VideoDetailController', ['$http','$sce','$filter','$rootScope','requestFactory','$stateParams','$scope','ngToast','data','relateddata','comments','$state',function ( $http, $sce, $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, relateddata, comments, $state ) {
        var successResponseData;
        $scope.showmoretext = 1;
        $scope.pCommentStatus = 0;
        $scope.PostQuestionstatus = 0;
        var dataBinder = function ( response ) { 
            $scope.subscription = response.subscription;
            $scope.tags = response.videos.tags;
            $scope.category = response.videos.categories [0];
            $scope.videos = response.videos;
            $scope.likescount = response.likescount;
            $scope.likestatus = response.likestatus;
            $scope.dislikescount = response.dislikescount;
            $scope.watchlaterstatus = response.watchlater;
            $scope.exam = response.exam;
            $scope.show_video_description = $scope.videos.short_description;
            $scope.randsub = $scope.subscription.data [Math.floor( ( Math.random() * $scope.subscription.data.length ) + 0 )];
            $scope.audioDownload = {link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',size : '12.7MB'};
            $scope.pdfDownload = {link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',size : '21.8MB'};
            $scope.docDownload = {link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',size : '34.7MB'};
            if ( angular.isDefined( $rootScope.myPlayer ) ) {
                $rootScope.myPlayer.dispose();
            }
            $scope.randsub = $scope.subscription.data [Math.floor( ( Math.random() * 3 ) + 0 )];
        };
        $scope.passclass = function ( pass ) {
            if ( $stateParams.video == pass ) {
                return true;
            } else if ( $stateParams.slug == pass ) {
                return true;
            } else {
                return false;
            }
        }
        $scope.passroute = function ( pass ) {
            if ( $scope.passclass( pass ) ) {
                return false;
            }
            if ( angular.isObject( $scope.live ) && $scope.live.total ) {
                $state.go( 'liveDetail', {slug : pass}, {reload : true} );
            } else {
                $state.go( 'videoDetail', {slug : pass}, {reload : true} );
            }
        }
        $scope.related = [];
        $scope.comment = [];

        $scope.stateparams = $stateParams;
        var dataBinderrelated = function ( response ) {
            if ( angular.isObject( response.playlist ) ) {
                $scope.playlist = response.playlist;
                response = response.videos;
            } else if ( angular.isObject( response.all_live_videos ) ) {
                $scope.live = response.all_live_videos;
                response = response.all_live_videos;
            }
            $scope.related = response;
            setTimeout( function () {
                $( '.scrollfinderrelated' ).mCustomScrollbar( 'update' );
                setTimeout( function () {
                    if ( document.querySelector( 'div.media.active' ) !== null ) {
                        var off = document.querySelector( 'div.media.active' ).offsetTop;
                        $( '.scrollfinderrelated' ).mCustomScrollbar( "scrollTo", off, {scrollEasing : "easeOut",scrollInertia : 180} );
                    }
                }, 500 );
            }, 500 );
        };
        var dataBinderComments = function ( response ) {
            $scope.comment = response;
        }
        $scope.toggleDescription = function () {
            $scope.showmoretext = !$scope.showmoretext;
            $scope.show_video_description = ( $scope.show_video_description == $scope.videos.short_description ) ? $scope.videos.description : $scope.videos.short_description;
        };
         $scope.postparentcomment = function ( val ) {
            if ( val !== '' && val !== undefined ) {
                var data = {comment : val};
                PostComment( data );
                $scope.parentcomment = "";
            }
        }  
        $scope.postchildcomment = function ( id, comment ) {
            if ( comment !== '' && comment !== undefined ) {
                var data = {comment : comment,parent_id : id};
                PostComment( data );
                angular.element( '.childcomment' ).value = "";
            }
        } 
        $scope.videolike = function(id,type){
            if($scope.likestatus != null){if( $scope.likestatus.dislike_count == 1 && type == 'dislike'){
                return false;
            }}
            if($scope.likestatus != null){if($scope.likestatus.like_count == 1  && type == 'like'){
                return false;
            }}
            if ( id !== '' && id !== undefined ) {
                var data = {video_id : id,type:type};
                VideoLike( data );
            }
        }
        $scope.watchlater = function(id){ 
            if($scope.watchlaterstatus){
                return false;
            }
            if ( id !== '' && id !== undefined ) {
                var data = {video_id : id};
                WatchLater( data );
            }
        }
        dataBinder( data.data.response );
        dataBinderrelated( relateddata.data.response );
        dataBinderComments( comments.data.response );

        
        var commentsuccess = function ( response ) {
            dataBinderComments( response.response );
            ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
            angular.element( 'textarea' ).val( '' );
            setTimeout( function () {
                $( 'button.btn-cancel[type="button"][ng-click="parentcomment=\'\'"]' ).trigger( 'click' );
            }, 500 );
        }
        var commentfail = function ( response ) {
            ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

        }
        var commenturl = requestFactory.getUrl( 'videos/comments/' + $scope.videos.slug );
        var PostComment = function ( data ) {
            requestFactory.post( commenturl, data, commentsuccess, commentfail );
        }
        var dataBinderLikes = function ( response ) {
            $scope.likescount = response.likescount;
            $scope.likestatus = response.likestatus;
            $scope.dislikescount = response.dislikescount;
        }
        var dataBinderWatchlater = function ( response ) {
            $scope.watchlaterstatus = response.watchlater;
        }
        var likesuccess = function ( response ) {
            dataBinderLikes( response.response );
            ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
            
        }
        var likefail = function ( response ) {
            ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

        }
        var watchlatersuccess = function ( response ) {
            dataBinderWatchlater( response.response );
            ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
            
        }
        var watchlaterfail = function ( response ) {
            ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

        }
        var videolikeurl = requestFactory.getUrl( 'videos/videolike/' + $scope.videos.slug );
        var VideoLike = function ( data ) {
            requestFactory.post( videolikeurl, data, likesuccess, likefail );
        }     
        var watchlaterurl = requestFactory.getUrl( 'videos/watchlater/' + $scope.videos.slug );
        var WatchLater = function ( data ) {
            requestFactory.post( watchlaterurl, data, watchlatersuccess, watchlaterfail );
        }  
        var success = function ( success ) {
            dataBinderrelated( success.response );
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.toggleSelectionTags = function ( tog ) {
            sessionStorage.tag = tog;
            $state.go( 'categoryvideos', {'slug' : $scope.category.parent_category.parent_category.slug}, {reload : true} )
        };
        $scope.loadmorerelatedcategory = function () {
            if ( $scope.related.next_page_url !== null ) {
                $http( {method : 'POST',url : $scope.related.next_page_url,headers : requestFactory.getHeaders(),data : {},ignoreLoadingBar: true} ).then( function ( r ) {
                    dataBinderrelated( r.data.response );
                }, function () {
                } );
            }
        }

        $scope.loadmorerelatedcategory();
        $http( {method : 'get',url : requestFactory.getUrl( 'recommended' ),headers : requestFactory.getHeaders(),ignoreLoadingBar: true} ).then( function ( r ) {
            $scope.recommended = r.data.response;
        }, function () {
        } );
    }] )

} )();