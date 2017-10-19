@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('content')
<div class="pageheader clearfix">
	<h2 class="pull-left">
		<i class="fa fa-tag"></i> {{trans('cms::staticcontent.user')}} <span>{{trans('cms::staticcontent.add_new_content')}}</span>
	</h2>
</div>
<form name="staticContentForm" method="POST"
	action="{{url('admin/staticContent/add')}}" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<div class="contentpanel">
		@include('base::partials.errors')
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="add_form clearfix">
							<div class="form-group"
								data-ng-class="{'has-error': errors.title.has}">
								<label class="control-label">{{trans('cms::staticcontent.title')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="title"
									data-ng-model="staticCtrl.staticcontent.title"
									class="form-control"
									placeholder="{{trans('cms::staticcontent.title_placeholder')}}"
									value="{{old('title')}}" />
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.content.has}">
								<label class="control-label">{{trans('cms::staticcontent.content')}}
									<span class="asterisk">*</span>
								</label>
								<textarea type="text" name="content" class="form-control"
									data-ng-model="staticCtrl.staticcontent.content"
									placeholder="{{trans('cms::staticcontent.content_placeholder')}}"
									value="{{old('content')}}"></textarea>
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group">
								<label class="control-label">{{trans('cms::staticcontent.status')}}</label>
								<select class="form-control mb10" name="is_active"
									data-ng-model="staticCtrl.staticcontent.is_active">
									<option value="1">{{trans('cms::staticcontent.active')}}</option>
									<option value="0">{{trans('cms::staticcontent.inactive')}}</option>
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
					<a class="btn btn-white mr5" href="{{url('admin/staticContent')}}">{{trans('base::general.cancel')}}</a>
					<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
				</div>
			</div>
		</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/static/static.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = { 
            		 staticContentForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>

@endsection
