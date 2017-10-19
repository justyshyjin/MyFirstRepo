@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection

@section('content')
<div ng-app="contactPage" ng-controller="ContactController">
	<div class="menu_container clearfix">
		<div class="page_menu pull-left">
			<ul class="nav">
				<li><a href="{{url('admin/latest')}}">{{trans('cms::latestnews.latest_news')}}</a>
				</li>
				<li><a href="{{url('admin/emails')}}" class="">{{trans('cms::emailtemplate.email')}}</a>
				</li>
				<li><a href="{{url('admin/smsTemplate')}}">{{
						trans('cms::emailtemplate.sms') }}</a></li>
				<li><a href="{{url('admin/staticContent')}}">{{
						trans('cms::staticcontent.static_content') }}</a></li>
				<li><a href="{{url('admin/testimonial')}}" class="">{{
						trans('cms::staticcontent.testimonial') }}</a></li>
				<li><a href="{{url('admin/banner')}}" class="">{{
						trans('cms::staticcontent.banner') }}</a></li>
				<li><a href="{{url('admin/contactus')}}" class="active">{{
						trans('cms::staticcontent.contactus') }}</a></li>
			</ul>
		</div>
	</div>
	<div class="pageheader clearfix">
		<span ng-hide="true" id="inititate">{{$id}}</span> <span
			ng-hide="true" id="rules">{!! json_encode($rules) !!}</span>
		<div class="pageheader clearfix">
			<h2 class="titleseperatepage">
				{{trans('cms::emailtemplate.view_contact_details')}}</h2>
		</div>
		<form name="contactusForm" method="POST"
			data-ng-submit="submitform($event)" enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="contentpanel">
				@include('base::partials.errors')
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="add_form clearfix">
									<div class="form-group"
										data-ng-class="{'has-error': errors.name.has}">
										<label class="control-label">{{trans('cms::emailtemplate.name')}}
											<span class="asterisk">*</span>
										</label> <input type="text" name="name"
											data-ng-model="emailData.name" class="form-control"
											placeholder="{{trans('cms::emailtemplate.name_placeholder')}}"
											value="{{old('name')}}" readonly />
										<p class="help-block" data-ng-show="errors.name.has">@{{
											errors.name.message }}</p>
									</div>
									<div class="form-group"
										data-ng-class="{'has-error': errors.phone.has}">
										<label class="control-label">{{trans('cms::emailtemplate.contactphone')}}
											<span class="asterisk">*</span>
										</label> <input type="text" name="phone"
											data-ng-model="emailData.phone" class="form-control"
											placeholder="{{trans('cms::emailtemplate.name_placeholder')}}"
											value="{{old('phone')}}" readonly />
										<p class="help-block" data-ng-show="errors.phone.has">@{{
											errors.phone.message }}</p>
									</div>
									<div class="form-group"
										data-ng-class="{'has-error': errors.email.has}">
										<label class="control-label">{{trans('cms::emailtemplate.contactemail')}}
											<span class="asterisk">*</span>
										</label> <input type="text" name="email"
											data-ng-model="emailData.email" class="form-control"
											placeholder="{{trans('cms::emailtemplate.name_placeholder')}}"
											value="{{old('email')}}" readonly />
										<p class="help-block" data-ng-show="errors.email.has">@{{
											errors.email.message }}</p>
									</div>
									<div class="form-group"
										data-ng-class="{'has-error': errors.message.has}">
										<label class="control-label">
											{{trans('cms::emailtemplate.subject')}} </label>
										<textarea ui-tinymce="{resize:false,height:400}" type="text"
											name="message" class="form-control"
											data-ng-model="emailData.message"
											placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}"
											value="{{old('message')}}" rows="5" cols="50"></textarea>
										<p class="help-block" data-ng-show="errors.message.has">@{{
											errors.message.message }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="padding10">
					<div class="fixed-btm-action">
						<div class="text-right btn-invoice">
							<a href="{{url('admin/contactus')}}"><span
								class="btn btn-danger pull-right mr10">{{trans('base::general.back')}}</span></a>
						</div>
					</div>
				</div>
		
		</form>
		@endsection @section('scripts')
		<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('tinymce/tiny_mce.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('tinymce/jquery.tinymce.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
		<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
		<script src="{{$getCmsAssetsUrl('js/contactus/contactus.js')}}"></script>
		<script type="text/javascript">
$('#tree').checktree();
    // <![CDATA[
         window.Mara = {
        		emailtemplateForm : {
        		    rules : {!! json_encode($rules) !!}
            },
            route : {

            },
            locale : {!! json_encode(trans('validation')) !!}
         };
    // ]]>

    </script>
		@endsection