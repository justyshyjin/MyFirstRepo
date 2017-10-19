( function () { 
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $ocLazyLoadProvider.config( {
                    debug : false,
                    modules : [
                            // ----------- wysihtml5 ELEMENTS -----------
                            {
                                name : 'videos',
                                files : [
                                        'contus/video/js/customer/video/controller/VideoController.js',
                                ],
                                serie : true
                            }, {
                                name : 'category',
                                files : [
                                        'contus/video/js/customer/video/controller/CategoryListController.js',
                                ],
                                serie : true
                            }, {
                                name : 'videodetailall',
                                files : [
                                    'contus/video/js/customer/videodetail/controller/VideoControl.js','contus/video/js/customer/videodetail/controller/VideoController.js'
                                ],
                                serie : true
                            }, {
                                name : 'videodetail',
                                files : [
                                    'contus/base/flowplayer/flowplayer.min.js','contus/base/flowplayer/flowplayer.hlsjs.min.js','//s0.2mdn.net/instream/html5/ima3.js',"//releases.flowplayer.org/vast/flowplayer.org/vast.min.js",'contus/base/flowplayer/skin/flowplayer.quality-selector.css', 'contus/base/flowplayer/skin/skin.css', 'http://platform.twitter.com/widgets.js', 'contus/video/js/customer/videodetail/controller/VideoController.js','contus/video/js/customer/videodetail/controller/VideoControl.js',
                                ],
                                serie : true
                            }, {
                                name : 'playlist',
                                files : [
                                        'contus/video/js/customer/playlists/controller/PlaylistController.js',
                                ],
                                serie : true
                            },{
                                name : 'livevideos',
                                files : [
                                        'contus/video/js/customer/livevideos/controller/LivevideoController.js',
                                ],
                                serie : true
                            }, {
                                name : 'examlistingpage',
                                files : [
                                        'contus/video/js/customer/exams/controller/ExamListContoller.js',
                                ],
                                serie : true
                            }, {
                                name : 'playlistdetail',
                                files : [
                                        'contus/video/js/customer/playlists/controller/PlaylistDetailController.js',
                                ],
                                serie : true
                            }, {
                                name : 'forgotpassword',
                                files : [
                                        'contus/video/js/customer/forgotpassword/controller/ForgotpasswordController.js',
                                ],
                                serie : true
                            }
                    ]
                } );
                $stateProvider.state( "category", {
                    url : "/category",
                    controller : 'CategoryListController',
                    templateUrl : 'listCategories',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'getCategoriesNavList' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'category' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "categorysection", {
                    url : "/section/:category/:slug",
                    controller : function ( $stateParams, $state ) {
                        sessionStorage.category = $stateParams.slug;
                        $state.go( 'categoryvideos', {
                            'slug' : $stateParams.category
                        }, {
                            reload : true
                        } )
                    }
                } ).state( "categoryvideos", {
                    url : "/videos/:slug",
                    controller : 'VideoController',
                    controllerAs : 'videoCtrl',
                    templateUrl : 'allvideos',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'videos' ), {
                                'main_category' : $stateParams.slug,
                                'tag' : sessionStorage.tag,
                                'search' : $rootScope.fields.search,
                                'category' : sessionStorage.category
                            }, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videos' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "videoDetail", {
                    url : "/video-detail/:slug",
                    views: {
                        '':{templateUrl : 'videodetail',
                            controller : 'VideoDetailController'},
                        'examdetail@videoDetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : { 
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        recommended : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'recommended' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        relateddata : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/related/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        comments : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/comments/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        questions : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/qa/' + $stateParams.slug ), {}, {
                                    headers : requestFactory.getHeaders()
                                } );
                            },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetail' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }} ).state( "liveDetail", {
                    url : "/live/:slug",
                    views: {
                        '':{templateUrl : 'videodetail',
                            controller : 'VideoDetailController'},
                        'examdetail@liveDetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        relateddata : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'getAllLiveVideos' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "playlist", {
                    url : "/playlist",
                    controller : 'PlaylistController',
                    controllerAs : 'playCtrl',
                    templateUrl : 'allPlaylists',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'playlist' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'playlist' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "playlistList", {
                    url : "/playlist/:slug",
                    controller : 'ExamListContoller',
                    templateUrl : 'playlistlistdetail',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/playlist/' + $stateParams.slug ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                    '$ocLazyLoad', function ( $ocLazyLoad ) {
                        var load = {
                            serie : true,
                            files : [],
                            cache : true
                        };
                        /**
                         * init the dependencies array
                         * 
                         * @type {Array}
                         */
                        $ocLazyLoad.getModuleConfig( 'examlistingpage' ).files.forEach( function ( files ) {
                            load.files.push( files );
                        } );
                        /**
                         * check if the lazy load data exists
                         */
                        if ( load.files.length ) {
                            return $ocLazyLoad.load( load );
                        }
                    }]
                    }
                } ).state( "playlistdetail", {
                    parent:'playlistList', 
                    url : "/:video",
                    views: {
                        'examdetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.video ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "examList", {
                    url : "/examgroup/:slug",
                    controller : 'ExamListContoller',
                    templateUrl : 'grouplistdetail',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'group/' + $stateParams.slug ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                    loadCtrl : [
                        '$ocLazyLoad', function ( $ocLazyLoad ) {
                            var load = {
                                serie : true,
                                files : [],
                                cache : true
                            };
                            /**
                             * init the dependencies array
                             * 
                             * @type {Array}
                             */
                            $ocLazyLoad.getModuleConfig( 'examlistingpage' ).files.forEach( function ( files ) {
                                load.files.push( files );
                            } );
                            /**
                             * check if the lazy load data exists
                             */
                            if ( load.files.length ) {
                                return $ocLazyLoad.load( load );
                            }
                        }
                ]
                    }
                } ).state( "examdetail", {
                    parent:'examList', 
                    url : "/:video",
                    views: {
                        'examdetail':{
                            templateUrl : 'groupvideodetail',
                            controller : 'VideoDetailControl'},},
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.post( requestFactory.getUrl( 'videos/' + $stateParams.video ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'videodetailall' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } ).state( "livevideos", {
                    url : "/livevideos",
                    controller : 'LivevideoController',
                    controllerAs : 'liveCtrl',
                    templateUrl : 'livevideos',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams, $rootScope ) {
                            return $http.post( requestFactory.getUrl( 'livevideos' ), {}, {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        loadCtrl : [
                                '$ocLazyLoad', function ( $ocLazyLoad ) {
                                    var load = {
                                        serie : true,
                                        files : [],
                                        cache : true
                                    };
                                    /**
                                     * init the dependencies array
                                     * 
                                     * @type {Array}
                                     */
                                    $ocLazyLoad.getModuleConfig( 'livevideos' ).files.forEach( function ( files ) {
                                        load.files.push( files );
                                    } );
                                    /**
                                     * check if the lazy load data exists
                                     */
                                    if ( load.files.length ) {
                                        return $ocLazyLoad.load( load );
                                    }
                                }
                        ],
                    }
                } );
            }
    ] )

} )();