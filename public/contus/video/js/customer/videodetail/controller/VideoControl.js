( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'VideoDetailControl', ['$http','$sce','$filter','$rootScope','requestFactory','$stateParams','$scope','ngToast','data','$state',function ( $http, $sce, $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, $state ) {
        var successResponseData;
        $rootScope.httpCount = 0;
        $rootScope.stateParams = $stateParams;
        $scope.showmoretext = 1;
        $scope.pCommentStatus = 0;
        $scope.PostQuestionstatus = 0;
        var dataBinder = function ( response ) {
            $scope.subscription = response.subscription;
            $scope.category = response.videos.categories [0];
            $scope.$emit( 'getFromDetail', {'video' : response.videos,'subscription' : response.subscription} );
            $scope.videos = response.videos;
            $scope.likescount = response.likescount;
            $scope.likestatus = response.likestatus;
            $scope.dislikescount = response.dislikescount;
            $scope.watchlaterstatus = response.watchlater;
            $scope.videos.isSubscription = false;
            if ( response.pdfsize ) {
                $scope.pdfsize = response.pdfsize;
            }
            if ( response.mp3size ) {
                $scope.mp3size = response.mp3size;
            }
            if ( response.wordsize ) {
                $scope.wordsize = response.wordsize;
            }

            $scope.exam = response.exam;
            $scope.show_video_description = '';
            if ( angular.isDefined( $rootScope.myPlayer ) ) {
                $rootScope.myPlayer.dispose();
            }
            $scope.randsub = $scope.subscription.data [Math.floor( ( Math.random() * $scope.subscription.data.length ) + 0 )];
            $scope.youtubeVideo = $sce.trustAsResourceUrl( 'https://www.youtube.com/embed/' + $scope.videos.youtube_id + '?rel=0&showinfo=0' );
        };
        $scope.notavailable = function ( isdemo ) {
            if ( isdemo ) {
                ngToast.create( {className : 'success',content : '<strong>Please Subscribe to access the files</strong>'} );
            } else {
                ngToast.create( {className : 'success',content : '<strong>Files not available at this moment </strong>'} );
            }
        }
        $scope.callback = function ( response ) {
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
            } else if ( angular.isObject( $scope.playlist ) && $scope.playlist.slug ) {
                $state.go( 'playlistdetail', {slug : $stateParams.slug,video : pass}, {reload : true} );
            } else {
                $state.go( 'videoDetail', {slug : pass}, {reload : true} );
            }
        }
        $scope.related = [];
        $scope.comment = [];
        $scope.question = [];

        $scope.stateparams = $stateParams;
        var dataBinderrelated = function ( response ) {
            if ( angular.isObject( response.playlist ) ) {
                $scope.playlist = response.playlist;
                response = response.videos;
            } else if ( angular.isObject( response.all_live_videos ) ) {
                $scope.live = response.all_live_videos;
                response = response.all_live_videos;
            }
            $scope.youtubeVideo = $sce.trustAsResourceUrl( 'https://www.youtube.com/embed/' + $scope.videos.youtube_id + '?rel=0&showinfo=0' );
            var temp = {};
            temp = $scope.related.data;
            $scope.related = response;
            $scope.related.data = ( temp ) ? temp.concat( $scope.related.data ) : $scope.related.data;
        };
        var dataBinderComments = function ( response ) {
            $scope.comment = response;
        }
        var dataBinderQuestions = function ( response ) {
            $scope.question = response;
        }

        $scope.toggleDescription = function () {
            $scope.showmoretext = !$scope.showmoretext;
            $scope.show_video_description = ( $scope.show_video_description == '' ) ? $scope.videos.description : '';
        };
        dataBinder( data.data.response );
        $scope.postparentcomment = function ( val ) {
            if ( !$scope.pCommentStatus ) {
                if ( val !== '' && val !== undefined ) {
                    var data = {comment : val};
                    PostComment( data );
                    $scope.parentcomment = "";
                }
            }
        }
        $scope.postparentquestion = function ( val ) {
            if ( val !== '' && val !== undefined ) {
                var data = {question : val};
                PostQuestion( data );
                $scope.parentquestion = "";
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
        $scope.postchildquestion = function ( id, question ) {

            if ( question !== '' ) {
                var data = {question : question,parent_id : id};
                PostQuestion( data );
                angular.element( '.childquestion' ).value = "";
            }
        }
        var fetchsuccess = function ( response ) {
            dataBinderComments( response.response );
        }

        var commentsuccess = function ( response ) {
            dataBinderComments( response.response );
            ngToast.create( {className : 'success',content : '<strong>' + response.message + '</strong>'} );
            angular.element( 'textarea' ).val( '' );
            setTimeout( function () {
                $( 'button.btn-cancel[type="button"][ng-click="parentcomment=\'\'"]' ).trigger( 'click' );
                $scope.pCommentStatus = 0;
            }, 500 );
        }

        var questionsuccess = function ( response ) {
            dataBinderQuestions( response.response );
            ngToast.create( {className : 'success',content : '<strong>' + "Your question has been posted successfully and waitnig for admin approval" + '</strong>'} );
            angular.element( 'textarea' ).val( '' );
            setTimeout( function () {
                $( 'button.btn-cancel[type="button"][ng-click="parentquestion=\'\'"]' ).trigger( 'click' );
                $scope.PostQuestionstatus = 0;
            }, 500 );
        }

        var questionloadsuccess = function ( response ) {
            dataBinderQuestions( response.response );
        }

        var commentfail = function ( response ) {
            $scope.pCommentStatus = 0;
            ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

        }
        var questionfail = function ( response ) {
            $scope.PostQuestionstatus = 0;
            ngToast.create( {className : 'danger',content : '<strong>' + response.message + '</strong>'} );

        }
        var commenturl = requestFactory.getUrl( 'videos/comments/' + $scope.videos.slug );
        var PostComment = function ( data ) {
            if ( !$scope.pCommentStatus ) {
                $scope.pCommentStatus = 1;
                requestFactory.post( commenturl, data, commentsuccess, commentfail );
            }
        }
        var questionurl = requestFactory.getUrl( 'videos/qa/' + $scope.videos.slug );
        var PostQuestion = function ( data ) {
            if ( !$scope.PostQuestionstatus ) {
                $scope.PostQuestionstatus = 1;
                requestFactory.post( questionurl, data, questionsuccess, questionfail );
            }
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
        $scope.loadprevcomment = function () {
            commenturl = $scope.comment.prev_page_url;
            requestFactory.post( commenturl, {}, fetchsuccess, commentfail );
        }
        $scope.loadprevquestion = function () {
            questionurl = $scope.question.prev_page_url;
            requestFactory.post( questionurl, {}, questionloadsuccess, questionfail );
        }

        $scope.loadmorecomment = function () {
            commenturl = $scope.comment.next_page_url;
            requestFactory.post( commenturl, {}, fetchsuccess, commentfail );
        }
        $scope.loadmorequestion = function () {
            questionurl = $scope.question.next_page_url;
            requestFactory.post( questionurl, {}, questionloadsuccess, questionfail );
        }

        $scope.addFavorites = function () {
            if ( $scope.videos.is_favourite === 1 ) {
                requestFactory.put( requestFactory.getUrl( 'favourite' ), {'video_slug' : $scope.videos.slug}, function ( success ) {
                    ngToast.create( {className : 'success',content : '<strong>' + success.message + '</strong>'} );
                    $scope.videos.is_favourite = 0;
                }, commentfail );
            } else {
                requestFactory.post( requestFactory.getUrl( 'favourite' ), {'video_slug' : $scope.videos.slug}, function ( success ) {
                    ngToast.create( {className : 'success',content : '<strong>' + success.message + '</strong>'} );
                }, commentfail );
                $scope.videos.is_favourite = 1;
            }
        }
        $scope.getLength = function ( obj ) {
            if ( obj ) {
                return Object.keys( obj ).length;
            }
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

        $scope.$on( "videoResumeing", function ( evt, data ) {
            $http( {method : 'POST',url : requestFactory.getUrl( 'recentlyViewed' ),headers : requestFactory.getHeaders(),data : {'video_id' : $scope.videos.slug},ignoreLoadingBar: true} );
        } );
        $http( {method : 'POST',url : requestFactory.getUrl( 'videos/comments/' + data.data.response.videos.slug ),headers : requestFactory.getHeaders(),data : {},ignoreLoadingBar: true} ).then( function ( r ) {
            fetchsuccess( r.data );
        }, commentfail );// then(fetchsuccess, commentfail);
        $http( {method : 'POST',url : requestFactory.getUrl( 'videos/qa/' + data.data.response.videos.slug ),headers : requestFactory.getHeaders(),data : {},ignoreLoadingBar: true} ).then( function ( r ) {
            questionloadsuccess( r.data );
        }, questionfail );
    }] )

} )();

/**
 * extends string prototype object to get a string with a number of characters 
 * from a string.
 * 
 * @type {Function|*}
 */
String.prototype.trunc = String.prototype.trunc || function ( n ) {

    // this will return a substring and
    // if its larger than 'n' then truncate and append '...' to the string and
    // return it.
    // if its less than 'n' then return the 'string'
    return this.length > n ? this.substr( 0, n - 1 ) + '...' : this.toString();
};