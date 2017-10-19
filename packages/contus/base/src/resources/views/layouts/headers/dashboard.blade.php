<div class="headerbar clearfix">
  <div class="header_logo pull-left">
    <h3><a href="{{url('admin/dashboard')}}">{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}</a></h3>
  </div>
  <ul class="header-right pull-right">
    <li><a href="{{url('admin/settings')}}"><i class="setting_icon"></i></a></li>
    <li class="dropdown">
      @if(isset(Auth::user()->profile_image) && Auth::user()->profile_image !="")
        <div class="user dropdown-toggle" id="menu1" data-toggle="dropdown"><img src="{{Auth::user()->profile_image}}" alt="" style="width:40px;height:40px;border-radius:100%;" />
        </div>
      @else
        <div class="user dropdown-toggle" id="menu1"  data-toggle="dropdown"><img src="{{$getBaseAssetsUrl('images/admin/user_images.png')}}" alt="" style="width:40px;height:40px;border-radius:100%;" />
        </div>
      @endif
      <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="menu1" >
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/profile')}}">{{__('base::adminsidebar.my_profile')}}</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/changepassword')}}">{{__('base::adminsidebar.change_password')}}</a></li>
        <li role="presentation" class="divider"></li>
        <li role="presentation" class="align-center"><form method="post" action="{{url('admin/auth/logout')}}" >
                {{ csrf_field() }}
                <button></i>{{trans('base::adminsidebar.log_out')}}</button>
            </form></li>
      </ul>
  </ul>
</div>