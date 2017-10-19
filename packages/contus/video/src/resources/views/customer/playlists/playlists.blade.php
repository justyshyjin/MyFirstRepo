<section class="breadcrumbs-section">
    <div class="container">
        <div class="row">
            <nav class="breadcrumb">
                <a class="breadcrumb-item" href="{{url()}}">Home</a> <a
                    class="breadcrumb-item" ui-sref="playlist"><span>Playlist</span></a>
            </nav>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="playlist-collections-container"
                ng-repeat="subcat in category.data">
                <h2 class="playlist-collections-title current-list"
                    title="@{{subcat.title}}">@{{subcat.title}}</h2>
                <div class="playlist-collections-slider">
                    <div class="item" data-initialize-owl-carousel
                        data-owl-carousel-options="videoOwlCarouselOptions"
                        data-owl-parent="@{{subcat.slug}}"
                        ng-repeat="playlist in subcat.playlists.data track by $index">
                        <a title="@{{playlist.name}}" @if(auth()->user()) ui-sref="
                            playlistList({slug:playlist.slug})" @else
                            ui-sref="login" @endif
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
                                    <strong>@{{playlist.videos}}</strong>
                                    <span>videos</span> <i>icons</i>
                                </div>
                        </span> <span class="trending-news-content">
                                <p class="settext-count">@{{playlist.name}}</p>
                        </span>
                        </a>
                        <div class="followers-count">
                            <p>
                                <span class="followers-counts">@{{playlist.followers}}
                                    @{{(playlist.followers>1)?'followers':'follower'}}</span> @if(auth()->user())
                                <button title="@{{(playlist.following)?'following':'follow'}}"
                                    class="follow-btn btn pull-right"
                                    ng-click="togglefollowplaylist(subcat.slug,$index)"
                                    ng-class="{'current-following':playlist.following}">@{{(playlist.following)?'following':'follow'}}</button>
                                @else <a href="{{url()}}/#/login"
                                    class="follow-btn btn pull-right" title="Follow">follow</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer show-more-videos  text-center"
                ng-show="category.next_page_url !== null">
                <a href="javascript:;" title="Show More" class="viewall"
                    ng-click="loadmorecategories()">Show More</a>
            </div>
        </div>
    </div>
</section>
