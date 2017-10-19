( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'VideoController', ['$rootScope','requestFactory','$stateParams','$scope','ngToast','data',function ( $rootScope, requestFactory, $stateParams, $scope, ngToast, data ) {
        var nexturl = 0;
        $scope.videoOwlCarouselOptions = {loop : true,nav : true,dots : false,margin : 10,autoplay : true,mouseDrag : true,pagination : true,responsive : {0 : {items : 1},600 : {items : 1},700 : {items : 4},992 : {items : 4,loop : false}}};
        $scope.videos = {};

        $scope.classinchecker = function () {
            angular.forEach( $scope.categories.child_category, function ( subcat ) {
                angular.forEach( subcat.child_category, function ( session ) {
                    if ( $scope.categoryFilter.indexOf( session.slug ) > -1 ) {
                        $scope.classinchecker [subcat.slug] = true;
                    }
                } )
            } )
        };
        var dataBinder = function ( response ) {
            var temp = {};
            temp = $scope.videos.data;
            $scope.categories = response.categories;
            if ( $scope.categories.slug === '' ) {
                $scope.categories = response.categories [0];
            }
            $scope.live_videos = response.live_videos;
            $scope.tags = response.tags;
            $scope.videos = response.videos;
            $rootScope.subscriptions = response.subscription;
            if ( nexturl === 0 ) {
                temp = '';
            }
            $scope.videos.data = ( temp ) ? temp.concat( $scope.videos.data ) : $scope.videos.data;
            $scope.classinchecker();
            sessionStorage.tag = '';
            sessionStorage.category = '';
            sessionStorage.search = '';
            sectionlist();
        };
        var sectionlist = function () {
            $scope.sections = {};
            angular.forEach( $scope.categories.child_category, function ( subcategory, keysubcategory ) {
                angular.forEach( subcategory.child_category, function ( section, key ) {
                    $scope.sections [section.slug] = section.title;
                } );
            } );
        }
        $scope.categoryFilter = ( sessionStorage.category ) ? sessionStorage.category.split( ',' ) : [];
        $scope.tagsFilter = ( sessionStorage.tag ) ? sessionStorage.tag.split( ',' ) : [];

        $scope.live = data.data.response.live_videos;
        dataBinder( data.data.response );
        $scope.getLength = function ( obj ) {
            if ( obj ) {
                return Object.keys( obj ).length;
            }
        }
        var success = function ( success ) {
            dataBinder( success.response );
            nexturl = 0;
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.toggleSelection = function ( tog, togvariable ) {
            var idx = togvariable.indexOf( tog );
            if ( idx > -1 ) {
                togvariable.splice( idx, 1 );
            } else {
                togvariable.push( tog );
            }
            return togvariable;
        };
        $scope.toggleSelectionTags = function ( tog ) {
            $scope.tagsFilter = $scope.toggleSelection( tog, $scope.tagsFilter );
            $rootScope.filter();
        };
        $scope.clearAllFilters = function () {
            $scope.categoryFilter = [];
            $scope.tagsFilter = [];
            $rootScope.filter();
        };
        $scope.toggleSelectionCategory = function ( tog ) {
            $scope.categoryFilter = $scope.toggleSelection( tog, $scope.categoryFilter );
            $scope.tagsFilter = [];
            $rootScope.filter();
        };
        $scope.loadmorerelatedvideo = function () {
            nexturl = 1;
            $rootScope.filter();
        };
        $rootScope.filter = function () {
            var url;
            if ( nexturl ) {
                url = $scope.videos.next_page_url;
            } else {
                url = requestFactory.getUrl( 'videos' );
            }
            requestFactory.post( url, {main_category : $stateParams.slug,search : $rootScope.fields.search,category : $scope.categoryFilter.join( ',' ),tag : $scope.tagsFilter.join( ',' )}, success, fail );
        }
        // var load_all_datas = function () {
        //     var i;
        //     for ( i = 0; i <= $scope.categories.child_category.length - 1; i++ ) {
        //         if ( $scope.categories.child_category [i].slug == $rootScope.subcatslug ) {
        //             $scope.categoryFilter = [];
        //             var j = 0;
        //             var ca = $scope.categories.child_category [i];
        //             for ( j = 0; j <= ca.child_category.length; j++ ) {
        //                 if ( ca.child_category [j] ) {
        //                     $scope.categoryFilter = $scope.toggleSelection( ca.child_category [j].slug, $scope.categoryFilter );
        //                 }
        //             }
        //             $rootScope.filter();
        //             break;
        //         }
        //     }
        // }
        // if ( $rootScope.subcatslug !== '' ) {
        //     load_all_datas();
        // }
        // $scope.$on( 'reloadRoute', function ( event ) {
        //     load_all_datas();
        // } );
    }] )

} )();

/**
 * extends string prototype object to get a string with a number of characters from a string.
 * 
 * @type {Function|*}
 */
String.prototype.trunc = String.prototype.trunc || function ( n ) {

    // this will return a substring and
    // if its larger than 'n' then truncate and append '...' to the string and return it.
    // if its less than 'n' then return the 'string'
    return this.length > n ? this.substr( 0, n - 1 ) + '...' : this.toString();
};