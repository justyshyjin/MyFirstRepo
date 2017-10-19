( function () {
    'use strict';
    var routes = angular.module( "app.routes" );
    routes.factory( 'requestFactory', requestFactory );
    routes.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $stateProvider.state( "transactions", {
                    url : "/transactions",
                    controller : 'transactionController',
                    controllerAs : 'transactionCtrl',
                    templateUrl : 'transactions',
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
            }
    ] )

} )();