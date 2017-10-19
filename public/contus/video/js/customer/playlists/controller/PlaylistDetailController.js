( function () {
    'use strict';
    var controllers = angular.module( "app.controllers" );
    controllers.factory( 'requestFactory', requestFactory );
    controllers.directive( 'baseValidator', validatorDirective );
    controllers.controller( 'PlaylistDetailController', [
            '$filter', '$rootScope', 'requestFactory', '$stateParams', '$scope', 'ngToast', 'data', 'relateddata', 'comments', '$state', function ( $filter, $rootScope, requestFactory, $stateParams, $scope, ngToast, data, relateddata, comments, $state ) {

                var successResponseData;
                var dataBinder = function ( response ) {
                    $scope.subscription = response.subscription;
                    $scope.tags = response.videos.tags;
                    $scope.category = response.videos.categories[ 0 ];
                    $scope.videos = response.videos;
                    $scope.show_video_description = $scope.videos.short_description;
                    $scope.audioDownload = {
                        link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',
                        size : '12.7MB'
                    };
                    $scope.pdfDownload = {
                        link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',
                        size : '21.8MB'
                    };
                    $scope.docDownload = {
                        link : 'http://www.cbu.edu.zm/downloads/pdf-sample.pdf',
                        size : '34.7MB'
                    };
                    if ( angular.isDefined( $rootScope.myPlayer ) ) {
                        $rootScope.myPlayer.dispose();
                    }
                    $rootScope.myPlayer = videojs( 'video_player', {
                        "techOrder" : [
                                'html5', 'flash'
                        ],
                        "controls" : true,
                        "preload" : "auto",
                    } );
                    $rootScope.myPlayer.src( [
                        {
                            type : "video/mp4",
                            src : $scope.videos.transcodedvideos[ 0 ].video_url
                        }
                    ] );
                    if ( angular.isString( $scope.videos.thumbnail_image ) && $scope.videos.thumbnail_image.length > 0 ) {
                        $rootScope.myPlayer.poster( $scope.videos.thumbnail_image );
                    } else {
                        $rootScope.myPlayer.poster( $scope.videos.transcodedvideos[ 0 ].thumb_url );
                    }

                    $scope.randsub = $scope.subscription.data[ Math.floor( ( Math.random() * 3 ) + 0 ) ];
                    document.getElementById( "video_player" ).style.display = "block";
                    // Change video url and preview image url.

                };
                $scope.related = [];
                $scope.comment = [];
                var dataBinderrelated = function ( response ) {
                    var temp = {};
                    temp = $scope.related.data;
                    $scope.related = response;
                    $scope.related.data = ( temp ) ? temp.concat( $scope.related.data ) : $scope.related.data;
                };
                var dataBinderComments = function ( response ) {
                    $scope.comment = response;
                }
                $scope.toggleDescription = function () {
                    $scope.show_video_description = ( $scope.show_video_description == $scope.videos.short_description ) ? $scope.videos.description : $scope.videos.short_description;
                };
                dataBinder( data.data.response );
                dataBinderrelated( relateddata.data.response );
                dataBinderComments( comments.data.response );
                $scope.postparentcomment = function ( val ) {
                    if ( val !== '' ) {
                        var data = {
                            comment : val
                        };
                        PostComment( data );
                        $scope.parentcomment = '';
                    }
                }
                $scope.postchildcomment = function ( id, comment ) {
                    if ( comment !== '' ) {
                        var data = {
                            comment : comment,
                            parent_id : id
                        };
                        PostComment( data );
                        angular.element( '.childcomment' ).value = "";
                    }
                }
                var commentsuccess = function ( response ) {
                    dataBinderComments( response.response );
                    ngToast.create( {
                        className : 'success',
                        content : '<strong>' + response.message + '</strong>'
                    } );
                }
                var commentfail = function ( response ) {
                    ngToast.create( {
                        className : 'danger',
                        content : '<strong>' + response.message + '</strong>'
                    } );

                }
                var commenturl = requestFactory.getUrl( 'videos/comments/' + $stateParams.slug );
                var PostComment = function ( data ) {
                    requestFactory.post( commenturl, data, commentsuccess, commentfail );
                }
                $scope.loadprevcomment = function () {
                    commenturl = $scope.comment.prev_page_url;
                    requestFactory.post( commenturl, {}, commentsuccess, commentfail );
                }
                $scope.loadmorecomment = function () {
                    commenturl = $scope.comment.next_page_url;
                    requestFactory.post( commenturl, {}, commentsuccess, commentfail );
                }
                $scope.addFavorites = function () {
                    if ( $scope.videos.favourites === 1 ) {
                        requestFactory.put( requestFactory.getUrl( 'favourite' ), {
                            'video_slug' : $stateParams.slug
                        }, function ( success ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + success.message + '</strong>'
                            } );
                            $scope.videos.favourites = 0;
                        }, commentfail );
                    } else {
                        requestFactory.post( requestFactory.getUrl( 'favourite' ), {
                            'video_slug' : $stateParams.slug
                        }, function ( success ) {
                            ngToast.create( {
                                className : 'success',
                                content : '<strong>' + success.message + '</strong>'
                            } );
                        }, commentfail );
                        $scope.videos.favourites = 1;
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
                    $state.go( 'categoryvideos', {
                        'slug' : $scope.category.parent_category.parent_category.slug
                    }, {
                        reload : true
                    } )
                };
                $rootScope.loadmorerelatedcategory = function () {
                    if ( $scope.related.next_page_url !== null ) {
                        requestFactory.post( $scope.related.next_page_url, {}, success, fail );
                    }
                }
                $scope.formatDate = function ( date, format ) {
                    var formattedDate = date;
                    if ( !angular.isDefined( format ) ) {
                        format = 'd MMM, yyyy';
                    }
                    try {
                        formattedDate = $filter( 'date' )( new Date( date ), format );
                    } catch ( error ) {
                    }
                    return formattedDate;
                };
            }
    ] )

} )();

/**
 * extends string prototype object to get a string with a number of characters from a string.
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