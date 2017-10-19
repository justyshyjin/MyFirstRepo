@extends('base::layouts.partials') 

@section('content')

<div class="container">    
        
    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3"> 
        
        <div class="row">                
            <div class="iconmelon">
               <h1 class="logo"></h1>
            </div>
        </div>
        
        <div class="panel panel-default" >
            <div class="panel-heading">
                <div class="panel-title text-center">{{trans('user::auth.forgotpassword.title')}}</div>
            </div>     

            <div class="panel-body" >
                @include('base::partials.errors')
                <form name="form" id="form" class="form-horizontal" action="{{url('admin/auth/forgot-password')}}"  method="POST">
                   {!! csrf_field() !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" id="email" autocomplete="Off" placeholder="{{trans('user::auth.forgotpassword.placeholder_email')}}" name="email" value="{{old('email')}}">                                       
                    </div>
                                                                 

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button title="{{trans('user::auth.forgotpassword.button')}}" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-log-in"></i> {{trans('user::auth.forgotpassword.button')}}</button>                          
                        <a title="{{trans('user::auth.forgotpassword.login')}}" href="{{ url('admin/auth/login') }}" class="pull-left forgot_link">{{trans('user::auth.forgotpassword.login')}}</a>
                        </div>
                    </div>
                    

                </form>     

            </div>                     
        </div>  
    </div>
</div>


@endsection

