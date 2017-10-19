<section class="breadcrumbs-section">
    <div class="container">
        <div class="row">
            <nav class="breadcrumb" ng-if="(playlist.name)?true:false">
                <a class="breadcrumb-item" href="{{URL::to('/')}}">Home</a>
                <a class="breadcrumb-item" ui-sref="@{{(playlist.name)?'playlist':'category'}}">
                    <span>Playlists </span>
                </a>
                    <span>@{{playlist.name}}</span>
            </nav>
        </div>
    </div>
</section>
<section class="video-details">
    <div class="container">
        <div class="row">
        <div ui-view="examdetail"></div>
            <div class="col-md-4">
                <div class="row">
                    <div class="panel panel-default sub-widgets related-videos">
                        <div class="panel-heading common-header">@{{(playlist.name)?playlist.name:'Related Videos'}} <span class="count" ng-if="playlist.name">(@{{related.total}})</span></div>
                        <div class="panel-body scrollfinderrelated" custom-scroll="{ 'autoHide': false }">
                            <div class="media" scrollfinder ng-repeat="video in related.data" ng-class="{'active':($root.stateParams.video === video.slug )}">
                                <div class="media-left">
                                    <a ui-sref="playlistdetail({slug:slug,video:video.slug})" title="@{{video.title}}">
                                            <span ng-show="videos.is_demo" class="demo-label">demo</span>
                                            <img alt="64x64" class="media-object notification-img" ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.selected_thumb}}" src="contus/base/images/no-preview.png" data-holder-rendered="true">
                                            <span class="time-label" ng-show="!(videos.liveStatus)"> @{{video.video_duration}}</span>
                                        </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a ui-sref="playlistdetail({slug:slug,video:video.slug})"  ng-click="passroute(video.slug)" title=" @{{video.title}}"> @{{video.title}}</a>
                                    </h4>
                                    <span>@{{video.categories[0].title}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer show-more-videos text-center" ng-show="related.next_page_url !== null">
                            <img alt="" class="loader-show-more" src="contus/base/images/loader-ellipse.gif">
                        </div>
                    </div>

                           <div class="panel panel-default details-subscription">
                    <div class="panel-heading" >
                         <span class="validation-text"> <div class="panel-heading" >Get access to all Videos.
                        </div></span></div>
                         <div class="panel-body text-center">
                            <ul class="clearfix new-member-action">
                            <li ng-repeat="subcrp in subscription.data">
                                <strong class="name-card">@{{subcrp.name}}</strong>
                               <strong class="prices-oranges"><i class="fa fa-inr"></i> @{{subcrp.amount}}</strong>
                                <span class="video-valid-text">@{{subcrp.duration}} days</span>
                            </li>
                        </ul>
                            <a title="Subscribe Now to Watch" ui-sref="subscribeinfo" class="btn btn-green ripple full-btn" ng-click="subscribe(randsub.slug)">Subscribe Now to Watch</a>
                        </div>
                    </div>


                    <div class="panel panel-default sub-widgets related-videos">
                        <div class="panel-heading common-header">Recommended Videos</div>
                        <div class="panel-body" custom-scroll="{ 'autoHide': false }">
                            <div class="media" ng-repeat="video in recommended.data" ng-class="{'active':($root.stateParams.video === video.slug )}">
                                <div class="media-left">
                                    <a ui-sref="videoDetail({slug:video.slug})" title="@{{video.title}}">
                                            <span  ng-show="video.is_demo" class="demo-label">demo</span>
                                            <img alt="64x64" class="media-object notification-img" ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.selected_thumb}}" src="contus/base/images/no-preview.png" data-holder-rendered="true">
                                            <span class="time-label" ng-show="!(videos.liveStatus)"> @{{video.video_duration}}</span>
                                        </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a ui-sref="videoDetail({slug:video.slug})" title=" @{{video.title}}"> @{{video.title}}</a>
                                    </h4>
                                    <span>@{{video.categories[0].title}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default sub-widgets" ng-show="@{{tags.length}}">
                        <div class="panel-heading common-header">Search Tags</div>
                        <div class="panel-body searched-tags vd-searched-tags">
                            <a href="javascript:;" title="@{{tag.name}}" data-ng-repeat="tag in tags" ng-click="toggleSelectionTags(tag.id)" title="@{{tag.name}}">@{{tag.name}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
