@extends('base::layouts.default')

@section('header')
    @include('base::layouts.headers.dashboard') 
@endsection

@section('content')
<style type="text/css">
    .custom-color {
        color: #a94442;
    }
</style>
<div class="contentpanel product order_list">

<div class="panel main_container">

<div class=" add_form">
    <h4 style="padding:20px 0;">
     {{trans('user::adminuser.changepassword.changepassword')}}
    </h4>
<div class="" data-base-validator data-ng-controller="ChangePasswordController as chngPassCtrl">
<form name="changePasswordForm" method="POST" data-ng-submit="chngPassCtrl.save($event)" enctype="multipart/form-data">
    {!! csrf_field() !!}
        @include('base::partials.errors')
        <div class="row">
            <div class="col-sm-12">
                      <div class=" clearfix">
                        <div class="row">
                            <div class="col-sm-12">
                               
                                <div class="form-group" data-ng-class="{'has-error': errors.old_password.has}">
                                    <label class="control-label">{{trans('user::adminuser.changepassword.oldpassword')}} <span class="asterisk">*</span></label>
                                    <input type="password" name="old_password"  data-ng-model="chngPassCtrl.setpassword.old_password" class="form-control" data-validation-name="Old Password" placeholder="{{trans('user::adminuser.changepassword.placeholder_oldpassword')}}"/>
                                    <p class="help-block" data-ng-show="errors.old_password.has">@{{ errors.old_password.message }}</p>
                                    <p class="help-block custom-color" data-ng-if="passwordError.has.Oldpassword">{{trans('user::adminuser.changepassword.wrong_old')}}</p>
                                </div>
    
                                <div class="form-group" data-ng-class="{'has-error': errors.password.has}">
                                    <label class="control-label">{{trans('user::adminuser.changepassword.newpassword')}} <span class="asterisk">*</span></label>
                                    <input type="password" name="password" class="form-control"  data-ng-model="chngPassCtrl.setpassword.password" placeholder="{{trans('user::adminuser.changepassword.placeholder_newpassword')}}"/>
                                     <p class="help-block" data-ng-show="errors.password.has">@{{ errors.password.message }}</p>
                                </div>
                                
                                <div class="form-group" data-ng-class="{'has-error': errors.password_confirmation.has}">
                                    <label class="control-label">{{trans('user::adminuser.changepassword.confirmpassword')}} <span class="asterisk">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control"  data-ng-model="chngPassCtrl.setpassword.password_confirmation" data-validation-name="Confirm Password" placeholder="{{trans('user::adminuser.changepassword.placeholder_confirmpassword')}}"/>
                                    <p class="help-block" data-ng-show="errors.password_confirmation.has">@{{ errors.password_confirmation.message }}</p>
                                    <p class="help-block custom-color"  data-ng-if="passwordError.has.reenterpasswordsame">{{trans('user::adminuser.changepassword.not_match')}}</p> 
                                </div>
                                
                            </div>
                        </div>
                        </div>
                    </div>
              
        </div>
    
    <div class="clear"></div>
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
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getUserAssetsUrl('js/adminusers/changepassword.js')}}"></script>
@endsection