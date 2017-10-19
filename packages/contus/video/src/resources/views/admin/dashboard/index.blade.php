@extends('base::layouts.default') 


@section('header')
    @include('base::layouts.headers.dashboard') 
@endsection
@section('content')
<div data-ng-controller="DashboardController as dashCtrl" >

<div class="menu_container clearfix">
                <div class="page_menu pull-left">

                    <ul class="nav">

                        <li><a href="{{url('admin/dashboard')}}" title="" class="active">{{__('base::adminsidebar.dashboard')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            @if(isset($_GET['permission']))
                <div class="alert alert-danger">You are not authorized to perform this action. Please contact your admin.</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">{{ implode('', $errors->all(':message')) }}</div>
            @endif
            <div class="main_container clearfix top_content panel">
           
                <div class="col-sm-7 monthly_summary_details">
                    <div class="monthly_summary">
                      
                        <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget-panel widget-style-2 white-bg">
                            <i class="fa fa-play-circle text-pink " aria-hidden="true"></i>
                            <h2 class="m-0 counter">@{{ dashCtrl.totalNumberOfVideos }}</h2>
                            <div>{{ __('video::dashboard.total_videos') }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget-panel widget-style-2 white-bg">
                            <i class="fa fa-spinner text-purple" aria-hidden="true"></i>
                            <h2 class="m-0 counter">@{{ dashCtrl.totalProgressingVideos }}</h2>
                            <div>{{ __('video::dashboard.total_progressing_videos') }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget-panel widget-style-2 white-bg">
                            <i class="fa fa-sliders text-info" aria-hidden="true"></i>
                            <h2 class="m-0 counter">@{{ dashCtrl.totalVideoPresets }}</h2>
                            <div>{{ __('video::dashboard.total_video_presets') }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget-panel widget-style-2 white-bg">
                            <i class="fa fa-sliders text-green" aria-hidden="true"></i>
                            <h2 class="m-0 counter">@{{ dashCtrl.activeVideoPresets }}</h2>
                            <div>{{ __('video::dashboard.active_video_presets') }}</div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
                <div class="col-sm-5 pull-right graph_container_details">
                    <div class="graph_container">
                    <h3>{{ __('video::dashboard.video_upload_chart') }}</h3>
                       <div id="line-example" style="height: 290px;"></div>
                    </div>
                </div>

            </div>
            
            <div class="contentpanel clearfix">
                <div class="panel main_container">
                    <h4>{{ __('video::dashboard.overview') }}</h4>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#latest_videos" data-toggle="tab">{{ __('video::dashboard.latest_videos') }}</a>
                        </li>
                        <li class=""><a href="#progressing_videos" data-toggle="tab">{{ __('video::dashboard.progressing_videos') }}</a>
                        </li>
                         <li class=""><a href="#top_categories" data-toggle="tab">{{ __('video::dashboard.top_categories') }}</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                    <div class="tab-pane active" id="latest_videos">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="center">{{ __('video::dashboard.s_no') }}</th>
                                <th>{{ __('video::dashboard.title') }}</th>
                                <th>{{ __('video::dashboard.categories') }}</th>
                                <th>{{ __('video::dashboard.no_of_presets') }}</th>
                                <th>{{ __('video::dashboard.status') }}</th>
                                <th>{{ __('video::dashboard.upload_status') }}</th>
                                <th>{{ __('video::dashboard.added_on') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr data-ng-if="dashCtrl.latestVideos.length == 0">
                				<td colspan="7" class="no-data">{{__('base::general.not_found')}}</td>
            				</tr>
                            <tr data-ng-if="dashCtrl.latestVideos.length > 0" data-ng-repeat="record in dashCtrl.latestVideos">
                                <td class="center">@{{ $index +1 }}</td>
                                <td>
                                    <div class="product_img">
                                    	<span class="img_title">
                                    		<img data-ng-if="record.thumbnail_image.length > 0" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                							<img data-ng-if="record.thumbnail_image.length == 0" data-ng-src="@{{ record.__codedvideos[0].thumb_url }}" alt="" />
                                    	</span>
                                        <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="img_description">@{{record.title}}</a>
                                    </div>
                                </td>
                                <td>
                                	<div data-ng-repeat="category in record.videocategory track by $index">
                                    	<span>@{{ category.category.title }}</span><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                    </div>
                                </td>
                                <td>@{{ record.__codedvideos.length }}</td>
                                <td>
                                	<span class="label label-success" ng-if="record.is_active == 1" >{{__('video::videos.message.active')}}</span>
                    				<span class="label label-danger" ng-if="record.is_active != 1" >{{__('video::videos.message.inactive')}}</span>
                                </td>
                                <td>
                                	<span class="label label-primary" ng-if="record.job_status == 'Video Uploaded'">@{{record.job_status}}</span>
                                	<span class="label label-warning" ng-if="record.job_status == 'Progressing'">@{{record.job_status}}</span>
                                	<span class="label label-success" ng-if="record.job_status == 'Complete'">@{{record.job_status}}</span>
                                	<span class="label label-danger" ng-if="record.job_status == 'Error'">@{{record.job_status}}</span>
                                </td>
                                <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="tab-pane" id="progressing_videos">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="center">{{ __('video::dashboard.s_no') }}</th>
                                    <th>{{ __('video::dashboard.title') }}</th>
                                    <th>{{ __('video::dashboard.categories') }}</th>
                                    <th>{{ __('video::dashboard.no_of_presets') }}</th>
                                    <th>{{ __('video::dashboard.status') }}</th>
                                    <th>{{ __('video::dashboard.upload_status') }}</th>
                                    <th>{{ __('video::dashboard.added_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr data-ng-if="dashCtrl.progressingVideos.length == 0">
                					<td colspan="7" class="no-data">{{__('base::general.not_found')}}</td>
            					</tr>
                                <tr data-ng-if="dashCtrl.progressingVideos.length > 0" data-ng-repeat="record in dashCtrl.progressingVideos">
                                    <td class="center">@{{ $index +1 }}</td>
                                    <td>
                                        <div class="product_img">
                                        	<span class="img_title">
                                        		<img data-ng-if="record.thumbnail_image.length > 0" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                    							<img data-ng-if="record.thumbnail_image.length == 0" data-ng-src="@{{ record.__codedvideos[0].thumb_url }}" alt="" />
                                        	</span>
                                            <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="img_description">@{{record.title}}</a>
                                        </div>
                                    </td>
                                    <td>
                                    	<div data-ng-repeat="category in record.videocategory track by $index">
                                        	<span>@{{ category.category.title }}</span><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                        </div>
                                    </td>
                                    <td>@{{ record.__codedvideos.length }}</td>
                                    <td>
                                    	<span class="label label-success" ng-if="record.is_active == 1" >{{__('video::videos.message.active')}}</span>
                        				<span class="label label-danger" ng-if="record.is_active != 1" >{{__('video::videos.message.inactive')}}</span>
                                    </td>
                                    <td>
                                    	<span class="label label-primary" ng-if="record.job_status == 'Video Uploaded'">@{{record.job_status}}</span>
                                    	<span class="label label-warning" ng-if="record.job_status == 'Progressing'">@{{record.job_status}}</span>
                                    	<span class="label label-success" ng-if="record.job_status == 'Complete'">@{{record.job_status}}</span>
                                    	<span class="label label-danger" ng-if="record.job_status == 'Error'">@{{record.job_status}}</span>
                                    </td>
                                    <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="top_categories">
                    	<table class="table">
                            <thead>
                                <tr>
                                    <th class="center">{{ __('video::dashboard.s_no') }}</th>
                                    <th>{{ __('video::dashboard.category_name') }}</th>
                                    <th>{{ __('video::dashboard.parent_category') }}</th>
                                    <th>{{ __('video::dashboard.status') }}</th>
                                    <th>{{ __('video::dashboard.added_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr data-ng-if="dashCtrl.topCategories.length == 0">
                					<td colspan="5" class="no-data">{{__('base::general.not_found')}}</td>
            					</tr>
                                <tr data-ng-if="dashCtrl.topCategories.length > 0" data-ng-repeat="record in dashCtrl.topCategories">
                                    <td class="center">@{{ $index +1 }}</td>
                                    <td>@{{record.title}}</td>
                                    <td>
                                    	<span data-ng-if="record.parent_category != null">@{{record.parent_category}}</span>
                                    	<span data-ng-if="record.parent_category == null">-</span>
                                    </td>
                                    <td>
                                    	<span class="label label-success" ng-if="record.is_active == 1" >{{__('video::videos.message.active')}}</span>
                        				<span class="label label-danger" ng-if="record.is_active != 1" >{{__('video::videos.message.inactive')}}</span>
                                    </td>
                                    <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            var loader = $('#preloader');
            loader.find('#status').css('display','none');
            loader.css('display','none');
        });        
    </script>
    <script src="{{$getBaseAssetsUrl('js/raphael-min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/morris-0.4.1.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/dashboard/dashboard.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection