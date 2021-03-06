 @extends('base::layouts.default') 

@section('header')

@include('base::layouts.headers.dashboard') 
@endsection 

@section('content')
       
<div data-ng-controller="UserGroupController as usrCtrl" >
<div class="menu_container clearfix">
                <div class="page_menu pull-left">
                    <ul class="nav">
                        <li>
                        	<a href="{{url('admin/customer')}}" >{{trans('customer::customer.customer')}}</a>
                        </li>
                        <li>
                            <a href="{{url('admin/users')}}" class="{{$isRouteActive('admin/users')}}" >{{trans('user::adminuser.users')}}</a>
                        </li>
                        <li>
                            <a href="{{url('admin/groups')}}" class="{{$isRouteActive('admin/groups')}}">{{trans('user::adminuser.user_groups')}}</a>
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
    data-route-name="groups"
    data-template-route = "admin/groups"
    data-count = "false"
    ></div>
</div>
</div>

@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getUserAssetsUrl('js/adminusers/usergroup.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection