@extends('base::layouts.default') @section('stylesheet')
<link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<style type="text/css">
.custom-color {
    color: #a94442;
}
</style>
<div class="product order_list" data-ng-controller="ViewVideoDetailsController as vVideoDetailsCtrl" data-ng-init=vVideoDetailsCtrl.fetchData('{{$id}}')>
    @include('video::admin.common.subMenu')
    <div class="contentpanel clearfix video-detail" data-ng-if="!vVideoDetailsCtrl.notFoundFlag">
        <!-- video detail left panel -->
        <div class="video-detail-left-panel">
            <div class="video-detail-profile-images">
                <img src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ video.selected_thumb }}" alt="" width="" height="" />
                <span class="active-label" ng-if="vVideoDetailsCtrl.video.is_active == 1">{{trans('video::videos.message.active')}}</span>
                <span class="inactive-label" ng-if="vVideoDetailsCtrl.editVideo.is_active != 1">{{trans('video::videos.message.inactive')}}</span>
            </div>
            <div class="video-detail-title">
                <h4>@{{vVideoDetailsCtrl.editVideo.title}}</h4>
                <ul>
                    <li data-ng-repeat="category in video.categories track by $index">
                        <span>@{{ category.title }}</span>
                        <span data-ng-if="video.categories.length != $index+1">,</span>
                    </li>
                </ul>
            </div>
            <h5 ng-if="(video.mp3)||(video.pdf)||(video.word)">Downloads</h5>
            <div class="video-detail-views" ng-if="video.mp3">
                <!-- <span><i class="time-icon"></i> 2h 24min </span> -->
                <span>
                    <a target="_blank" href="@{{video.mp3}}">MP3</a>
                </span>
            </div>
            <div class="video-detail-views" ng-if="video.pdf">
                <!-- <span><i class="time-icon"></i> 2h 24min </span> -->
                <span>
                    <a target="_blank" href="@{{video.pdf}}">PDF</a>
                </span>
            </div>
            <div class="video-detail-views" ng-if="video.word">
                <!-- <span><i class="time-icon"></i> 2h 24min </span> -->
                <span>
                    <a target="_blank" href="@{{video.word}}">WORD</a>
                </span>
            </div>
            <div class="video-detail-views" data-ng-hide="video.youtube_live">
                <!-- <span><i class="time-icon"></i> 2h 24min </span> -->
                <span>
                    <i class="view-icon"></i>
                    @{{video.recent}} Views
                </span>
            </div>

            <div class="video-description" data-ng-if="vVideoDetailsCtrl.editVideo.subscription != ''">
                <label>{{ trans('video::videos.subscription') }}</label>
                <p>@{{vVideoDetailsCtrl.editVideo.subscription}}</p>
            </div>
        </div>
        <!-- video detail right panel -->
        <div class="video-detail-right-panel">
            <div class="clearfix play-video-container">
                <div class="play-video">
                    <div class="play-video-emped">
                        <div init-flow-player video="video" class="flowplayer functional"></div>
                    </div>
                    <div class="video-detail-title">
                        <div class="play-video-detail" data-ng-hide="video.youtube_live">
                            <h5>{{ trans('video::videos.categories') }}</h5>
                            <p>
                            <li data-ng-repeat="category in video.categories track by $index">
                                <span>@{{ category.parent_category.parent_category.title }}</span>
                                <span data-ng-if="category.length != $index+1"> > </span>
                                <span>@{{ category.parent_category.title }}</span>
                                <span data-ng-if="category.parent_category.length != $index+1"> > </span>
                                <a href="{{url('admin/categories/videos')}}/@{{category.id}}">@{{ category.title }}</a>

                            </li>
                            </p>
                        </div>
                    </div>
                    <div class="play-video-detail" data-ng-if="vVideoDetailsCtrl.editVideo.description != ''">
                        <h5>{{ trans('video::videos.description') }}</h5>
                        <p data-ng-bind="vVideoDetailsCtrl.editVideo.descriptionContent"></p>
                        <a href="javascript:void(0);" title="" class="view-cast" data-ng-show="vVideoDetailsCtrl.editVideo.trimFlag" data-ng-click="vVideoDetailsCtrl.showFullDescription()">
                            Read More
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="video-tags" data-ng-if="vVideoDetailsCtrl.editVideo.tags != ''">
                        <h5>{{ trans('video::videos.tags') }}</h5>
                        <ul>
                            <li data-ng-repeat="tag in vVideoDetailsCtrl.editVideo.tags">
                                <a title="">@{{ tag.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="video-download" data-ng-if="vVideoDetailsCtrl.editVideo.subtitle != ''">
                        <a href="{{ url('uploads/subtitle') }}/@{{ vVideoDetailsCtrl.editVideo.subtitle }}" target="_blank" title="" class="">
                            <i></i>
                            @{{ vVideoDetailsCtrl.editVideo.subtitle }}
                        </a>
                        <span>{{ trans('video::videos.subtitle') }}</span>
                    </div>
                </div>
                <div class="play-video-column">
                    <div class="play-video-format">
                        <ul>
                            <li>
                                <div class="mp4-format">
                                    <i class="format-icon"></i>
                                    <div class="mp4-format-title" data-ng-hide="video.youtube_live">
                                        <h5>HLS</h5>
                                        <p>m3u8 Format</p>
                                    </div>
                                    <div class="mp4-format-title" data-ng-show="video.youtube_id">
                                        <h5>Live</h5>
                                        <p>youtube</p>
                                    </div>
                                    <div class="mp4-format-title" data-ng-show="video.username">
                                        <h5>Live</h5>
                                        <p>Wowza</p>
                                    </div>
                                </div>
                            </li>
                            <li data-ng-hide="video.youtube_live">
                                <div class="mp4-format">
                                    <i class="popularity-icon"></i>
                                    <div class="mp4-format-title">
                                        <h5>@{{video.video_duration}}</h5>
                                        <p>Duration</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="mp4-format">
                                    <i class="review-star-icon"></i>
                                    <div class="mp4-format-title">
                                        <h5>@{{video.authfavourites}}</h5>
                                        <p>Favorites</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="video-writer">
                        <div class="video-writer-detail" ng-if="video.presenter">
                            <label>Presenter</label>
                            <p>@{{video.presenter}}</p>
                        </div>
                        <div class="video-writer-detail" data-ng-hide="video.youtube_live">
                            <label>Genre</label>

                            <p data-ng-repeat="category in video.collections track by $index">

		                                <span>@{{ category.name }}</span>
                                <span data-ng-if="video.collections.length != $index+1">,</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- cast container -->
            <div class="video-detail-grid clearfix" data-ng-if="vVideoDetailsCtrl.editVideo.video_cast != ''">
                <div class="comments-answer-tab">
                    <div class="tabbable-panel">
                        <uib-tabset class="tabbable-line" active="activeIndexTab"> <uib-tab heading="Comments (@{{(comment.total)?comment.total:0}})">
                        <div class="media replied-comment">
                            <div class="media-left">
                                <a title="" class="replied-user img-circle">
                                    <img alt="" class=" media-object" ng-src="{{auth()->user()->profile_image}}" src="{{url('contus/base/images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                                </a>
                            </div>
                            <div class="media-body">
                                <textarea ng-model="parentcomment" class="form-control textare-comment" rows="3" placeholder="Comment" maxlength="1000"></textarea>
                                <p class="text-right">
                                    <button title="Cancel" type="button" ng-click="parentcomment=''" class="btn btn-sm btn-cancel">Clear</button>
                                    <button title="Comment" type="button" ng-click="postparentcomment(parentcomment)" class="btn btn-sm btn-action">Comment</button>
                                </p>
                            </div>
                        </div>
                        <div class="text-center">
                            <a title="Show Previous" class="view-more-comments" ng-show="comment.prev_page_url !== null" href="javascript:;" ng-click="loadprevcomment()">Show Previous</a>
                        </div>
                        <div class="media replied-comment" ng-repeat="com in comment.data">
                            <div class="media-left">
                                <a title="" class="replied-user img-circle">
                                    <img alt="" class=" media-object" ng-src="@{{com[com.user_type].profile_picture}}" src="{{url('contus/base/images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">
                                    @{{com[com.user_type].name}}
                                    <span class="replied-date">@{{com.created_at|convertDate|convertAgoTime}}</span>
                                </h4>
                                <p>@{{com.comment}}</p>
                                <span class="label label-success" ng-if="com.is_active == 1" style="cursor: pointer;" data-ng-click="updateStatus(com,'0')" title="{{trans('video::videos.deactivate_comment')}}" data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span>
                                <span class="label label-danger" ng-if="com.is_active != 1" style="cursor: pointer;" data-ng-click="updateStatus(com,'1')" title="{{trans('video::videos.activate_comment')}}" data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span>

                            <!--Reply comments section   -->
                            <p>
                                    <a href="javascript:;" class="reply-link" ng-click="isReplyFormOpen = !isReplyFormOpen">
                                        <span ng-hide="isReplyFormOpen">Reply</span>
                                        <span ng-hide="!isReplyFormOpen">close</span>
                                    </a>
                                    <a href="javascript:;" ng-show="que.reply_answer[0].id" class="reply-link show-question" ng-click="isReplycontentOpen = isReplyFormOpen = !isReplycontentOpen">
                                        <span ng-hide="isReplycontentOpen">Show </span>
                                        <span ng-hide="!isReplycontentOpen">Hide </span> reply comment
                                    </a>
                                </p>
                                <div ng-show="isReplyFormOpen" class="media replied-question">
                                    <div class="media-body">
                                        <textarea ng-model="childcomment[com.id]" class="form-control textare-question" rows="3" placeholder="Comment" maxlength="1000"></textarea>
                                        <p class="text-right">
                                            <button type="button" ng-click="childcomment=''" class="btn btn-sm btn-cancel">Clear</button>
                                            <button type="button" ng-click="postchildcomment(com.id,childcomment[com.id])" class="btn btn-sm btn-action">Submit</button>
                                        </p>
                                    </div>
                                </div>
                                <div ng-repeat="reply in com.reply_comment" class="comment-reply">
                                    <div class="media-left">
                                        <a href="javascript:;" title="" class="replied-user img-circle">
                                            <img alt="" class=" media-object" ng-src="@{{reply[reply.user_type].profile_picture}}" src="{{$cdnUrl('images/user.png')}}" data-holder-rendered="true" style="width: 64px; height: 64px;">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            @{{reply[reply.user_type].name}}
                                            <span class="replied-date">
                                                <span class="replied-date">@{{reply.created_at|convertDate|convertAgoTime}}</span>
                                            </span>
                                        </h4>
                                        <p>@{{reply.comment}}</p>
                                    </div>
                                </div>
                            </div>
                          <!--Reply comments section   -->

                        </div>
                        <div class="text-center">
                            <a class="view-more-comments" title="View More" ng-show="comment.next_page_url !== null" href="javascript:;" ng-click="loadmorecomment()">View More</a>
                        </div>
                        </uib-tab> </uib-tabset>
                    </div>
                </div>
            </div>
            <!-- <a href="#" title="" class="view-cast">See full cast <i class="fa fa-angle-double-right" aria-hidden="true"></i></a> -->
        </div>
        <!-- posters container -->
    </div>
</div>
<div class="error-page" data-ng-if="vVideoDetailsCtrl.notFoundFlag">
    <h4>{{ trans('base::general.404_not_found') }}</h4>
    <p>{{ trans('base::general.not_found_text') }}</p>
</div>
<div data-ng-if="vVideoDetailsCtrl.editVideo.trailerId != ''" id="trailerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('video::videos.trailer')}}</h5>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
</div>
@endsection @section('scripts')
<link href="{{$getBaseAssetsUrl('flowplayer/skin/flowplayer.quality-selector.css')}}" type="text/css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('flowplayer/skin/skin.css')}}" type="text/css" rel="stylesheet">
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('flowplayer/flowplayer.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('flowplayer/flowplayer.hlsjs.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/libs/ui-bootstrap/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/viewVideoDetail.js')}}"></script>
@endsection
