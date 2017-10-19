<nav class="mobile-active-menu">
    <div class="overlay"></div>
    <ul>
        @if(auth()->user())
        <li class="profile-status mobile-menu" >
            <a href="">
                <span class="image">
                    <img src="{{$getHopmediaAssetsUrl('images/profile-image.png')}}">
                </span>
                <span class="welcom-name">Welcome <i>Joseph Nesan</i></span>
            </a>
        </li>
        @else
        <li class="mobile-menu">
            <a href="javascript:void(0)" class="signin">Sign in / Sign up</a>
        </li>
        @endif
        <li>
            <a ui-sref="features()">Features</a>
        </li>
        <li>
            <a ui-sref="pricing()">Pricing</a>
        </li>
        <li>
            <a ui-sref="aboutUs()">about</a>
        </li>
        <li>
            <a ui-sref="contact()"">contact</a>
        </li>
    </ul>
</nav>