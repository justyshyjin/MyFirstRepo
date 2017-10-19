@extends('base::layouts.default') @section('header')

@include('base::layouts.headers.dashboard') @endsection

@section('content')

<div data-ng-controller="contactusController as contactusCtrl">
	<div class="menu_container clearfix">
		<div class="page_menu pull-left">
			<ul class="nav">
				<!--<li><a href="{{url('admin/latest')}}">{{trans('cms::latestnews.latest_news')}}</a>
				</li>-->
				<li><a href="{{url('admin/emails')}}">{{trans('cms::emailtemplate.email')}}</a>
				</li>
				<!--<li><a href="{{url('admin/smsTemplate')}}">{{
						trans('cms::emailtemplate.sms') }}</a></li>-->
				<li><a href="{{url('admin/staticContent')}}">{{
						trans('cms::staticcontent.static_content') }}</a></li>
			    <!--<li><a href="{{url('admin/testimonial')}}" >{{
						trans('cms::staticcontent.testimonial') }}</a></li>-->
                 <li><a href="{{url('admin/banner')}}"  >{{
						trans('cms::staticcontent.banner') }}</a></li>
				<li><a href="{{url('admin/contactus')}}" class="active">{{
	              trans('cms::staticcontent.contactus') }}</a></li>
			</ul>
		</div>
	</div>
	<div class="contentpanel product order_list">
		@include('base::partials.errors')
		<div class="alert alert-success"
			data-ng-if="testCtrl.showResponseMessage">
			<button type="button" class="close" data-dismiss="alert">�</button>
			<span>@{{testCtrl.responseMessage}}</span>
		</div>
		<div data-grid-view data-rows-per-page="10"
			data-route-name="contactus"
			data-template-route="admin/contactus" data-count="false"></div>
	</div>
</div>

@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script	src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/contactus/index.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>

@endsection