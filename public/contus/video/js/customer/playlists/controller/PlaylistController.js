( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'initializeOwlCarousel', intializeOwlCarouselDirective );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'PlaylistController', [
            '$rootScope', 'requestFactory', '$stateParams', '$scope', 'ngToast', 'data', '$filter', function ( $rootScope, requestFactory, $stateParams, $scope, ngToast, data, $filter ) {
                var nexturl = 0;
                $scope.videoOwlCarouselOptions = {
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
                        500 : {
                            items : 2
                        },
                        768 : {
                            items : 3
                        },
                        992 : {
                            items : 4,
                            loop : false
                        }
                    }

                };
                $scope.category = data.data.response;
                $scope.sortby = "recently";
                $scope.selectSortBy = function(type){
                	$scope.sortby = type;
                	requestFactory.post( requestFactory.getUrl( 'playlist' ), {'sortby':type}, ( function ( response ) {
                		$scope.category = response.response;                       
                    } ), error );
                }
                $scope.loadmorecategories = function () {
                    requestFactory.post( $scope.category.next_page_url, {'sortby':$scope.sortby}, ( function ( response ) {
                        var temp = '';
                        temp = $scope.category.data;
                        $scope.category = response.response;
                        $scope.category.data = ( temp ) ? temp.concat( $scope.category.data ) : $scope.category.data;
                    } ), error );
                }
                $scope.togglefollowplaylist = function ( subcatslug, index ) {
                    var key = ( $filter( 'getByKey' )( $scope.category.data, subcatslug.slug, 'slug', 'key' ) );
                    if (! $scope.category.data[ key ].auth_follower.length) {
                        requestFactory.post( requestFactory.getUrl( 'playlists' ), {
                            'playlist_slug' : $scope.category.data[ key ].slug
                        }, function ( success ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + success.message + '</strong>'
                            } );
                            $scope.category.data[ key ].auth_follower[0] = {'is_follow':1};
                        }, error );
                    } else {
                        requestFactory.put( requestFactory.getUrl( 'playlists' ), {
                            'playlist_slug' : $scope.category.data[ key ].slug
                        }, function ( success ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + success.message + '</strong>'
                            } );
                            $scope.category.data[ key ].auth_follower = [];
                        }, error );
                    }
                }
                var error = function ( error ) {
                    ngToast.create( {
                        className : 'danger',
                        content : '<strong>' + error.message + '</strong>'
                    } );
                }
                $scope.$on( "triggerNextOwlcarosel", function ( evt, data ) {
                    var key = ( $filter( 'getByKey' )( $scope.category.data, data[ 'parent-slug' ], 'slug', 'key' ) );
                    $scope.loadmoreplaylists( $scope.category.data[ key ].playlists, data[ 'parent-slug' ], key )
                } );
                $scope.loadmoreplaylists = function ( playlist, slug, key ) {
                    if ( playlist.next_page_url !== null ) {
                        requestFactory.post( playlist.next_page_url, {
                            'slug' : slug
                        }, ( function ( response ) {
                            var temp = '';
                            temp = $scope.category.data[ key ].playlists.data;
                            $scope.category.data[ key ].playlists = response.response.playlists;
                            $scope.category.data[ key ].playlists.data = ( temp ) ? temp.concat( $scope.category.data[ key ].playlists.data ) : $scope.category.data[ key ].playlists.data;
                        } ), error );
                    }
                }
            }
    ] )

} )();