@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard') 
@endsection

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('content')
<style type="text/css">
    .custom-color {
        color: #a94442;
    }
</style>
<div class="contentpanel product order_list">

<div class="panel main_container clearfix" style="border: 1px solid transparent;">
   <div class=" add_form">
    <h4 style="padding:0 0 20px 0;">
     {{trans('user::adminuser.my_profile')}}
    </h4>
 <div class="" data-base-validator data-ng-controller="ProfileController as prfCtrl">
<form name="profileForm" method="POST"  data-ng-init="prfCtrl.fetchData()" data-ng-submit="prfCtrl.save($event)" enctype="multipart/form-data">
   {!! csrf_field() !!}
        @include('base::partials.errors')
        
       <div id="table_loader" class="table_loader_container" data-ng-if="prfCtrl.gridLoadingBar">
          <div class="table_loader">
            <div class="loader"></div>
          </div>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
          <label class="control-label">{{trans('user::adminuser.username')}} <span class="asterisk">*</span></label>
          <input type="text" name="name" data-ng-model="prfCtrl.user.name" class="form-control" placeholder="{{trans('user::adminuser.username_placeholder')}}" value="{{old('name')}}" />
          <p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
          <label class="control-label">{{trans('user::adminuser.email')}} <span class="asterisk">*</span></label>
          <input type="text" name="email" data-unique="{{url('api/admin/users/unique')}}@{{'/'+prfCtrl.user.id}}" data-ng-model="prfCtrl.user.email" class="form-control" placeholder="{{trans('user::adminuser.email_placeholder')}}" value="{{old('email')}}"/>
          <p class="help-block" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
          <label class="control-label">{{trans('user::adminuser.phone')}} <span class="asterisk">*</span></label>
          <input type="text" name="phone" maxlength="10" maxlength="15" class="form-control" data-ng-model="prfCtrl.user.phone" placeholder="{{trans('user::adminuser.phone_placeholder')}}" value="{{old('phone')}}"/>
          <p class="help-block" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
        </div>

        <div class="form-group">
          <label class="control-label">{{trans('user::adminuser.gender')}}</label> 
          <select class="form-control mb10" name="gender" data-ng-model="prfCtrl.user.gender">
            <option value="" disabled>{{trans('user::adminuser.select_gender')}}</option>
            <option value="male">{{trans('user::adminuser.male')}}</option>
            <option value="female">{{trans('user::adminuser.female')}}</option>
          </select>
        </div>
        
          <div class="profile_image_upload">
            <div class="form-group">                                            
              <label class="control-label">{{ trans('user::adminuser.profile_image') }}</label>
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-append">
                  <div class="uneditable-input">
                    <i class="glyphicon glyphicon-file fileupload-exists"></i> <span
                    class="fileupload-preview"></span>
                  </div>
                  <span class="btn btn-default btn-file"> 
                    <span class="fileupload-new">{{trans('video::videos.select_image')}}</span> 
                    <span class="fileupload-exists">{{trans('video::videos.change')}}</span> 
                    <input type="file" 
                    id ="profile-image"   
                    name="image"
                    data-action="{{url('api/admin/users/profile-image')}}" />
                  </span> 
                  <a href="#" class="btn btn-default fileupload-exists profile-image-remove"
                  data-dismiss="fileupload" data-ng-click="prfCtrl.removeProfileImageProperty()">{{trans('video::videos.remove')}}</a>
                  <p class="help-block hide"></p>
                </div>
              </div>
              <div class="form-group">
                <div class="clsFileUpload">
                  <span id="profile-image-delete" data-ng-click="prfCtrl.deleteProfileImage()" data-ng-show="prfCtrl.user.profile_image" data-boot-tooltip="true" title="{{trans('user::user.delete_profile_image')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
                  <img id="profile-preview" data-ng-show="prfCtrl.user.profile_image" data-ng-src="@{{prfCtrl.user.profile_image}}" width="180px" height="180px">
                  <div id="image-progress" class="hide clsProgressbar"></div>
                  <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                </div>
              </div>
            </div>
          </div>    
      </div>
        
    <div class="fixed-btm-action">
      <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
      &nbsp;
      <a class="btn btn-danger pull-right mr10" href="{{url('admin/dashboard')}}">{{trans('base::general.cancel')}}</a>
    </div>
    </form>
</div>
</div>
</div>
</div>
@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getUserAssetsUrl('js/adminusers/profile.js')}}"></script>
@endsection