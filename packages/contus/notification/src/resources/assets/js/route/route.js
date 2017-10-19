( function () {
    'use strict';
    appRoute.config( [
            '$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', function ( $stateProvider, $urlRouterProvider, $ocLazyLoadProvider ) {
                $stateProvider.state( "notifications", {
                    url : "/notifications",
                    controller : 'notificationController',
                    controllerAs : 'notificationCtrl',
                    templateUrl : 'notifications',
                    access : 'login',
                    resolve : {
                        data : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'profile' ), {
                                headers : requestFactory.getHeaders()
                            } );
                        },
                        notification : function ( requestFactory, $http ) {
                            return $http.get( requestFactory.getUrl( 'notifications' ), {
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
                                            'contus/notification/js/myaccount/controller/notificationController.js',
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