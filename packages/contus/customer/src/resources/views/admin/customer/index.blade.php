 @extends('base::layouts.default') 

@section('header')

@include('base::layouts.headers.dashboard') 
@endsection 
@section('stylesheet')
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}" />
@endsection
@section('content')
       
<div data-ng-controller="UserController as usrCtrl" >
<div class="menu_container clearfix">
                <div class="page_menu pull-left">
                    <ul class="nav">
                        <li>
                        	<a href="{{url('admin/customer')}}" class="active" data-toggle="tab" aria-expanded="true" >{{trans('customer::customer.customer')}}</a>
                        </li>
                        <li>
                        	<a href="{{url('admin/users')}}" >{{trans('user::adminuser.users')}}</a>
                        </li>

                        <li>
                            <a href="{{url('admin/groups')}}" >{{trans('user::adminuser.user_groups')}}</a>
                        </li>

                    </ul>
                </div>
            </div>
<div class="contentpanel product order_list">
    @include('base::partials.errors')
    <div class="alert alert-success" data-ng-if="usrCtrl.showResponseMessage">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <span>@{{usrCtrl.responseMessage}}</span>
    </div>
    <div 
    data-grid-view 
    data-rows-per-page="10"
    data-route-name="customer"
    data-template-route = "admin/customer"
    data-count = "false"
    ></div>
</div>
</div>

@endsection
@section('scripts')
	<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getCustomerAssetsUrl('js/customer/index.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection