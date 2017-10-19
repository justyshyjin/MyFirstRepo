@extends('base::layouts.default')


@section('header')
    @include('base::layouts.headers.dashboard')
@endsection
@section('content')
<div data-ng-controller="ReportsController as reportCtrl" >

<div class="menu_container clearfix">
                <div class="page_menu pull-left">

                    <ul class="nav">

                        <li><a href="{{url('admin/reports')}}" title="" class="active">{{trans('base::adminsidebar.reports')}}</a>
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
			<h3 class="report-users-title">User Reports</h3>
                    <div class="col-lg-3 col-sm-3 report-totalcustomer">
                        <h5><i class="fa fa-users"></i>Total Customers</h5>
                        <div class="count-list-price">                            
                            <div class="count-list-price-details">
                                <p>Customers</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfCustomer }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 report-activecustomer">
                        <h5> <i class="fa fa-user "></i>Active Customers</h5>
                        <div class="count-list-price">
                            <i class="aws-up-icon"></i>
                            <div class="count-list-price-details">
                                <p>Active</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfActiveCustomer }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 report-inactivecustomer">
                        <h5><i class="fa fa-user-times"></i>Inactive Customers</h5>
                        <div class="count-list-price">
							 <i class="aws-down-icon"></i>
                            <div class="count-list-price-details">
                                <p>Inactive</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfInactiveCustomer }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-3 col-sm-3 report-subcribedcustomer">
                        <h5><i class="fa fa-user"></i>Subscribed Customers</h5>
                        <div class="count-list-price">
                            <div class="count-list-price-details">
                                <p>Subscribed Customers</p>
                                <div class="unit-price">@{{ reportCtrl.subcribedCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix count-list count-list-last">
                <h3 class="report-videos-title">Video Reports</h3>
                    <div class="col-lg-3 col-sm-3 report-totalvideos">
                        <h5><i class="fa fa-file-video-o"></i>Total Videos</h5>
                        <div class="count-list-price">                            
                            <div class="count-list-price-details">
                                <p>Videos</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfVideos }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 report-activevideos">
                        <h5> <i class="fa fa-file-video-o"></i>Active Videos</h5>
                        <div class="count-list-price">
                            <i class="aws-up-icon"></i>
                            <div class="count-list-price-details">
                                <p>Active</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfActiveVideos }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 report-inactivevideos">
                        <h5><i class="fa fa-file-video-o"></i>Inactive Videos</h5>
                        <div class="count-list-price">
							 <i class="aws-down-icon"></i>
                            <div class="count-list-price-details">
                                <p>Inactive</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfInActiveVideos }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-3 col-sm-3 report-livevideos">
                        <h5><i class="fa fa-video-camera"></i>Live Videos</h5>
                        <div class="count-list-price">
                            <div class="count-list-price-details">
                                <p>Live Videos</p>
                                <div class="unit-price">@{{ reportCtrl.totalNumberOfLiveVideos }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="clearfix user-analytics">                    
                    <div class="col-md-6 dashbord-graph mt20">
                    <h3 class="clearfix cs-graph-heading">User Analytics
                    <select class="form-control title-dropdown mb10 pull-right ng-pristine ng-valid ng-touched" name="usercharttype" data-ng-model="reportCtrl.usercharttype" ng-change="selectCustType(reportCtrl.usercharttype)">
                        <option value="day">Day</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                      </select>
                    </h3>
                    	<div id="users-chart" style="height: 350px; width: 100%;"></div>
                    </div>                     
                     <div class="col-md-6 dashbord-graph mt20">
                     <h3 class="clearfix cs-graph-heading">Video Analytics <select class="form-control title-dropdown mb10 title-dropdown pull-right ng-pristine ng-valid ng-touched" name="videocharttype" data-ng-model="reportCtrl.videocharttype" ng-change="selectVideoType(reportCtrl.videocharttype)">
            <option value="day">Day</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
          </select></h3> <div id="video-chart" style="height: 350px; width: 100%;"></div>
                    </div>
                </div>
                
				<div class="clearfix aws-billing-cost" ng-hide="true">
                   <div class="aws-billing-cost-left-column">
                     <div class="aws-billing-cost-heading"><p>Total Comments</p>                     
						<h5>@{{ reportCtrl.commentcount }} <span>Comments</span></h5>
                     </div>
                   </div>
                   <div class="aws-billing-cost-right-column">
                    <div class="aws-billing-cost-graph">
                    	<div ng-hide = true id="aws-chart" style="height: 170px; width: 170px;"></div>
                    </div>
                    <div class="aws-billing-cost-graph-details">
                      <h5>Video Documents</h5>
                      <ul>
                       <li>
                        <h4>@{{ reportCtrl.pdfcount}}</h4>
                        <p>PDF</p>
                       </li>
                        <li>
                        <h4>@{{ reportCtrl.wordcount }}</h4>
                        <p>Word Documents</p>
                       </li>
                       <li>
                        <h4>@{{ reportCtrl.mp3count }}</h4>
                        <p>MP3</p>
                       </li>
                      </ul>
                    </div>
                   </div>
                </div>
                <div class="col-md-12 dashbord-graph mt20">
                <h3 class="clearfix cs-graph-heading">Subscribed User Analytics
                 <select class="form-control title-dropdown mb10 title-dropdown pull-right ng-pristine ng-valid ng-touched" name="subscribeduser" data-ng-model="reportCtrl.subscribeduser" ng-change="selectSubcribedUserType(reportCtrl.subscribeduser)">
            		<option value="day">Day</option>
            		<option value="month">Month</option>
            		<option value="year">Year</option>
          			</select></h3> 
          			<div id="subscribed-user" style="height: 350px; width: 100%;"></div>
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
    <script src="{{$getVideoAssetsUrl('js/reports/reports.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection