( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'ExamListContoller', ['$http','requestFactory','data','$state','$stateParams','$scope','$rootScope',function ( $http, requestFactory, data, $state, $stateParams, $scope, $rootScope ) {
        $rootScope.httpCount = $rootScope.httpCount + 1;
        $scope.related = {data : null};
        // $scope.recommended = recommended.data.response;
        $scope.slug = $stateParams.slug;
        var dataBinderrelated = function ( response ) {
            if ( response.playlist ) {
                $scope.playlist = response.playlist;
                response = response.videos;
            }
            var temp = null;
            temp = $scope.related.data;
            $scope.related = response;
            $scope.group = response.group_id;
            $scope.related.data = $scope.related.data;
            $scope.category = response.category;
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
        $scope.toggleSelectionTags = function ( tog ) {
            sessionStorage.tag = tog;
            $state.go( 'categoryvideos', {'slug' : $scope.video.categories [0].parent_category.parent_category.slug}, {reload : true} )
        };
        $scope.$on( "getFromDetail", function ( evt, data ) {
            $scope.tags = data.video.tags;
            $scope.video = data.video;
            $scope.videos = data.video;
            $scope.subscription = data.subscription;
            $scope.randsub = $scope.subscription.data [Math.floor( ( Math.random() * $scope.subscription.data.length ) + 0 )];
        } );
        dataBinderrelated( data.data.response );
        $scope.loadmorerelatedcategory = function () {
            if ( $scope.related.next_page_url !== null ) {
                if ( $scope.playlist ) {
                    $http( {method : 'post',url : $scope.related.next_page_url,headers : requestFactory.getHeaders(),data : {},ignoreLoadingBar: true} ).then( function ( r ) {
                        dataBinderrelated( r.data.response );
                    }, function () {
                    } );
                } else {
                    $http( {method : 'get',url : $scope.related.next_page_url,headers : requestFactory.getHeaders(),ignoreLoadingBar: true} ).then( function ( r ) {
                        dataBinderrelated( r.data.response );
                    }, function () {
                    } );
                }
            }
        }
        $scope.loadmorerelatedcategory();
        var params = "";
        if(typeof($scope.group) != "undefined" && $scope.group!== null) {        
        	params = '?group='+$scope.group.slug+"&exam="+$scope.group.exams.slug;
        }
        $http( {method : 'get',url : requestFactory.getUrl( 'recommended'+params ),headers : requestFactory.getHeaders(),ignoreLoadingBar: true} ).then( function ( r ) {
            $scope.recommended = r.data.response;
        }, function () {
        } );
        if ( $state.current.name === 'examList' ) {
            $state.go( 'examdetail', {'slug' : $stateParams.slug,'video' : data.data.response.data [0].slug}, {reload : true} )
        } else if ( $state.current.name === 'playlistList' ) {
            $state.go( 'playlistdetail', {'slug' : $stateParams.slug,'video' : data.data.response.videos.data [0].slug}, {reload : true} )
        }
    }] )

} )();