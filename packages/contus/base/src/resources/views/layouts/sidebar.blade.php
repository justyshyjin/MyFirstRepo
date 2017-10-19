<div class="leftpanel"> 
	<a href="{{url('admin/dashboard')}}"  title="" class="logo_panel"><img
		src="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.logo' )}}"></a>
	<div class="clear"></div>
	<div class="main_nav">
		<ul class="nav"> 
			<li><a href="{{url('admin/dashboard')}}"
				{{ Request::is('admin/dashboard') ? 'class=active' : '' }}><i
					class="dashbaord_icon"></i><span>{{
						__('base::general.dashboard') }}</span></a></li>
			<li><a href="{{url('admin/videos')}}"
				{{ Request::is('admin/videos') ? 'class=active' : '' }}><i class="download_icon"></i><span>{{
						__('base::general.videos') }}</span></a></li>
			<li><a href="{{url('admin/livevideos')}}"
				   {{Request::is('admin/livevideos') ? 'class=active' : ''}}><i class="live_video_icon"></i><span>
						Live Videos
					</span></a></li>
			<li><a href="{{url('admin/users')}}"
				{{Request::is('admin/users') ? 'class=active' : ''}}><i class="contact_icon"></i><span>{{
						__('base::general.users') }}</span></a></li>
			<li><a href="{{url('admin/subscriptions-plans')}}"
				{{Request::is('admin/subscriptions-plans') ? 'class=active' : ''}}><i
					class="subscribe"></i><span>{{ __('base::general.subscriptions') }}</span></a>
			</li>
			<li><a href="{{url('admin/transactions')}}"
				{{Request::is('admin/transactions') ? 'class=active' : ''}}><i
					class="transactions"></i><span>{{ __('base::general.transactions') }}</span></a>
			</li>
			<li><a href="{{url('admin/banner')}}"
				{{Request::is('admin/banner') ? 'class=active' : ''}}><i class="cms-icon"></i><span>{{
						__('base::general.cms') }}</span></a></li>
			<li><a href="{{url('admin/reports')}}"
				{{Request::is('admin/reports') ? 'class=active' : ''}}><i class="reports-icon"></i><span>Analytics</span></a></li>
		</ul>
	</div>
</div>
