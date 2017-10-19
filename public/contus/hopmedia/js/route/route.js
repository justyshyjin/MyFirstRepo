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
                                name : 'dashboard',
                                files : [
                                        '../contus/hopmedia/js/controller/Dashboard/DashboardController.js',
                                ],
                                serie : true
                            }
                            
                    ]
                } );
                $urlRouterProvider.otherwise( '/' );
                $stateProvider.state( "dashboard", { 
                    url : "/",
                    controller : 'dashboardController',
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
                } );
                $stateProvider.state( "features", { 
                    url : "/Features",
                    controller : 'dashboardController',
                    templateUrl : 'features',
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
                } );

                $stateProvider.state('pricing',{
                    url : "/Pricing",
                    controller : 'dashboardController',
                    templateUrl : 'pricing',
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
                });

            $stateProvider.state('aboutUs',{
                    url : "/Aboutus",
                    controller : 'dashboardController',
                    templateUrl : 'aboutUs',
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
                });
  
            $stateProvider.state('contact',{
                    url : "/ContactUs",
                    controller : 'dashboardController',
                    templateUrl : 'contact',
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
                });
           
            $stateProvider.state('privacy-policy',{
                    url : "/Privacy-Policy",
                    controller : 'dashboardController',
                    templateUrl : 'privacy-policy',
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
                });
                $stateProvider.state('terms-condition',{
                    url : "/Terms-Condition",
                    controller : 'dashboardController',
                    templateUrl : 'terms-condition',
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
                });
                
            }
    ] )

} )();