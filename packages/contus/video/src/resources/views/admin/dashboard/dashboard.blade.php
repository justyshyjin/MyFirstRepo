@extends('base::layouts.default')


@section('header')
@include('base::layouts.headers.dashboard')
@endsection
@section('content')
<div data-ng-controller="DashboardController as dashCtrl" >

<div class="menu_container clearfix">
                <div class="page_menu pull-left">

                    <ul class="nav">

                        <li><a href="{{url('admin/dashboard')}}" title="" class="active">{{trans('base::adminsidebar.dashboard')}}</a>
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
            <div class="main_container clearfix  panel dashbord-section">

                <div class="clearfix count-list">
                    <div class="col-lg-3 col-sm-3">
                        <h5><i class="aws-biiling-icon"></i>AWS Billing</h5>
                        <div class="count-list-price">
                            <i class="aws-down-icon" data-ng-if="dashCtrl.monthlyAwsCost[0].total_cost < dashCtrl.monthlyAwsCost[1].total_cost"></i>
                            <i class="aws-up-icon" data-ng-if="dashCtrl.monthlyAwsCost.length == 1 || dashCtrl.monthlyAwsCost[0].total_cost > dashCtrl.monthlyAwsCost[1].total_cost"></i>
                            <div class="count-list-price-details">
                                <p>Monthly Expense</p>
                                <div class="unit-price" >$ @{{ (dashCtrl.monthlyAwsCost[0].total_cost)?dashCtrl.monthlyAwsCost[0].total_cost:0 }} <span>USD</span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <h5> <i class="revenue-icon"></i>REVENUE STATS</h5>
                        <div class="count-list-price" id="revenue">
                            <div class="count-list-price-details">
                                <p> </br>&nbsp;Total Revenue</p>
                                <div class="unit-price">&#x20b9;  @{{ (dashCtrl.total_revenue)?dashCtrl.total_revenue:0|INR }} <span>INR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <h5><i class="aws-video-icon"></i>Total Videos</h5>
                        <div class="count-list-price">

                            <div class="count-list-price-details">
                                <p>Videos Added</p>
                                <div class="unit-price">@{{ dashCtrl.totalNumberOfVideos }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3">
                        <h5><i class="aws-user-icon"></i>Total Users</h5>
                        <div class="count-list-price">
                            <div class="count-list-price-details">
                                <p>Subscribed Users</p>
                                <div class="unit-price">@{{ dashCtrl.subcribedCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix user-analytics">
                    <h3>User Analytics</h3>
                    <div class="col-md-8 dashbord-graph">
                    	<div id="users-chart" style="height: 350px; width: 90%;"></div>
                    </div>
                    <div class="col-md-4 pull-right">
                          <div class="processing-status">
                            <h5>@{{dashCtrl.totalNumberOfActiveCustomer}}/@{{dashCtrl.totalNumberOfCustomer}}</h5>
                            <div class="video-processing clearfix ">
                                <p>Active Users</p><span>@{{dashCtrl.activeCustomerPercent}}% <i></i></span>
                            </div>
                            <div class="progress tvod">

                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:@{{dashCtrl.activeCustomerPercent}}%">
                                </div>
                            </div>
                        </div>
                          <div class="processing-status">
                            <h5>@{{dashCtrl.totalNumberOfInactiveCustomer}}/@{{dashCtrl.totalNumberOfCustomer}}</h5>
                            <div class="video-processing clearfix">
                                <p>Inactive Users</p><span>@{{dashCtrl.inactiveCustomerPercent}}% <i class="down-status-icon"></i></span>
                            </div>
                            <div class="progress rvod">

                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:@{{dashCtrl.inactiveCustomerPercent}}%">
                                </div>
                            </div>
                        </div>
                        <div class="user-progress-status">

                        	<div class="circular-progress-bar position" data-percent="@{{dashCtrl.activeVideoPercent}}" data-duration="@{{dashCtrl.totalNumberOfVideos}}" data-color="#e5e5e5,#97d383"><span class="progress-title">Active Videos</span></div>
							<div class="circular-progress-bar position" data-percent="@{{dashCtrl.inactiveVideoPercent}}" data-duration="@{{dashCtrl.totalNumberOfVideos}}" data-color="#e5e5e5,#00d9fe"><span class="progress-title">Inactive Videos</span></div>
							<div class="circular-progress-bar position" data-percent="@{{dashCtrl.liveVideoPercent}}" data-duration="@{{dashCtrl.totalNumberOfVideos}}" data-color="#e5e5e5,#6169c1"><span class="progress-title">Live Videos</span></div>
                        </div>
                    </div>
                </div>

                <div class="clearfix aws-billing-cost">
                   <div class="aws-billing-cost-left-column">
                     <div class="aws-billing-cost-heading"><p>AWS Billing/ Cost</p><span data-ng-if="dashCtrl.monthlyAwsCost.length > 0">Last updated on @{{ $root.getFormattedDate(dashCtrl.monthlyAwsCost[0].updated_at) }}</span>
                      <h5>$@{{  (dashCtrl.totalAwsCost)?dashCtrl.totalAwsCost:0 }} <span>USD</span></h5>

                     </div>
                   </div>
                   <div class="aws-billing-cost-right-column">
                    <div class="aws-billing-cost-graph">
                    	<div ng-hide = true id="aws-chart" style="height: 170px; width: 170px;"></div>
                    </div>
                    <div class="aws-billing-cost-graph-details">
                      <h5>Amazon Web Services Usage</h5>
                      <ul>
                       <li>
                        <h4>@{{ dashCtrl.awsStats[1].option_value }}</h4>
                        <p>Total Consumed Space in S3</p>
                       </li>
                        <li>
                        <h4>@{{ dashCtrl.awsStats[0].option_value }}</h4>
                        <p>Total No. of objects</p>
                       </li>
                      </ul>
                    </div>
                   </div>
                </div>


            </div>

            <div class="contentpanel clearfix">
                <div class="panel main_container">
                    <h4>{{ trans('video::dashboard.overview') }}</h4>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#latest_videos" data-toggle="tab">{{ trans('video::dashboard.latest_videos') }}</a>
                        </li>
                        <li class=""><a href="#progressing_videos" data-toggle="tab">{{ trans('video::dashboard.progressing_videos') }}</a>
                        </li>
                         <li class=""><a href="#top_categories" data-toggle="tab">{{ trans('video::dashboard.top_categories') }}</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                    <div class="tab-pane active" id="latest_videos">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="center">{{ trans('video::dashboard.s_no') }}</th>
                                <th>{{ trans('video::dashboard.title') }}</th>
                                <th>{{ trans('video::dashboard.categories') }}</th>
                                <th>{{ trans('video::dashboard.status') }}</th>
                                <th>{{ trans('video::dashboard.upload_status') }}</th>
                                <th>{{ trans('video::dashboard.added_on') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr data-ng-if="dashCtrl.latestVideos.length == 0">
                				<td colspan="7" class="no-data">{{trans('base::general.not_found')}}</td>
            				</tr>
                            <tr data-ng-if="dashCtrl.latestVideos.length > 0" data-ng-repeat="record in dashCtrl.latestVideos">
                                <td class="center">@{{ $index +1 }}</td>
                                <td>
                                    <div class="product_img">
                                    	<span class="img_title">
                                    		<img data-ng-if="record.thumbnail_image.length > 0"  data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                							<img data-ng-if="record.thumbnail_image.length == 0" src="{{ url('contus/base/images/admin/no_image_available.jpg') }}" data-ng-src="@{{ record.transcodedvideos[0].thumb_url }}" alt="" />
                                    	</span>
                                        <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="img_description">@{{record.title}}</a>
                                    </div>
                                </td>
                                <td>
                                	<div data-ng-repeat="category in record.videocategory track by $index">
                                    	<span>@{{ category.category.title }}</span><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                    </div>
                                </td>
                                <td>
                                	<span class="label label-success" ng-if="record.is_active == 1" >{{trans('video::videos.message.active')}}</span>
                    				<span class="label label-danger" ng-if="record.is_active != 1" >{{trans('video::videos.message.inactive')}}</span>
                                </td>
                                <td>
                                	<span class="label label-primary" ng-if="record.job_status == 'Video Uploaded'">@{{record.job_status}}</span>
                                	<span class="label label-warning" ng-if="record.job_status == 'Progressing'">@{{record.job_status}}</span>
                                	<span class="label label-success" ng-if="record.job_status == 'Complete'">@{{record.job_status}}</span>
                                	<span class="label label-danger" ng-if="record.job_status == 'Error'">@{{record.job_status}}</span>
                                	<span class="label label-danger" ng-if="record.job_status == 'Uploading'">@{{record.job_status}}</span>
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
                                    <th class="center">{{ trans('video::dashboard.s_no') }}</th>
                                    <th>{{ trans('video::dashboard.title') }}</th>
                                    <th>{{ trans('video::dashboard.categories') }}</th>
                                    <th>{{ trans('video::dashboard.status') }}</th>
                                    <th>{{ trans('video::dashboard.upload_status') }}</th>
                                    <th>{{ trans('video::dashboard.added_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr data-ng-if="dashCtrl.progressingVideos.length == 0">
                					<td colspan="7" class="no-data">{{trans('base::general.not_found')}}</td>
            					</tr>
                                <tr data-ng-if="dashCtrl.progressingVideos.length > 0" data-ng-repeat="record in dashCtrl.progressingVideos">
                                    <td class="center">@{{ $index +1 }}</td>
                                    <td>
                                        <div class="product_img">
                                        	<span class="img_title">
                                        		<img data-ng-if="record.thumbnail_image.length > 0" data-ng-src="@{{ record.thumbnail_image }}" alt="" />
                    							<img data-ng-if="record.thumbnail_image.length == 0" src="{{ url('contus/base/images/admin/no_image_available.jpg') }}" data-ng-src="@{{ record.transcodedvideos[0].thumb_url }}" alt="" />
                                        	</span>
                                            <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}" class="img_description">@{{record.title}}</a>
                                        </div>
                                    </td>
                                    <td>
                                    	<div data-ng-repeat="category in record.videocategory track by $index">
                                        	<span>@{{ category.category.title }}</span><span data-ng-if="record.videocategory.length != $index+1">,</span>
                                        </div>
                                    </td>
                                    <td>
                                    	<span class="label label-success" ng-if="record.is_active == 1" >{{trans('video::videos.message.active')}}</span>
                        				<span class="label label-danger" ng-if="record.is_active != 1" >{{trans('video::videos.message.inactive')}}</span>
                                    </td>
                                    <td>
                                    	<span class="label label-primary" ng-if="record.job_status == 'Video Uploaded'">@{{record.job_status}}</span>
                                    	<span class="label label-warning" ng-if="record.job_status == 'Progressing'">@{{record.job_status}}</span>
                                    	<span class="label label-success" ng-if="record.job_status == 'Complete'">@{{record.job_status}}</span>
                                    	<span class="label label-danger" ng-if="record.job_status == 'Error'">@{{record.job_status}}</span>
                                    	<span class="label label-danger" ng-if="record.job_status == 'Uploading'">@{{record.job_status}}</span>
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
                                    <th class="center">{{ trans('video::dashboard.s_no') }}</th>
                                    <th>{{ trans('video::dashboard.category_name') }}</th>
                                    <th>{{ trans('video::dashboard.parent_category') }}</th>
                                    <th>{{ trans('video::dashboard.status') }}</th>
                                    <th>{{ trans('video::dashboard.added_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<tr data-ng-if="dashCtrl.topCategories.length == 0">
                					<td colspan="5" class="no-data">{{trans('base::general.not_found')}}</td>
            					</tr>
                                <tr data-ng-if="dashCtrl.topCategories.length > 0" data-ng-repeat="record in dashCtrl.topCategories">
                                    <td class="center">@{{ $index +1 }}</td>
                                    <td>@{{record.title}}</td>
                                    <td>
                                    	<span data-ng-if="record.parent_category != null">@{{record.parent_category}}</span>
                                    	<span data-ng-if="record.parent_category == null">-</span>
                                    </td>
                                    <td>
                                    	<span class="label label-success" ng-if="record.is_active == 1" >{{trans('video::videos.message.active')}}</span>
                        				<span class="label label-danger" ng-if="record.is_active != 1" >{{trans('video::videos.message.inactive')}}</span>
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
    <script src="{{$getBaseAssetsUrl('js/canvasjs.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/morris-0.4.1.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/jquery-plugin-progressbar.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/dashboard/dashboard.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection