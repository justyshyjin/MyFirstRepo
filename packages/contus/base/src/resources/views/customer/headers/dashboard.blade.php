<div class="headerbar clearfix">
  <div class="header_logo pull-left">
    <h3><a href="{{url('admin/dashboard')}}">{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}</a></h3>
  </div>
  <ul class="header-right pull-right">
    <li><a href="#"><i class="help_icon"></i></a></li>
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
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/profile')}}" title="">{{trans('base::adminsidebar.my_profile')}}</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/users/changepassword')}}">{{trans('base::adminsidebar.change_password')}}</a></li>
        <li role="presentation" class="divider"></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('admin/auth/logout')}}">{{trans('base::adminsidebar.log_out')}}</a></li>    
      </ul>
  </ul>
</div>