<body>
    <section class="breadcrumbs-section">
        <div class="container">
            <div class="row">
                <nav class="breadcrumb">
                  <a class="breadcrumb-item" href="{{URL::to('/')}}">Home</a>
                    <a class="breadcrumb-item" href="javascript:;">
                        <span>Scheduled Live Videos </span>
                    </a>
                </nav>
            </div>
        </div>
    </section>
    <section class="live-videos-container">
        <div class="container">
            <div class="row"><div class="cs-title"><span class="center-title">Scheduled Live Videos</span></div>
                <div class="tabbable-panel live-video-tab">
                    <div class="tabbable-line" data-ng-init="showorhidedata = 1">
                        <div class="filter pull-right"></div>
                        <div class="tab-content" data-ng-show="showorhidedata">
                            <div class="tab-pane active" id="civil-service">

                                                <p class="mynorecordfound" ng-if='livevideos.data.length === 0'>
                                                    No Schedule Live Video(s) Found
                                                </p>
                                <ul ng-if='livevideos.data.length !== 0' class="live-video-collections clearfix" ng-class="{'searched-videos-list':searchedvideoslist}">
                                    <li data-ng-repeat="(id,video) in livevideos.data">
                                        <div class="forgrid" ng-hide="searchedvideoslist">
                                            <a ui-sref="liveDetail({slug:video.slug})">
                                                <span class="video-icon-overlay">
                                                    <span class="demo-label" ng-show="video.liveVideoTime">Live Now</span>
                                                    <img src="{{$cdnUrl('images/no-preview.png')}}" ng-src="@{{(video.selected_thumb)?video.selected_thumb:video.selected_thumb}}" alt="Owl Image">
                                                    <span class="video-timing" ng-show="video.liveVideoTime">
                                                        <span class="play-icons"></span>
                                                    </span>

                                            </a>
                                            <div ng-hide="true" class="videos-count-play">
                                                <strong>31</strong>
                                                <span>videos</span>
                                                <i>icons</i>
                                            </div>
                                            </span>
                                            <span class="trending-news-content">
                                                <p class="settext-count">@{{video.title.trunc(50)}}</p>
                                            </span>
                                            </a>                                            
                                        </div>
                                    </li>
                                </ul>
                                <div class="panel-footer show-more-videos  text-center" ng-show="livevideos.next_page_url !== null">
                                    <a href="javascript:;" class="viewall" ng-click="loadmorelivevideo()">Show More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</body>
</html>
