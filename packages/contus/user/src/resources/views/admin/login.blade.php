@extends('base::layouts.partials')

@section('content')
<style>
h1.logo {  margin: 15px 0 0 23px;  }
.login_header{border-bottom: 2px solid #d74421; padding-bottom: 10px;}
#loginbox {
    margin-top: 9%;
}

#particles {
    width: 100%;
    height: 100%;
    overflow: hidden;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    position: absolute;
    z-index: -2;
}
 .login_header span{   margin-top: 32px;
    display: inline-block;}

</style>
<div class="login_header clearfix"> <h1 class="logo"></h1></div>
<div class="container">

    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

       <div class="row">
                <div class="iconmelon">

                </div>
            </div>

        <div class="panel panel-default" >
            <div class="panel-heading">
                <div class="panel-title text-center">{{trans('user::auth.login.title')}}</div>
            </div>

            <div class="panel-body" >
               @include('base::partials.errors')
                <form name="form" id="form" class="form-horizontal" action="{{url('admin/auth/login')}}"  method="POST">
                   {!! csrf_field() !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" id="email" placeholder="{{trans('user::auth.login.placeholder_email')}}" name="email" value="{{ old('email') }}">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="{{trans('user::auth.login.placeholder_password')}}" name="password">
                    </div>

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button  title="{{trans('user::auth.login.signin')}}" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-log-in"></i> {{trans('user::auth.login.signin')}}</button>
                        <a title="{{trans('user::auth.login.forgot_password')}}" href="{{url('admin/auth/forgot-password')}}" class="pull-left forgot_link">{{trans('user::auth.login.forgot_password')}}</a>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>



@endsection
 @section('scripts')

@endsection
