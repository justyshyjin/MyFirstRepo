@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection

@section('content')
<div class="pageheader clearfix">
	<h2 class="pull-left">
		<i class="fa fa-tag"></i> {{trans('cms::smstemplate.user')}} <span>{{trans('cms::smstemplate.add_new_sms')}}</span>
	</h2>
</div>
<form name="smsTemplateForm" method="POST"
	action="{{url('admin/smsTemplate/update'.$sms->id)}}"
	enctype="multipart/form-data">
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
								<label class="control-label">{{trans('cms::smstemplate.name')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="name"
									data-ng-model="smsCtrl.sms.name"
									class="form-control"
									placeholder="{{trans('cms::smstemplate.name_placeholder')}}"
									value="{{old('name')}}" />
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.subject.has}">
								<label class="control-label">{{trans('cms::smstemplate.subject')}}</label><span
									class="asterisk">*</span> <input type="text" name="subject"
									maxlength="10" class="form-control"
									data-ng-model="smsCtrl.sms.subject"
									placeholder="{{trans('cms::smstemplate.subject_placeholder')}}"
									value="{{old('subject')}}" />
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.content.has}">
								<label class="control-label">{{trans('cms::smstemplate.content')}}
									<span class="asterisk">*</span>
								</label>
								<textarea type="text" name="content" class="form-control"
									data-ng-model="smsCtrl.sms.content"
									placeholder="{{trans('cms::smstemplate.content_placeholder')}}"
									value="{{old('content')}}"></textarea>
								<p class="help-block"hide"></p>
							</div>


							<div class="form-group">
								<label class="control-label">{{trans('cms::smstemplate.status')}}</label>
								<select class="form-control mb10" name="is_active"
									data-ng-model="smsCtrl.sms.is_active">
									<option value="1">{{trans('cms::smstemplate.active')}}</option>
									<option value="0">{{trans('cms::smstemplate.inactive')}}</option>
								</select>
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
					<a class="btn btn-white mr5" href="{{url('admin/smsTemplate')}}">{{trans('base::general.cancel')}}</a>
					<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
				</div>
			</div>
		</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/sms/sms.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = { 
            		 smsTemplateForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>

@endsection
