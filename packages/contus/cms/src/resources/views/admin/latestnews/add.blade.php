@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div ng-app="grid" ng-controller="latestNewsController as latestNewsCtrl">
<div class="pageheader clearfix">
    <div class="menu_container clearfix">
        <div class="page_menu pull-left">
            <ul class="nav">
                <li><a href="{{url('admin/latest')}}" class="active">{{trans('cms::latestnews.latest_news')}}</a>
                </li>
                <li><a href="{{url('admin/emails')}}">{{
                        trans('cms::latestnews.email') }}</a></li>
                <li><a href="{{url('admin/smsTemplate')}}">{{
                        trans('cms::latestnews.sms') }}</a></li>
                <li><a href="{{url('admin/staticContent')}}">{{
                        trans('cms::staticcontent.static_content') }}</a></li>
               <li><a href="{{url('admin/testimonial')}}" class="">{{
                        trans('cms::staticcontent.testimonial') }}</a></li>
                 <li><a href="{{url('admin/banner')}}" class="">{{
                        trans('cms::staticcontent.banner') }}</a></li>
                  <li><a href="{{url('admin/contactus')}}" class="">{{
            trans('cms::staticcontent.contactus') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="pageheader clearfix">
	<h2 class="pull-left">
		<i  class="titleseperatepage"></i>  <span>{{trans('cms::latestnews.add_new_news')}}</span>
	</h2>
	</div>
</div>
</div>
 <form name="latestNewsFormd" method="POST" data-base-validator data-ng-submit="latestNewsCtrl.save($event,latestNewsCtrl.latestnews.id)" enctype="multipart/form-data">
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
								<label class="control-label">{{trans('cms::latestnews.title')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="title"
									data-ng-model="latestNewsCtrl.latestnews.title"
									class="form-control"
									placeholder="{{trans('cms::latestnews.title_placeholder')}}"
									value="{{old('title')}}" />
								   <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
							</div>
							     <div class="form-group" data-ng-class="{'has-error': errors.content.has}">
                                        <label class="control-label">
                                            {{trans('cms::latestnews.content')}}
                                            <span class="asterisk">*</span>
                                        </label>
                                        <textarea  ui-tinymce="{resize:false,height:400}"  type="text" name="content" class="form-control" data-ng-model="latestNewsCtrl.latestnews.content" placeholder="{{trans('cms::latestnews.content_placeholder')}}" value="{{old('content')}}" rows="5" cols="50"></textarea>
                                        <p class="help-block" data-ng-show="errors.content.has">@{{ errors.content.message }}</p>
                                    </div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.post_creator.has}">
								<label class="control-label">{{trans('cms::latestnews.post_creator')}}</label><span
									class="asterisk">*</span> <input type="text"
									name="post_creator" maxlength="100" class="form-control"
									data-ng-model="latestNewsCtrl.latestnews.post_creator"
									placeholder="{{trans('cms::latestnews.post_creator_placeholder')}}"
									value="{{old('post_creator')}}" />
								<p class="help-block hide"></p>
							</div>
							<div class="form-group">
								<label class="control-label">{{trans('cms::latestnews.status')}}</label>
								<select class="form-control mb10" name="is_active"
									data-ng-model="latestNewsCtrl.latestnews.is_active">
									<option value="1" selected="selected" >{{trans('cms::latestnews.active')}}</option>
									<option value="0">{{trans('cms::latestnews.inactive')}}</option>
								</select>
							</div>
							        
					</div>
				</div>
				<div class="clear"></div>
				<div class="padding10">
					<div class="fixed-btm-action">
						<div class="text-right btn-invoice">
							<a class="btn btn-white mr5" href="{{url('admin/latest')}}">{{trans('base::general.cancel')}}</a>
							<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
						</div>
					</div>
				</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/latestnews/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('angular/angular-ui.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/tiny_mce.js')}}"></script>
<script src="{{$getBaseAssetsUrl('tinymce/jquery.tinymce.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/latestnews/index.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/latestnews/latestnews.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = { 
            		latestNewsForm : {
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>

@endsection
