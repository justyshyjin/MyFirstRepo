@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection

@section('content')
<div ng-app="emailPage" ng-controller="EmailController">
    <div class="menu_container clearfix">
        <div class="page_menu pull-left">
            <ul class="nav">
                {{-- <li><a href="{{url('admin/latest')}}">{{trans('cms::latestnews.latest_news')}}</a>
                </li> --}}
                <li><a href="{{url('admin/emails')}}" class="active">{{trans('cms::emailtemplate.email')}}</a>
                </li>
                {{-- <li><a href="{{url('admin/smsTemplate')}}">{{
                        trans('cms::emailtemplate.sms') }}</a></li> --}}
                <li><a href="{{url('admin/staticContent')}}">{{
                        trans('cms::staticcontent.static_content') }}</a></li>
                {{-- <li><a href="{{url('admin/testimonial')}}" class="">{{
                        trans('cms::staticcontent.testimonial') }}</a></li> --}}
                 <li><a href="{{url('admin/banner')}}" class="">{{
                        trans('cms::staticcontent.banner') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="pageheader clearfix">
        <span ng-hide="true" id="inititate">{{$id}}</span>
          <span ng-hide="true" id="rules">{!! json_encode($rules) !!}</span>
<div class="pageheader clearfix">
	<h2 class="titleseperatepage">
		{{trans('cms::emailtemplate.edit_new_email')}}
	</h2>
</div>
<form name="emailtemplateForm" method="POST" data-ng-submit="submitform($event)" enctype="multipart/form-data">
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
									data-ng-model="emailData.name"
									class="form-control"
									placeholder="{{trans('cms::emailtemplate.name_placeholder')}}"
									value="{{old('name')}}" />
								<p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
							</div>
							<div class="form-group"
								data-ng-class="{'has-error': errors.subject.has}">
								<label class="control-label">{{trans('cms::emailtemplate.subject')}}</label><span
									class="asterisk">*</span> <input type="text" name="subject"
									maxlength="10" class="form-control"
									data-ng-model="emailData.subject"
									placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}"
									value="{{old('subject')}}" />
								<p class="help-block" data-ng-show="errors.subject.has">@{{ errors.name.message }}</p>
							</div>

							  <div class="form-group" data-ng-class="{'has-error': errors.subject.has}">
                                    <label class="control-label">
                                       {{trans('cms::emailtemplate.content')}}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <textarea ui-tinymce="tinymceOptions"  name="content" class="form-control" data-ng-model="emailData.content" placeholder="{{trans('cms::emailtemplate.content_placeholder')}}" value="{{old('content')}}"></textarea>
                                    @{{tinymce}}
                                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.content.message }}</p>
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
					<a class="btn btn-white mr5" href="{{url('admin/emails')}}">{{trans('base::general.cancel')}}</a>
					<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
				</div>
			</div>
		</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/wysihtml5-0.3.0.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-wysihtml5.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/tinymce.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/tinymce.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/email/email.js')}}"></script>
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
