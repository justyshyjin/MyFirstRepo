( function () {
    "use strict";
    var controller = angular.module( "app.controllers" );
    controller.factory( 'requestFactory', requestFactory );
    controller.controller( 'notificationController', ['$scope','$state','$filter','$rootScope','$document','requestFactory','data','notification','ngToast',function ( $scope, $state, $filter, $rootScope, $document, requestFactory, data, notification, ngToast ) {
        $scope.profile = {};
        $scope.notifications = [];
        $scope.next_page = '';
        var successResponseData;
        $scope.subscription = data.data.message.subscription;
        var dataBinder = function () {
            $scope.data = successResponseData.response;
            $scope.notification_settings = successResponseData.response.notification_settings;
            $scope.notify_comment = successResponseData.response.notification_settings.notify_comment;
            $scope.notify_videos = successResponseData.response.notification_settings.notify_videos;
            $scope.notify_reply_comment = successResponseData.response.notification_settings.notify_reply_comment;
            $scope.notify_newsletter = successResponseData.response.notification_settings.notify_newsletter;
            $scope.notificationData = $scope.data.data;
            $scope.notifications = $scope.notifications.concat( $scope.notificationData );
            $scope.next_page = $scope.data.next_page_url;
        };
        $scope.isreadNotifications = function () {
            this.notify = 1;
            if ( baseValidator.validateAngularForm( event.target, $scope ) ) {
                requestFactory.post( requestFactory.getUrl( 'notification/isRead' ), this.notify, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    $rootScope.notificationCount = 0;
                }, function () {
                } );

            }
        };
        $scope.toggleSelection = function ( value, name ) {
            if ( name == 'notify_comment' && value == 0 ) {
                $scope.notify_comment = 0;
            }
            if ( name == 'notify_videos' && value == 0 ) {
                $scope.notify_videos = 0;
            }
            if ( name == 'notify_reply_comment' && value == 0 ) {
                $scope.notify_reply_comment = 0;
            }
            if ( name == 'notify_newsletter' && value == 0 ) {
                $scope.notify_newsletter = 0;
            }
        };
        $scope.goState = function ( notification ) {
            if ( notification.type === 'subscription_plans' ) {
                $state.go( 'subscriptions' );
            } else {
                $state.go( 'videoDetail', {slug : notification [notification.type_type] [0].slug} );
            }
        }
        $scope.updateNotificationsettings = function () {
            this.notify = [];
            if ( $scope.notify_comment ) {
                this.notify.push( {notify_comment : 1} )
            } else {
                this.notify.push( {notify_comment : 0} )
            }
            if ( $scope.notify_videos ) {
                this.notify.push( {notify_videos : 1} )
            } else {
                this.notify.push( {notify_videos : 0} )
            }
            if ( $scope.notify_reply_comment ) {
                this.notify.push( {notify_reply_comment : 1} )
            } else {
                this.notify.push( {notify_reply_comment : 0} )
            }
            if ( $scope.notify_newsletter ) {
                this.notify.push( {notify_newsletter : 1} )
            } else {
                this.notify.push( {notify_newsletter : 0} )
            }

            if ( baseValidator.validateAngularForm( event.target, $scope ) ) {
                requestFactory.post( requestFactory.getUrl( 'notification/updatesettings' ), this.notify, function ( response ) {
                    ngToast.create( {className : 'success',content : '<strong>' + "Notification settings updated successfully" + '</strong>'} );
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    $state.reload();
                }, this.fillError );

            }
        };
        $scope.isreadNotifications();
        this.fillError = function ( response ) {
            if ( response.status == 422 && response.data.hasOwnProperty( 'messages' ) ) {
                angular.forEach( response.data.messages, function ( message, key ) {
                    if ( typeof message == 'object' && message.length > 0 ) {
                        $scope.errors [key] = {has : true,message : message [0]};
                    }
                } );
            }
        };
        var success = function ( success ) {
            successResponseData = success;
            dataBinder();
        };
        var fail = function ( fail ) {
            return fail;
        };
        $scope.moreNotifications = function () {
            requestFactory.get( $scope.next_page, success, fail );
        };
        success( notification.data );
    }] );
} )();