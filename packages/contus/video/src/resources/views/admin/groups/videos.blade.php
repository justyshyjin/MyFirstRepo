@extends('base::layouts.default') @section('stylesheet') @endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<style type="text/css">
.custom-color {
    color: #a94442;
}
</style>
<div class="product order_list" data-ng-controller="ViewVideogroupsController as vvideoplaylistsCtrl" data-ng-init=vvideoplaylistsCtrl.fetchData('{{$id}}')>
    @include('video::admin.common.subMenu')
    <div class="contentpanel clearfix video-conatiner" data-ng-if="!vvideoplaylistsCtrl.notFoundFlag">
        <div class="video-list-grid">
            <div class="pagination_bredrumbs clearfix">
                <h4 class="pull-left">
                   Exam Groups :
                    <span>
                   <i>@{{vvideoplaylistsCtrl.videogroups.title}}</i>
                    </span>
                </h4>
                <ul data-ng-hide="true" data-ng-if="vvideoplaylistsCtrl.videogroups.videos != ''" class="pull-right">
                    <li>
                        <a href="#" title="" data-ng-class="{'active': vvideoplaylistsCtrl.videoGridView}" data-ng-click="vvideoplaylistsCtrl.showGridView()">
                            <i class="line-grid-icon"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" title="" data-ng-class="{'active': vvideoplaylistsCtrl.videoListView}" data-ng-click="vvideoplaylistsCtrl.showListView()">
                            <i class="line-list-icon"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div data-ng-if="vvideoplaylistsCtrl.videogroups.videos == ''" style="text-align: center; width: 100%; margin-top: 15px;" colspan="@{{heading.length + 2}}" class="no-data">{{trans('base::general.not_found')}}</div>
        <ul class="video-conatiner-list" data-ng-if="vvideoplaylistsCtrl.videogroups.videos != ''" >
            <li data-ng-repeat="record in vvideoplaylistsCtrl.videogroups.videos track by $index">
            <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" title="@{{record.title}}">
                    <img  data-ng-src="@{{ record.selected_thumb}}" alt="" width="" height="" />
                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                </a>
                <span class="video-listing-review">7.9</span>
                <div class="video-conatiner-list-detail">
                  <h5>@{{record.title}}</h5>
                    <ul>
                        <li data-ng-repeat="category in record.videocategory track by $index">
                            <a href="{{url('admin/categories/videos')}}/@{{category.category_id}}">@{{ category.category.title }}</a>
                            <span data-ng-if="record.videocategory.length != $index+1">,</span>
                        </li>
                    </ul>
                </div>
                <span class="delete_table_icon" title="{{trans('video::playlist.remove_video_from_playlist')}}" data-toggle="modal" data-target="#removeVideoModal" data-ng-click="vvideoplaylistsCtrl.removeVideosFromCollection(record.id)" data-boot-tooltip="true">
                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                </span>
                <!-- hover tooltip -->
                <div class="video-list-hover_detail">
                    <h5>
                        <strong data-ng-bind="vvideoplaylistsCtrl.getTrimmedString(record.title, 100)"></strong>
                        <span class="video-list-date">(@{{ $root.getFormattedDate(record.created_at) }})</span>
                        <span class="video-listing-hover-review">7.9</span>
                    </h5>
                    <ul>
                        <li data-ng-repeat="category in record.videocategory track by $index">
                            <span>@{{ category.category.title }}</span>
                            <span data-ng-if="record.videocategory.length != $index+1">,</span>
                        </li>
                    </ul>
                    <div class="view-like">
                        <span>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            1234
                        </span>
                        <span>
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            854
                        </span>
                    </div>
                    <p data-ng-bind="vvideoplaylistsCtrl.getTrimmedString(record.short_description, 100)"></p>
                    <div class="video-list-cast">
                        <p data-ng-if="record.transcodedvideos.length > 0">
                            {{trans('video::videos.presets')}}:
                            <span data-ng-bind="vvideoplaylistsCtrl.getVideoPresets(record)"></span>
                        </p>
                        <p>
                            Director:
                            <span>Peter Chelson</span>
                        </p>
                        <p>
                            Writers:
                            <span>
                                Allon Loeb
                                <abbr>(screenplay)</abbr>
                            </span>
                            <span>
                                Stewart Schill
                                <abbr>(Story)</abbr>
                            </span>
                        </p>
                        <p data-ng-if="record.video_cast.length > 0">
                            {{trans('video::videos.cast')}}:
                            <span data-ng-bind="vvideoplaylistsCtrl.getVideoCasts(record)"></span>
                        </p>
                    </div>
                </div>
            </li>
        </ul>
        <div class="video_listing_view" data-ng-if="vvideoplaylistsCtrl.videoplaylists.videos != ''" data-ng-show="vvideoplaylistsCtrl.videoListView">
            <div data-ng-repeat="record in vvideoplaylistsCtrl.videoplaylists.videos track by $index" class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">
                    <div class="video_list_img">
                        <img class="img-responsive" data-ng-src="@{{ record.selected_thumb }}" src="{{url('contus/base/images/no-preview.png')}}" alt="" />
                        <img class="img-responsive" data-ng-if="record.thumbnail_image.length == 0" data-ng-src="@{{ record.transcodedvideos[0].thumb_url }}" alt="" />
                        <i class="fa fa-play-circle" aria-hidden="true"></i>
                        <!-- <div class="video_timing">2.05</div> -->
                    </div>
                </a>
                <div class="video_list_info">
                    <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="video-list-title">@{{record.title}}</a>
                    <!-- <a href="#" class="video_list_info_category">@{{record.short_description}}</a> -->
                    <li data-ng-repeat="category in record.videocategory track by $index">
                        <a href="{{url('admin/categories/videos')}}/@{{category.category_id}}">@{{ category.category.title }}</a>
                        <span data-ng-if="record.videocategory.length != $index+1">,</span>
                    </li>
                    <span>@{{record.recent.length}} views</span>
                    <p>@{{record.short_description}}</p>
                </div>
                <span class="delete_table_icon" title="{{trans('video::playlist.remove_video_from_playlist')}}" data-toggle="modal" data-target="#removeVideoModal" data-ng-click="vvideoplaylistsCtrl.removeVideosFromCollection(record.id)" data-boot-tooltip="true">
                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                </span>
            </div>
        </div>
        <div class="pagination_custom clearfix">
            <ul class="pagination pagination-split nomargin pull-right" data-ng-if="links.length > 0">
                <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                    <a href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class="pageLink">@{{link.value}}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="error-page" data-ng-if="vvideoplaylistsCtrl.notFoundFlag">
        <h4>{{ trans('base::general.404_not_found') }}</h4>
        <p>{{ trans('base::general.not_found_text') }}</p>
    </div>
    <div class="modal fade" id="removeVideoModal" data-role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{trans('video::playlist.remove_video_from_playlist')}}</h5>
                </div>
                <div class="modal-body">
                    <p>{{trans('video::playlist.confirm_remove_video_from_playlist')}}</p>
                </div>
                <div class="clearfix modal-footer video_delete_footer">
                    <span class="btn btn-danger pull-right" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vvideoplaylistsCtrl.confirmRemoveVideosFromCollection()" class="btn btn-primary pull-right mr10" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/groups/videos.js')}}"></script>
@endsection
