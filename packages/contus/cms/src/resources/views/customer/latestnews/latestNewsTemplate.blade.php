<div class="blog-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div ng-repeat="article in blog.data" class="blog-details">
                    <div class="blog-heading-container clearfix">
                        <div class="calendar-date">
                            <span class="">@{{article.created_at|convertDate|convertAgoTime}}</span>
                        </div>
                        <h2 class="detailed-heading"><a ui-sref="blogdetail({slug:article.slug})" title="@{{article.title}}">@{{article.title}}</a></h2>
                    </div>
                    <div class="blog-content">
                        <div class="media">
                            <div class="media-left">
                                <a ui-sref="blogdetail({slug:article.slug})" title="@{{article.title}}" rel="bookmark">
                                    <img alt="" class="media-object" ng-src="@{{article.post_image}}" src="{{$cdnUrl('images/no-preview.png')}}" style="width: 220px; height: 120px;">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="blog-content-expand">
                                    <p ng-bind-html="to_trusted(article.content)"></p>
                                </div>
                                    <div class="readblog">
                                        <a ui-sref="blogdetail({slug:article.slug})" title="@{{article.title}}" rel="bookmark">Read more</a>
                                    </div>
                            </div>
                            <div class="post-info">
                                <span  class="creater-name" href="#!" class="theauthor">By @{{article.post_creator}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="showmoreBlog text-center">
                    <a text-align="center" class="view-more-comments" title="Show More" ng-show="blog.next_page_url !== null" href="javascript:;" ng-click="showmore()">Show More</a>
                </div>
            </div>
        </div>
    </div>
</div>
