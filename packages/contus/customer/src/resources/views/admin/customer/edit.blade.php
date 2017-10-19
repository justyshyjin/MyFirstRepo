@extends('base::layouts.default') 

@section('stylesheet')
   <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
   <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection

@section('header')
    @include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div class="pageheader clearfix">
    <h2 class="pull-left">
        <i class="fa fa-tag"></i> {{trans('user::adminuser.user')}} 
        <span>{{trans('user::adminuser.update_user')}}</span>
    </h2>
</div>
<form name="userForm" method="POST" action="{{url('admin/users/update/'.$user->id)}}">
    {!! csrf_field() !!}
    <div class="contentpanel">
        @include('base::partials.errors')
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="add_form clearfix">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label">{{trans('user::adminuser.username')}} <span class="asterisk">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="{{trans('user::adminuser.username_placeholder')}}" value="{{old('name', $user->name)}}" />
                                    <p class="help-block hide"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{trans('user::adminuser.email')}} <span class="asterisk">*</span></label>
                                    <input type="text" name="email" class="form-control" placeholder="{{trans('user::adminuser.email_placeholder')}}" value="{{old('name', $user->email)}}"/>
                                    <p class="help-block hide"></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{trans('user::adminuser.phone')}} <span class="asterisk">*</span></label>
                                    <input type="text" name="phone" class="form-control" placeholder="{{trans('user::adminuser.phone_placeholder')}}" value="{{old('name', $user->phone)}}"/>
                                    <p class="help-block hide"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{trans('user::adminuser.status')}}</label>
                                    <select class="form-control mb10" name="is_active">
                                        <option @if(old('is_active') == 1) selected="selected" @endif value="1">{{trans('user::adminuser.active')}}</option>
                                        <option @if(old('is_active', $user->is_active) !== NULL && old('is_active', $user->is_active) == 0) selected="selected" @endif value="0">{{trans('user::adminuser.inactive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">{{trans('user::adminuser.sex')}}</label> 
                                    <input type="text" name="sex" class="form-control" placeholder="{{trans('user::adminuser.sex_placeholder')}}" value="{{old('sex', $user->sex)}}"/>
                                </div>
                            </div>
                        </div>
                        <hr>
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
                <a class="btn btn-white mr5" href="{{url('admin/users')}}">{{trans('base::general.cancel')}}</a>
                <button class="btn btn-primary">{{trans('base::general.update')}}</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getUserAssetsUrl('js/adminusers/user.js')}}"></script>
    <script type="text/javascript">
    $('#tree').checktree();
    $('#usergrouppermissions').on('change', function() {
          if ( this.value == '0')
          {
            $("#grouppermissions").show();
          }
          else
          {
            $("#grouppermissions").hide();
          }
        });
        // <![CDATA[
             window.Mara = { 
                userForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>
@endsection
