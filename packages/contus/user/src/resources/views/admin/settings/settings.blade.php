@extends('base::layouts.default') 

@section('header')
@include('base::layouts.headers.dashboard') 
@endsection

@section('content')
<form name="userForm" method="POST" action="{{url('admin/settings/update')}}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="contentpanel">
	<div class="panel main_container clearfix" style="border: 1px solid transparent;">
	<div class=" add_form">
        @include('base::partials.errors')
		<div class="">
			<ul class="nav nav-tabs">
                @foreach($settingCategories as $key => $settingCategory)
                    <li  class="@if($key == 0) active @endif">
                        <a title="{{$settingCategory->name}}" href="#{{$settingCategory->slug}}" data-toggle="tab"> 
                         {{$settingCategory->name}}
                        </a>
                    </li>
                @endforeach
			</ul>
			<div class="mb30"></div>
		</div>

		<div class="tab-content">
		
		  @foreach($settingDetails as $key => $settingDetail)
		  
		      <div class="tab-pane text-style @if($key == 0) active @endif" id="{{$settingDetail->slug}}">
		      
		        @foreach($settingDetail['category'] as $key => $category)
		        
				    @foreach($category['settings'] as $key => $setting)
        				
        					<div class="@if($setting->is_hidden) hide @endif">
        						<div class="form-group">
        							<label class="control-label">{{ $setting->display_name }}<span class="asterisk">*</span></label>
        							@if($setting->type == 'dropdown')
        							     <select name="{{$category->slug.'__'.$setting->setting_name}}" class="form-control" id="{{$category->slug.'__'.$setting->setting_name}}">
        							         @foreach($setting->getOption() as $option)    
        							            <option @if(old($setting->setting_name, $setting->setting_value) == $option) selected="selected" @endif value="{{ $option }}">{{ $option }}</option>
        							         @endforeach
        							     </select>
                                    @elseif($setting->type == 'image')
                                        <input type="file" name="{{$category->slug.'__'.$setting->setting_name}}" class="form-control" id="{{$category->slug.'__'.$setting->setting_name}}">
                                        <img alt="" src="{{asset('assets/images/'.$setting->setting_value)}}">
        							@else
        							     <input name="{{$category->slug.'__'.$setting->setting_name}}" class="form-control" id="{{$category->slug.'__'.$setting->setting_name}}" value="{{old($category->slug.'__'.$setting->setting_name, $setting->setting_value)}}">
        							@endif
        							@if($setting->description)
        							 <p class="help-block">{{$setting->description}}</p>
        							@endif
        						</div>
        					</div>
        				
        		    @endforeach

				@endforeach
				
			</div>
		      
		  @endforeach

		</div>
	
	<div class="clear"></div>
	<div class="padding10">
		<div class="fixed-btm-action">
			<div class="text-right btn-invoice">
			    <a class="btn btn-danger mr10" title="{{ trans('base::general.cancel') }}" href="{{url('admin/dashboard')}}">{{ trans('base::general.cancel') }}</a>
			    <button class="btn btn-primary" title="{{ trans('base::general.submit') }}">{{ trans('base::general.submit') }}</button>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
</form>	
@endsection

@section('scripts')
<script src="{{$getUserAssetsUrl('js/settings/settings.js')}}"></script>
@endsection