<header>
    <section class="container">
        <a href="javascript:void(0)" class="menu-icon sprite"></a>
        <a ui-sref="dashboard()" class="logo sprite"></a>

        @include('hopmedia::common.menubar')
        @section('menubar')
        @show
        <nav class="sign-up">
            <ul> 
                @if(auth()->user())
                       <li class="profile-status" style="">
                            <a href="">
                                <span class="welcom-name">Welcome <i>{{auth()->user()->name}}</i></span>
                                <span class="image">
                                    <img src="{{$getHopmediaAssetsUrl('images/profile-image.png')}}">
                                </span>
                            </a>
                        </li>
                        <li> 
                            <a href="{{Url('hopmedia/auth/logout')}}" class="">Sign Out</a>
                        </li>
                    @else
                        <li> 
                            <a href="javascript:void(0)" class="signin">Sign In</a>
                        </li>
                         <li>
                            <a href="javascript:void(0)" class="signup">Sign up</a>
                        </li>
                    @endif
            </ul>
        </nav>
    </section>
</header>
