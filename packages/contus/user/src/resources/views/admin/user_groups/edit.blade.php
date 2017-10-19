 @extends('base::layouts.default') 

@section('header')

@include('base::layouts.headers.dashboard') 
@endsection 

@section('content')
<div class="menu_container clearfix">
    <div class="page_menu pull-left">
        <ul class="nav">
            <li>
                <a href="{{url('admin/users')}}" class="{{$isRouteActive('admin/users')}}"  >{{trans('user::adminuser.users')}}</a>
            </li>
            <li>
                <a href="{{url('admin/groups')}}" class="{{$isRouteActive('admin/groups')}}"  >{{trans('user::adminuser.user_groups')}}</a>
            </li>
        </ul>
    </div>
</div>

<div class="contentpanel">

    <div class="row">
    
        <div class="col-sm-12">
          <form name="groupForm" method="POST" action="{{url('admin/groups/update/'.$group->id)}}">
  {!! csrf_field() !!}
        @include('base::partials.errors')            
        <div class="panel panel-default">
                <div class="panel-body">
                    <div class="add_form clearfix">
                    <h4 style="padding:0 0 20px 0;">
                     Edit User Group
                    </h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label">Group Name<span class="asterisk">*</span></label>
                                <input type="text" data-unique="{{url('admin/groups/unique/'.$group->id)}}" name="name" class="form-control" placeholder="Group Name" value="{{old('name', $group->name)}}" />
                                <p class="help-block hide"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group role-row">
                                <label class="control-label">Permissions<span class="asterisk">*</span></label>
                                 <ul id="tree">
                                     @foreach(Config::get('access.modules') as $key => $modules)
                                         <li>
                                             <label class="control-label">
                                                <input type="checkbox" /> {{$key}}
                                            </label>
                                             <ul>
                                             @foreach($modules as $eachModule => $moduleDetails)
                                             <li>
                                                <label class="control-label">
                                                    <input type="checkbox" /> {{$moduleDetails['name']}} 
                                                </label>
                                                <ul>
                                                    @foreach($moduleDetails['permission'] as $label => $permission)
                                                       <li>
                                                             <label><input @if(array_key_exists($permission,json_decode($group->permissions, true))) checked @endif type="checkbox" data-multicheck-validate="permissions" name="permissions[]" value="{{$permission}}"> {{$label}}</label>
                                                       </li>
                                                    @endForeach
                                                </ul>
                                             </li>
                                             @endForeach
                                             </ul>
                                          </li>
                                     @endForeach
                                </ul>
                                 <p class="help-block hide"></p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="fixed-btm-action">
                  <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                  &nbsp;
                  <a class="btn btn-danger pull-right mr10" href="{{url('admin/groups')}}">{{trans('base::general.cancel')}}</a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/bootstrap-wysihtml5.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
    <script src="{{$getUserAssetsUrl('js/admingroup/edit.js')}}"></script>
    <script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.VPlay = { 
                admingroup : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>
@endsection