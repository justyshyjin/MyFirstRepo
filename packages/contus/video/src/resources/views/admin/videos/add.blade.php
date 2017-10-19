@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}">
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection
@section('content')
<div ng-app="grid"
	ng-controller="wowzaController as wowzaCtrl">
	
    @include('video::admin.common.subMenu')
	<div class="pageheader clearfix">
		<h2 class="pull-left">
			<span ng-hide="true" id="inititate" data-ng-init="init()"></span>
			<h2 class="titleseperatepage">Add Wowza live stream</h2>
		</h2>
	</div>
	<form name="wowzaForm" method="POST" data-base-validator
		data-ng-submit="wowzaCtrl.save($event)"
		enctype="multipart/form-data">
		{!! csrf_field() !!}
		<div class="contentpanel">
			@include('base::partials.errors')
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="add_form clearfix">
								<div ng-if="true" class="">

									<div class="form-group" 
										data-ng-class="{'has-error': errors.title.has}">
										<label class="control-label">
											{{__('cms::latestnews.title')}} <span class="asterisk">*</span>
										</label> <input type="text" name="title"
											data-ng-model="wowzaCtrl.latestnews.title"
											class="form-control"
											placeholder="{{__('cms::latestnews.title_placeholder')}}"
											value="{{old('title')}}" />
										<p class="help-block" data-ng-show="errors.title.has">@{{
											errors.title.message }}</p>
									</div>
									<div class="form-group"
										data-ng-class="{'has-error': errors.description.has}">
										<label class="control-label">
											{{__('video::videos.description')}} <span class="asterisk">*</span>
										</label>
										<textarea type="text"
											name="content" class="form-control"
											data-ng-model="wowzaCtrl.latestnews.description"
											placeholder="{{__('cms::latestnews.content_placeholder')}}"
											value="{{old('description')}}" rows="5" cols="50"></textarea>
										<p class="help-block" data-ng-show="errors.description.has">@{{
											errors.description.message }}</p>
									</div>
									<div class="form-group">
									<label class="control-label">Stream by</label>
									<div class="col-xs-12 mb-1">
										<label>
											<input ng-model="wowzaCtrl.checkStream" type="radio" name="checkStream" value="Yes">
											<span>HLS Url</span>
										</label>
										<label>
											<input ng-model="wowzaCtrl.checkStream" type="radio" name="checkStream" value="No">
											<span>{{__('video::videos.aspect_ratio')}}</span>
										</label>
									</div>
									<div ng-if="wowzaCtrl.checkStream == 'Yes'">
										<div class="form-group"
											 data-ng-class="{'has-error': errors.hls.has}">
											<input type="text" name="hls"
															data-ng-model="wowzaCtrl.latestnews.hls"
															class="form-control"
															placeholder="Enter HLS url"
															value="{{old('hls')}}" />
											<p class="help-block" data-ng-show="errors.hls.has">@{{
											errors.hls.message }}</p>
										</div>
									</div>

									<br><br>
									<div ng-if="wowzaCtrl.checkStream == 'No'">
										<div class="form-group"
											 data-ng-class="{'has-error': errors.post_creator.has}">
											<select class="form-control mb10" name="aspect_ratio"
													data-ng-model="wowzaCtrl.latestnews.aspect_ratio" data-ng-init="wowzaCtrl.latestnews.aspect_ratio = '640X360' " >
												<option value="640X360">640X360</option>
												<option value="1280X720">1280X720</option>
												<option value="1920X1080">1920X1080</option>
											</select>
											<p class="help-block" data-ng-show="errors.aspect_ratio.has">@{{
											errors.aspect_ratio.message }}</p>
										</div>
									</div>
									</div>
									<div class="form-group" ng-if="false" data-ng-class="{'has-error': errors.scheduled_time.has}">
										<label class="control-label">{{__('video::videos.scheduled_time')}}</label><span class="asterisk">*</span>
										 <input datetime-picker  type="text" name="scheduled_time" id="scheduled_time" data-ng-model="wowzaCtrl.latestnews.scheduled_time" size="30"  placeholder="{{__('video::videos.scheduled_time')}}" data-validation-name = "scheduled_time" value="{{date ( "Y-m-d H:i:s")}}" class="form-control" ng-blur="dateBlur($event,wowzaCtrl.latestnews.scheduled_time)" ng-keyup="dateKeyup($event,wowzaCtrl.latestnews.scheduled_time)"/>
										<p class="help-block" data-ng-show="errors.scheduled_time.has">@{{
											errors.scheduled_time.message }}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="padding10">
							<div class="fixed-btm-action">
								<div class="text-right btn-invoice">
									<a class="btn btn-white mr5" href="javascript:;" onclick="window.history.back();">{{__('base::general.cancel')}}</a>
									<button class="btn btn-primary submitbutton">{{__('base::general.submit')}}</button>
								</div>
							</div>
						</div>
	</form>
</div>
@endsection @section('scripts')
<script src="{{$getCmsAssetsUrl('js/latestnews/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<link rel="stylesheet" href="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.css" />
 <script src="https://rawgit.com/kineticsocial/angularjs-datetime-picker/master/angularjs-datetime-picker.js"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/index.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/latestnews.js')}}"></script>
<style>
.st-container {
	overflow-x: inherit;
}
</style>
@endsection
