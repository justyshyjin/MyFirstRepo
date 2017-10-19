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
            <h3>My Favourites</h3>
            <ul class="videos-grid clearfix">
                <li data-ng-repeat="(id,video) in videos.data">
                    <div title ="@{{video.title}}">
                        <a  title="@{{video.title}}" ui-sref="videoDetail({slug:video.slug})"
                            class="video-collections-links"> <span
                            class="video-icon-overlay"> <span
                                class="demo-label" ng-hide="true">demo</span>
                                <img
                                ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.transcodedvideos[0].thumb_url}}"
                                src="contus/base/images/no-preview.png"
                                alt="Owl Image"> <span
                                class="video-timing"> <span
                                    class="play-icons"></span><span
                                    ng-hide="false" class="time-label">
                                        40min</span>
                            </span>
                                <div ng-hide="true"
                                    class="videos-count-play">
                                    <strong>31</strong> <span>videos</span>
                                    <i>icons</i>
                                </div>
                        </span><span class="trending-news-content">
                                <p class="settext-count">@{{video.title}}</p>
                        </span>
                        </a>
                        <div class="followers-count">
                            <p>
                                <span class="followers-counts">@{{video.created_at|convertDate|convertAgoTime}}</span>
                                <button title="Wishlist" ng-click="unfoollow(video.slug)"
                                    class="follow-btn btn pull-right wishlist favourited-wish"><i class="wishlist-icon"></i></button>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>    @endsection
@include('customer::user.account.index')