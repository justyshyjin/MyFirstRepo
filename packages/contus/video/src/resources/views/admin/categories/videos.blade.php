@extends('base::layouts.default')

@section('stylesheet')
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')

<style type="text/css">
    .custom-color {
        color: #a94442;
    }
</style>

<div class="product order_list"  data-ng-controller="ViewVideoCategoriesController as vVideoCategoriesCtrl" data-ng-init=vVideoCategoriesCtrl.fetchData('{{$id}}')>
@include('video::admin.common.subMenu')

<div class="contentpanel clearfix video-conatiner" data-ng-if="!vVideoCategoriesCtrl.notFoundFlag">
               <div class="video-list-grid">
               <div data-ng-init="vVideoCategoriesCtrl.parentCategory('{{$id}}')" class="pagination_bredrumbs clearfix">
                <h4 class="pull-left">{{trans('video::videos.category')}} : <span>
                <i data-ng-if="vVideoCategoriesCtrl.parentCategoryTitle" data-ng-repeat="(key, record) in vVideoCategoriesCtrl.parentCategoryTitle" >@{{record}} /</i>
                <i>@{{vVideoCategoriesCtrl.videoCategories.title}}</i>
                </span>
              </h4>
                <ul data-ng-if="false" class="pull-right">
                 <li><a href="#" title="" data-ng-class="{'active': vVideoCategoriesCtrl.videoGridView}" data-ng-click="vVideoCategoriesCtrl.showGridView()"><i class="line-grid-icon" ></i></a></li>
                  <li><a href="#" title="" data-ng-class="{'active': vVideoCategoriesCtrl.videoListView}" data-ng-click="vVideoCategoriesCtrl.showListView()"><i class="line-list-icon"></i></a></li>
                </ul>
                </div>

               </div>
               <div data-ng-if="vVideoCategoriesCtrl.videoCategories.videos == ''" style="text-align: center;width: 100%;margin-top:15px;" colspan="@{{heading.length + 2}}" class="no-data">{{trans('base::general.not_found')}}</div>
                <ul class="video-conatiner-list" data-ng-if="vVideoCategoriesCtrl.videoCategories.videos != ''" data-ng-show="vVideoCategoriesCtrl.videoGridView">
                	<li data-ng-repeat = "record in vVideoCategoriesCtrl.videoCategories.videos track by $index">
                        <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" title="@{{record.title}}">
                            <img data-ng-if="record.videoposter.length > 0" data-ng-src="@{{ record.videoposter[0].image_url }}" alt="" width="" height="" />
                            <img data-ng-if="record.videoposter.length == 0 && record.thumbnail_image.length > 0" data-ng-src="@{{ record.thumbnail_image }}" alt="" width="" height="" />
              				<img data-ng-if="record.videoposter.length == 0 && record.thumbnail_image.length == 0" data-ng-src="@{{ record.transcodedvideos[0].thumb_url }}" alt="" width="" height="" />
                            <i class="fa fa-play-circle" aria-hidden="true"></i>
                        </a>
                        <span class="video-listing-review">7.9</span>
                        <div class="video-conatiner-list-detail">
                            <h5>@{{record.title}}</h5>
                            <ul>
                                <li data-ng-repeat="category in record.videocategory track by $index">
                                	<a href="{{url('admin/categories/videos')}}/@{{category.category_id}}">@{{ category.category.title }}</a><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                </li>
                            </ul>
                        </div>
                        <!-- hover tooltip -->
                        <div class="video-list-hover_detail">
                            <h5><strong data-ng-bind="vVideoCategoriesCtrl.getTrimmedString(record.title, 100)"></strong><span class="video-list-date">(@{{ $root.getFormattedDate(record.created_at) }})</span><span class="video-listing-hover-review">7.9</span></h5>

                            <ul>
                                <li data-ng-repeat="category in record.videocategory track by $index">
                                	<span>@{{ category.category.title }}</span><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                </li>
                            </ul>
                            <div class="view-like">
                                <span><i class="fa fa-heart" aria-hidden="true"></i>1234</span>
                                <span><i class="fa fa-eye" aria-hidden="true"></i>854</span>
                            </div>
                            <p data-ng-bind="vVideoCategoriesCtrl.getTrimmedString(record.short_description, 100)"></p>
                            <div class="video-list-cast">
                                <p data-ng-if="record.transcodedvideos.length > 0">{{trans('video::videos.presets')}}:<span data-ng-bind="vVideoCategoriesCtrl.getVideoPresets(record)"></span></p>

                                <p>Director:<span>Peter Chelson</span>
                                </p>
                                <p>Writers:<span>Allon Loeb <abbr>(screenplay)</abbr></span><span>Stewart Schill <abbr>(Story)</abbr></span>
                                </p>
                                <p data-ng-if="record.video_cast.length > 0">{{trans('video::videos.cast')}}:<span data-ng-bind="vVideoCategoriesCtrl.getVideoCasts(record)"></span></p>

                            </div>
                        </div>
                    </li>
                </ul>
        		<div class="video_listing_view" data-ng-if="vVideoCategoriesCtrl.videoCategories.videos != ''" data-ng-show="vVideoCategoriesCtrl.videoListView">
                  <div data-ng-repeat = "record in vVideoCategoriesCtrl.videoCategories.videos track by $index" class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">
                      <div class="video_list_img">
                          <img class="img-responsive" src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.selected_thumb }}" alt="" />
                          <i class="fa fa-play-circle" aria-hidden="true"></i>
                          <!-- <div class="video_timing">2.05</div> -->
                      </div>
                    </a>
                    <div class="video_list_info">
                        <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="video-list-title">@{{record.title}}</a>
                        <!-- <a href="#" class="video_list_info_category">@{{record.short_description}}</a> -->
                        <li data-ng-repeat="category in record.videocategory track by $index">
                            <a href="{{url('admin/categories/videos')}}/@{{category.category_id}}">@{{ category.category.title }}</a><span data-ng-if="record.videocategory.length != $index+1">,</span>
                        </li>
                        <span>@{{record.recent.length}} views</span>
                        <p>@{{record.short_description}}</p>
                    </div>
                  </div>
                </div>
                <div class="pagination_custom clearfix">
                    <ul class="pagination pagination-split nomargin pull-right" data-ng-if="links.length > 0">
                        <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                            <a href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="pageLink" >@{{link.value}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="error-page" data-ng-if="vVideoCategoriesCtrl.notFoundFlag">
            	<h4>{{ trans('base::general.404_not_found') }}</h4>
            	<p>{{ trans('base::general.not_found_text') }}</p>
            </div>
</div>
@endsection
@section('scripts')
  <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
  <script src="{{$getVideoAssetsUrl('js/categories/videos.js')}}"></script>
@endsection