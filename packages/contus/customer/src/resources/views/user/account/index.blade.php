<section class="dashboard-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3 pleft0">
           <div class="myaccfor-mobile" data-mobile-toggle list="lists">{{trans('customer::customer.myaccount')}} <span class="glyphicon glyphicon-th-list pull-right" aria-hidden="true"></span></div>
                <ul class="list-group dashboard-links">
                    <li class="list-group-item active-links"><a
                        ui-sref="profile" ui-sref-active="active" title="My Profile"> {{trans('customer::customer.myprofile')}} </a></li>
                    <li class="list-group-item"><a ui-sref="following" ui-sref-active="active" title="My Playlist">My Playlists <span
                            class="badge"></span></a></li>
                    <li class="list-group-item"><a ui-sref="favourites"
                        ui-sref-active="active" title="My favourites"> {{trans('customer::customer.myfavourites')}}<span
                            class="badge"></span></a></li>
                    <li class="list-group-item"><a
                        ui-sref="subscriptions" ui-sref-active="active" title="My Plan">
                           {{trans('customer::customer.myplans')}} <span class="badge"></span>
                    </a></li>
                    <li class="list-group-item"><a
                        ui-sref="transactions" ui-sref-active="active" title="my Transcations">{{trans('customer::customer.mytransaction')}}<span class="badge"></span>
                    </a></li>
                    <li class="list-group-item"><a
                        ui-sref="notifications" ui-sref-active="active" title="Notifications">
                            {{trans('customer::customer.mynotifications')}}<span class="badge"></span>
                    </a></li>
                    <li class="list-group-item"><a ui-sref="password"
                        ui-sref-active="active" title="Change Password"> {{trans('customer::customer.mychangepassword')}}</a></li>
                    <li class="list-group-item" ><a
                        href="{{url('/auth/logout')}}" title="logout"  onclick="return confirm('Are you sure do you want to Logout?')">
                            Logout </a></li>
                </ul>
            </div>
            @yield('profilecontent')
        </div>
    </div>
</section>

