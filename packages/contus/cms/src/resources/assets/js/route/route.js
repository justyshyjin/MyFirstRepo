( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $ocLazyLoadProvider.config( {
                    debug : false,
                    modules : [
                            // ----------- wysihtml5 ELEMENTS -----------
                            {
                                name : 'content',
                                files : [
                                        'contus/cms/js/staticcontent/controller/StaticContentController.js',
                                ],
                                serie : true
                            },
							{
                                name : 'blog',
                                files : [
                                        'contus/cms/js/latestnews/controller/blogController.js',
                                ],
                                serie : true
                            },
                            {
                                name : 'mobileblog',
                                files : [
                                        'contus/base/css/mobile.css',
                                ],
                                serie : true
                            },
							{
                                name : 'blogdetail',
                                files : [
                                        'contus/cms/js/latestnews/controller/blogDetailController.js',
                                ],
                                serie : true
                            },
                    ]
                } );
              
                $stateProvider.state( "staticContent", {
                    url : "/content/:slug",
                    controller : 'StaticContentController',
                    controllerAs : 'contentCtrl',
                    templateUrl : 'staticContentTemplate',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'staticcontent/' + $stateParams.slug), {
                                headers : requestFactory.getHeaders()
                            });
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
                                        $ocLazyLoad.getModuleConfig( 'content' ).files.forEach( function ( files ) {
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
                $stateProvider.state( "staticContent.mobile", {
                    url : "/mobile",
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
                                        $ocLazyLoad.getModuleConfig( 'mobileblog' ).files.forEach( function ( files ) {
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
               $stateProvider.state( "blog", {
                    url : "/blog",
                    controller : 'blogController',
                    controllerAs : 'blogCtrl',
                    templateUrl : 'blog',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'blog'), {
                                headers : requestFactory.getHeaders()
                            });
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
                                        $ocLazyLoad.getModuleConfig( 'blog' ).files.forEach( function ( files ) {
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
               $stateProvider.state( "blog.mobile", {
                   url : "/mobile",
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
                                       $ocLazyLoad.getModuleConfig( 'mobileblog' ).files.forEach( function ( files ) {
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
$stateProvider.state( "blogdetail", {
                    url : "/blogdetails/:slug",
                    controller : 'blogDetailController',
                    controllerAs : 'blogdetCtrl',
                    templateUrl : 'blogdetail',
                    resolve : {
                        data : function ( requestFactory, $http, $stateParams ) {
                            return $http.get( requestFactory.getUrl( 'blogdetail/' +$stateParams.slug), {
                                headers : requestFactory.getHeaders()
                            });
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
                                        $ocLazyLoad.getModuleConfig( 'blogdetail' ).files.forEach( function ( files ) {
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
