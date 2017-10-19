    @section('profilecontent')<div class="col-md-9">
    <div class="row">
        <div class="subscription-contanier">
            <div class="row">
                <div class="col-md-9">
                    <h5>Upgrade to @{{subscription.name}}</h5>
                    <p>
                        <span class="text-blue">@{{subscription.amount}}</span>
                        @{{subscription.description}}
                    </p>
                </div>
                <div class="col-md-3">
                    <a title="Subscribe now" class="btn full-btn btn-subscription"
                        ui-sref="subscribeinfo">Subscribe now</a>
                </div>
            </div>
        </div>
        <div class="myfavourite">
            <h3>My Playlists</h3>
            <ul class="videos-grid clearfix">
                <li
                        ng-repeat="playlist in videos.data track by $index">
                        <a title="@{{playlist.name}}" @if(auth()->user())
                            ui-sref=" playlistList({slug:playlist.slug})"
                             @else
                            ui-sref="login"
                             @endif
                            class="video-collections-links"> <span
                            class="video-icon-overlay"> <span
                                class="demo-label" ng-hide="true">demo</span>
                                <img
                                src="contus/base/images/no-preview.png"
                                alt="Owl Image"> <span
                                class="video-timing"> <span
                                    class="play-icons"></span>
                            </span>
                                <div class="videos-count-play">
                                    <strong>@{{playlist.videos|getCount}}</strong>
                                    <span>videos</span> <i>icons</i>
                                </div>
                        </span> <span class="trending-news-content">
                                <p class="settext-count">@{{playlist.name}}</p>
                        </span>
                        </a>
                        <div class="followers-count">
                            <p>
                                 @if(auth()->user())
                                <button
                                    class="follow-btn btn pull-right current-following" title="following" ng-click="unfoollow(playlist.slug)">following</button>
                                @else <a href="{{url()}}/#/login"
                                    class="follow-btn btn pull-right" title="follow">follow</a>
                                @endif
                            </p>
                        </div>
                </li>
            </ul>
        </div>
    </div>
</div>    @endsection
@include('customer::user.account.index')