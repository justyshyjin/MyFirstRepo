var appRoute = angular.module( "app.routes" );
( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $ocLazyLoadProvider.config( {
                    debug : false,
                    modules : [
                            // ----------- wysihtml5 ELEMENTS -----------
                            {
                                name : 'login',
                                files : [
                                        'contus/customer/js/auth/controller/LoginController.js',
                                ],
                                serie : true
                            }, {
                                name : 'dashboard',
                                files : [
                                        'contus/customer/js/auth/controller/DashboardController.js',
                                ],
                                serie : true
                            }, {
                                name : 'signup',
                                files : [
                                        'contus/customer/js/auth/controller/SignupController.js',
                                ],
                                serie : true
                            },{
                                name : 'newpassword',
                                files : [
                                        'contus/customer/js/auth/controller/NewpasswordController.js',
                                ],
                                serie : true
                            },{
                                name : 'profile',
                                files : [
                                        'contus/customer/js/myaccount/controller/myAccountController.js',
                                ],
                            }, {
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
                                name : 'profile',
                                files : [
                                        'contus/customer/js/myaccount/controller/myAccountController.js',
                                ],
                                serie : true
                            }, {
                                name : 'password',
                                files : [
                                        'contus/customer/js/myaccount/controller/changePasswordController.js',
                                ],
                                serie : true
                            }, {
                                name : 'notifications',
                                files : [
                                        'contus/customer/js/myaccount/controller/changePasswordController.js',
                                ],
                                serie : true
                            }, {
                                name : 'favourite',
                                files : [
                                        'contus/customer/js/myaccount/controller/favouriteController.js',
                                ],
                                serie : true
                            }, {
                                name : 'following',
                                files : [
                                        'contus/customer/js/myaccount/controller/followController.js',
                                ],
                                serie : true
                            },{
                                name : 'subscription',
                                files : [
                                        'contus/customer/js/myaccount/controller/subscriptionController.js',
                                ],
                                serie : true
                            }
                    ]
                } );
                $urlRouterProvider.otherwise( '/' );
                $stateProvider.state( "dashboard", {
                    url : "/",
                    controller : 'dashboardController',
                    controllerAs : 'dashCtrl',
                    templateUrl : 'dashboard',
                    resolve : {
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
                                    $ocLazyLoad.getModuleConfig( 'dashboard' ).files.forEach( function ( files ) {
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
                } ).state( "resetpassword", {
                    url : "/forgotpassword/:slug",
                    templateUrl: function(urlattr){
                        return 'forgotPassword/' + urlattr.slug;
                    }
            } );
                $stateProvider.state( "videos", {
                    url : "/videos",
                    controller : 'VideoController',
                    controllerAs : 'videoCtrl',
                    templateUrl : 'allvideos',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'videos' ), {
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
                } );
                $stateProvider.state( "profile", {
                    url : "/profile",
                    controller : 'myAccountController',
                    controllerAs : 'accountCtrl',
                    templateUrl : 'myprofile',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'profile' ).files.forEach( function ( files ) {
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
                $stateProvider.state( "subscriptions", {
                    url : "/subscriptions",
                    controller : 'subscriptionController',
                    controllerAs : 'subCtrl',
                    templateUrl : 'subscriptions',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
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
                                    [
                                            'contus/base/js/gridView.js', 'contus/customer/js/myaccount/controller/subscriptionController.js',

                                    ].forEach( function ( files ) {
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
                $stateProvider.state( "subscribeinfo", {
                    url : "/subscribeinfo",
                    controller : 'myAccountController',
                    controllerAs : 'accountCtrl',
                    templateUrl : 'subscribeinfo',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'profile' ).files.forEach( function ( files ) {
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

                $stateProvider.state( "password", {
                    url : "/password",
                    controller : 'changePasswordController',
                    controllerAs : 'passwordCtrl',
                    templateUrl : 'password',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'password' ).files.forEach( function ( files ) {
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

                $stateProvider.state( "editProfile", {
                    url : "/editProfile",
                    controller : 'myAccountController',
                    controllerAs : 'profileCtrl',
                    templateUrl : 'editProfile',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'profile' ).files.forEach( function ( files ) {
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
                $stateProvider.state( "favourites", {
                    url : "/favourites",
                    controller : 'favouritesController',
                    controllerAs : 'favouritesCtrl',
                    templateUrl : 'favourites',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        favourites : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'favourite' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'favourite' ).files.forEach( function ( files ) {
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
                $stateProvider.state( "following", {
                    url : "/following",
                    controller : 'followController',
                    templateUrl : 'following',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        playlists : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'playlists' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'following' ).files.forEach( function ( files ) {
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

                $stateProvider.state( "subscription", {
                    url : "/",
                    controller : 'subscriptionController',
                    controllerAs : 'subCtrl',
                    templateUrl : 'subscrptionForm',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'subscriptions' ), {
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
                                    $ocLazyLoad.getModuleConfig( 'subscription' ).files.forEach( function ( files ) {
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