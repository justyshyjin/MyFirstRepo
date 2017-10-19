@section('profilecontent')
<div class="col-md-9">
    <div class="row">

          <div class="col-md-12 col-xs-12 col-sm-12">
    <div class="row">
            <div class="panel panel-default payment-actions">
  <div class="panel-body">
   <i class="actions-img"></i>
  <div class="payment-actions-content except-profile">
     <ul class="video-member-options clearfix" >
                    <li class="" data-ng-repeat="subcrp in subscription">
                        <span>Video / PDF / MP3</span>
                        <strong class="rate-card">@{{subcrp.name}}</strong>
                         <strong class="prices"><i class="fa fa-inr"></i> @{{subcrp.amount}}</strong>
                        <span class="video-valid-text">@{{subcrp.duration}} days</span>
                        <a ui-sref="subscribeinfo" class="action-subscription ripple">Subscribe Now</a>
                    </li>
               </ul>
   </div>
  </div>
</div></div>
        </div>

          <div class="col-md-12 col-xs-12 col-sm-12">
    <div class="row">
        <div
            class="panel panel-default myaccount notification-collections">
            <div class="panel-heading">
                Notifications <a  title="Settings"  class="pull-right edit-info"
                    href="javascript:;" data-ng-init ="notification_list = 1" data-ng-click="notification_list = !notification_list">Settings </a>
            </div>
            <div class="panel-body" ng-hide="notification_list">
                    <div class="media">
                    <form name="updatenotificationsettingsForm" method="POST" data-base-validator data-ng-submit="updateNotificationsettings()"
			        enctype="multipart/form-data" novalidate>
			        {!! csrf_field() !!}
                    <div class="media-body">
                     <strong class="cs-title-sec">Do Not notify me when </strong>
                        <ul >
                            <li>
                            <div class="input">
                            <input type="checkbox"  ng-checked = "notification_settings.notify_comment" name="notify_comment" id="notify_comment"  ng-model="notify_comment" value="notify_comment" ng-click="toggleSelection(notify_comment,'notify_comment')" ng-true-value="1" ng-false-value="0"><label for="notify_comment" >{{trans ('notification::notification.someonecomment')}}</label>
                            </div></li>
                            <li>
                            <div class="input">
                            <input type="checkbox"  ng-checked = "notification_settings.notify_reply_comment" name="notify_reply_comment" id="notify_reply_comment" ng-model="notify_reply_comment"  value="notify_reply_comment" ng-click="toggleSelection(notify_reply_comment,'notify_reply_comment')" ng-true-value="1" ng-false-value="0"><label for="notify_reply_comment">{{trans ('notification::notification.someonereplycomment')}}</label>
                            </div>
                            </li>
                            <li>
                            <div class="input">
                            <input type="checkbox"  ng-checked = "notification_settings.notify_videos" name="notify_videos" id="notify_videos" ng-model="notify_videos"  value="notify_videos" ng-click="toggleSelection(notify_videos,'notify_videos')" ng-true-value="1" ng-false-value="0"><label for="notify_videos">{{trans ('notification::notification.videocomment')}}</label>
                            </div>
                            </li>
                        </ul>
                         <strong class="cs-title-sec">Subscribe  me to </strong>
                           <ul>
                            <li>
                            <div class="input">
                            <input type="checkbox" ng-checked = "notification_settings.notify_newsletter" name="notify_newsletter" id="notify_newsletter" ng-model="notify_newsletter" ng-click="toggleSelection(notify_newsletter,'notify_newsletter')" ng-true-value="1" ng-false-value="0"><label for="notify_newsletter" >{{trans ('notification::notification.newsletter')}}</label>
                            </div>
                            </li>
                            <span class="dummylabel">{{trans ('notification::notification.alert')}}</span>
                         </ul>
                        <button title="Submit" title="Update Setting" title="Submit" class="btn btn-blue pull-left">Update Setting
                        </button>

                    </div>
                    </form>
                </div>

            </div>
          <div class="panel-body"  ng-show="notification_list">
                <div class="media"
                    ng-repeat="notification in notifications">
                    <a href="javascript:;" ng-click="goState(notification)">
                    <div class="media-left">
                        <img alt="64x64"
                            class="media-object notification-img"
                            ng-src="@{{notification[notification.creator_type][0].profile_picture}}"
                            src="{{$cdnUrl('images/user.png')}}"
                            err-src="{{$cdnUrl('images/user.png')}}"
                            data-holder-rendered="true">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">@{{notification.content}}</h4>
                        <p class="notification-status">
                        <span ng-switch="notification.type">
                        <span ng-switch-when='subscription_plans' ></span>
                        <span ng-switch-when='comment' class="cs-answer" style="font-size: 12px;"> @{{notification.created_at|convertDate|convertAgoTime}}</span>
                        <span ng-switch-when='question' class="cs-question">@{{notification.created_at|convertDate|convertAgoTime}}</span>
						  <span ng-switch-when='answer' class="cs-answer">@{{notification.created_at|convertDate|convertAgoTime}}</span>
                          <span ng-switch-when='rcomment' class="cs-answer">@{{notification.created_at|convertDate|convertAgoTime}}</span>
						  <span ng-switch-when='video' class="cs-recentadded"> <strong>Newly added on</strong> <span class="date-notifiaction">@{{notification.created_at|convertDate|date:'MMM dd, yyyy'}}</span>  @{{notification.created_at|convertDate|date:'h:mm a'}}</span>
						  <span ng-switch-when='live' class="cs-currentlive">
                         <strong>next live on </strong><span class="date-notifiaction">@{{notification.created_at|convertDate|date:'MMM dd, yyyy'}}</span>
                         @{{notification.created_at|convertDate|date:'h:mm a'}}
					      </span>
                        </span>
                        </p>
                    </div>
                    </a>
                </div>
                <div class="media-body" ng-if="!(notifications.length)">
                        <h4 class="media-heading mynorecordfound">{{trans ('notification::notification.notfound')}}</h4>
                    </div>

                 <div class="show-all-notifications text-center" ng-show="next_page!=null">
                    <a title="Show more" href="javascript:;" class="" ng-click="moreNotifications()" title="Show more">Show all notifications</a>
                </div>

            </div>

        </div></div></div>
    </div>
</div>
@endsection @include('customer::user.account.index')
