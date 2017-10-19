( function () {
    'use strict';

    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $stateProvider.state( "transactions", {
                    url : "/transactions",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl : 'transactions',
                    access : 'login',
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
                                            'contus/base/js/gridView.js', 'contus/payment/js/myaccount/controller/transactionController.js',

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

                $stateProvider.state( "subscribeinfos", {
                    url : "/subscribeinfos",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl : 'subscribeinfos',
                    access : 'login',
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
                                    [
                                            'contus/payment/js/myaccount/controller/transactionController.js',
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
                $stateProvider.state( "paymentsuccess", {
                    url : "/paymentsuccess/:slug",
                    templateUrl: function(urlattr){
                        return 'paymentsuccess/' + urlattr.slug;
                    },
                } ); $stateProvider.state( "paymentfailure", {
                    url : "/paymentfailure/:slug",
                    templateUrl: function(urlattr){
                        return 'paymentfailure/' + urlattr.slug;
                    },
                } ); $stateProvider.state( "paymentcancel", {
                    url : "/paymentcancel/:slug",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl: function(urlattr){
                        return 'paymentcancel/' + urlattr.slug;
                    },
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
                                            'contus/base/js/gridView.js', 'contus/payment/js/myaccount/controller/transactionController.js',

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
            }
    ] )

} )();