
<h2 class="big-video-title">@{{videos.title}}</h2>
<div class="col-md-8 pleft0">

    <div class="big-video-container" ng-if="(videos.hls_playlist_url && videos.username=='' && videos.video_duration!=='0:00')?true:false">
        <div class="big-video">
            <div init-flow-player video="videos" class="flowplayer functional fp-slim"></div>
            <div class="initimation-subscribe" ng-class="{'ng-hide':!videos.isSubscription}">
                <div class="initimation-subscribe-inner">
                    <p>Subscribe to watch rest of the video</p>
                    <a class="make-subscription" ui-sref="subscribeinfo" title="subscribe now" href="/subscribeinfo">subscribe now</a>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="big-video-container" ng-if="(videos.hls_playlist_url && videos.youtube_id=='')?true:false">
        <div class="big-video">
            <div init-flow-player video="videos" class="flowplayer functional fp-slim"></div>
            <div class="initimation-subscribe" ng-class="{'ng-hide':!videos.isSubscription}">
                <div class="initimation-subscribe-inner">
                    <p>Subscribe to watch rest of the video</p>
                    <a class="make-subscription" ui-sref="subscribeinfo" title="subscribe now" href="/subscribeinfo">subscribe now</a>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="big-video-container" ng-if="(videos.youtube_id)?true:false">
        <div class="big-video">
            <div class="video">
                <iframe class="embed-responsive-item" src="@{{youtubeVideo}}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="video-content">
        <div class="download-options">
            <ul class="clearfix">
                <li>
                    <a data-ng-if="videos.mp3" title="Audio" ng-href="@{{videos.mp3}}" download ng-class="{'available':!videos.mp3}" target="_self" class="cs-green">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.audio') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{mp3size }}">@{{mp3size }}</span>
                    </a>
                    <a data-ng-hide="videos.mp3" ng-click="notavailable(videos.is_demo)" title="Audio" href="javascript:;" ng-class="{'available':!videos.mp3}" target="_self" class="cs-green">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.audio') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{mp3size }}">@{{mp3size }}</span>
                    </a>
                </li>
                <li>
                    <a data-ng-if="videos.pdf" title="PDF" ng-href="@{{videos.pdf}}" download ng-class="{'available':!videos.pdf}" target="_self" class="cs-skyblue">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.pdf') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{pdfsize != ''}}">@{{pdfsize }}</span>
                    </a>
                    <a data-ng-hide="videos.pdf" ng-click="notavailable(videos.is_demo)" title="PDF" href="javascript:;" ng-class="{'available':!videos.pdf}" target="_self" class="cs-skyblue">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.pdf') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{pdfsize != ''}}">@{{pdfsize }}</span>
                    </a>
                </li>
                <li>
                    <a data-ng-if="videos.word" title="Word" ng-href="@{{videos.word}}" download ng-class="{'available':!videos.word}" target="_self" class="cs-blue">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.word') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{wordsize != ''}}">@{{wordsize }}</span>
                    </a>
                    <a data-ng-hide="videos.word" ng-click="notavailable(videos.is_demo)" title="Word" href="javascript:;" ng-class="{'available':!videos.word}" target="_self" class="cs-blue">
                        <i class="assets-img"></i>
                        <strong class="cs-assest-options">
                            {{trans('video::videos.word') }}
                            <span>{{trans('video::videos.download') }}</span>
                        </strong>
                        <span class="mb-size" ng-show="@{{wordsize != ''}}">@{{wordsize }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="panel panel-default video-comments">
            <div class="panel-heading">
                <a href="javascript:;" ng-click="addFavorites()" ng-if="(videos.youtube_id)?false:true" ng-class="{'favourited-wish':videos.is_favourite}">
                    <i class="wishlist-icon"></i>
                    Favorites
                </a>
                &nbsp;
                <ul class="pull-right sharefriends">
                    <li class="fb">
                        <a facebook data-url='@{{location.absUrl()}}' data-title='@{{videos.title}}' data-text='@{{videos.title}}' data-picture-url='@{{videos.posterUrl}}'>
                            @{{ shares }}
                            <i class="fa fa-facebook-square" aria-hidden="true" title="facebook"></i>
                        </a>
                    </li>
                    <li class="twit">
                        <a init-twitter data-title='@{{videos.title}}' data-url='@{{location.absUrl()}}'>
                            <i class="fa fa-twitter" aria-hidden="true" title="twitter"></i>
                        </a>
                    </li>
                    <li class="linkin">
                        <a linkedin data-url='@{{location.absUrl()}}' data-title='@{{videos.title}}' data-summary="@{{videos.short_description}}">
                            @{{linkedinshares}}
                            <i class="fa fa-linkedin" aria-hidden="true" title="linkedin"></i>
                        </a>
                    </li>
                    <li class="link" ng-hide='true'>
                        <a href="#!">
                            <i class="fa fa-link" aria-hidden="true" title="twitter"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="panel-body ">
                <div class="row no-gutter-changing">
                    <div class="published-date clearfix">
                        <div class="col-md-3 col-xs-12" ng-if="(category.parent_category.parent_category.title)?true:false">
                            <span>Category</span>
                            <strong>@{{category.parent_category.parent_category.title}}</strong>
                        </div>
                        <div class="col-md-3 col-xs-12" ng-if="(category.parent_category.parent_category.title)?true:false">
                            <span>Presenter</span>
                            <strong>@{{videos.presenter}}</strong>
                        </div>
                        <div class="col-md-3 col-xs-12" ng-if="(exam.length)?true:false">
                            <span>Exam</span>
                            <strong ng-repeat="e in exam">
                                @{{e.exams.title}}
                                <i ng-if="!$last">, </i>
                            </strong>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <span>Published on</span>
                            <strong data-ng-if="videos.published_on.length>0"> @{{videos.published_on+' 00:00:00'|convertDate|date:'dd-MM-yyyy'}} </strong>
                            <strong data-ng-if="videos.published_on.length == 0"> NA</strong>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <span>Licensed by</span>
                            <strong>Vplay</strong>
                        </div>
                    </div>
                </div>
                <p class="cs-shortdesc">@{{videos.short_description}}</p>
                <p class="extend">@{{show_video_description}}</p>
            </div>
            <div class="panel-footer text-center">
                <a title="Show @{{(showmoretext)?'More':'less'}}" class="show-more-content" ng-click="toggleDescription()" href="javascript:;">Show @{{(showmoretext)?'More':'less'}}</a>
            </div>
        </div>
    </div>
    <div class="comments-answer-tab">
        <div class="tabbable-panel">
            <uib-tabset class="tabbable-line" active="activeIndexTab"> <uib-tab heading="Comments (@{{(comment.total)?comment.total:0}})">
            <div class="media replied-comment">
                <div class="media-left">
                    <a title="" class="replied-user img-circle">
                        <img alt="" class=" media-object" ng-src="@{{customerProfile.profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </a>
                </div>
                <div class="media-body">
                    <textarea ng-model="parentcomment" class="form-control textare-comment" rows="3" placeholder="Leave your comment here" maxlength="1000"></textarea>
                    <p class="text-right">
                        <button title="Cancel" type="button" ng-click="parentcomment=''" class="btn btn-sm btn-cancel" ng-disabled="!parentcomment">Clear</button>
                        <button title="Comment" type="button" ng-click="postparentcomment(parentcomment)" class="btn btn-sm btn-action" ng-disabled="!parentcomment">Comment</button>
                    </p>
                </div>
            </div>
            <div class="text-center cs-previous-comment">
                <a title="Show Previous" class="view-more-comments ripple" ng-show="comment.prev_page_url !== null" href="javascript:;" ng-click="loadprevcomment()">Show Previous</a>
            </div>
            <div class="media replied-comment" ng-repeat="com in comment.data">
                <div class="media-left">
                    <a title="" class="replied-user img-circle">
                        <img alt="" class=" media-object" ng-src="@{{com[com.user_type].profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        @{{com[com.user_type].name}}
                        <span class="replied-date">@{{com.created_at|convertDate|convertAgoTime}}</span>
                    </h4>
                    <p>@{{com.comment}}</p>

                    <p>
                        <a href="javascript:;" ng-show="com.reply_comment[0].id" class="reply-link show-question" ng-click="isReplycontentOpen = isReplyFormOpen = !isReplycontentOpen">
                            <span ng-hide="isReplycontentOpen">Show reply comments</span>
                            <span ng-hide="!isReplycontentOpen">Hide reply comments</span>
                        </a>
                    </p>
					  <div ng-hide="!isReplycontentOpen" ng-repeat="reply in com.reply_comment">
                        <div class="media-left">
                            <a href="javascript:;" title="" class="replied-user img-circle">
                                <img alt="" class=" media-object" ng-src="@{{reply[reply.user_type].profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                @{{reply[reply.user_type].name}}
                                <span class="replied-date">
                                    <span class="replied-date">@{{reply.created_at|convertDate|convertAgoTime}}</span>

                            </h4>
                            <p>@{{reply.comment}}</p>
                        </div>
                    </div>
                </div>


            </div>
            <div class="text-center">
                <a class="view-more-comments ripple" title="View More" ng-show="comment.next_page_url !== null" href="javascript:;" ng-click="loadmorecomment()">View More</a>
            </div>
            </uib-tab> <uib-tab heading="Queries (@{{(question.total)?question.total:0}})">
            <div class="media replied-question">
                <div class="media-left">
                    <span title="" class="replied-user img-circle">
                        <img alt="" class=" media-object" ng-src="@{{customerProfile.profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </span>
                </div>
                <div class="media-body">
                    <textarea ng-model="parentquestion" class="form-control textare-question" rows="3" placeholder="Leave your question here" maxlength="1000"></textarea>
                    <p class="text-right">
                        <button type="button" ng-click="parentquestion=''" class="btn btn-sm btn-cancel" ng-disabled="!parentquestion">Clear</button>
                        <button type="button" ng-click="postparentquestion(parentquestion)" class="btn btn-sm btn-action" ng-disabled="!parentquestion">Question</button>
                    </p>
                </div>
            </div>
            <div class="text-center cs-previous-comment">
                <a class="view-more-comments" ng-show="question.prev_page_url !== null" href="javascript:;" ng-click="loadprevquestion()">Show Previous</a>
            </div>
            <div class="media replied-question" ng-repeat="que in question.data">
                <div class="media-left">
                    <a href="javascript:;" title="" class="replied-user img-circle">
                        <img alt="" class=" media-object" ng-src="@{{que[que.user_type].profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">
                        @{{que[que.user_type].name}}
                        <span class="replied-date">@{{que.created_at|convertDate|convertAgoTime}}</span>
                    </h4>
                    <p>@{{que.questions}}</p>
                    <p>
                        <a href="javascript:;" ng-show="que.reply_answer[0].id" class="reply-link show-question" ng-click="isReplycontentOpen = isReplyFormOpen = !isReplycontentOpen">
                            <span ng-hide="isReplycontentOpen">Show reply questions</span>
                            <span ng-hide="!isReplycontentOpen">Hide reply questions</span>
                        </a>
                    </p>
                    <div ng-hide="!isReplycontentOpen" ng-repeat="subque in que.reply_answer">
                        <div class="media-left">
                            <a href="javascript:;" title="" class="replied-user img-circle">
                                <img alt="" class=" media-object" ng-src="@{{subque[subque.user_type].profile_picture}}"  err-src="{{$cdnUrl('images/user.png')}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                @{{subque[subque.user_type].name}}
                                <span class="replied-date">
                                    <span class="replied-date">@{{subque.created_at|convertDate|convertAgoTime}}</span>

                            </h4>
                            <p>@{{subque.answers}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a class="view-more-comments" ng-show="question.next_page_url !== null" href="javascript:;" ng-click="loadmorequestion()">View More</a>
            </div>
            </uib-tab> </uib-tabset>
        </div>
    </div>
</div>