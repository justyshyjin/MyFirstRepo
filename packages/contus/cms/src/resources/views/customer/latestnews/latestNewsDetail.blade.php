<div class="blog-container detailed-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="blog_post_banner blog_post_image">
						<img width="100%" height="400"
							ng-src="@{{blogdetail.post_image}}" src="{{$cdnUrl('images/no-preview.png')}}"
							class="img-responsive img-thumbnail">
					   </div>
					<h2 class="detailed-heading">@{{blogdetail.title}}</h2>
					<div class="">
						<span class="themonth small-sprittext">@{{blogdetail.created_at|convertDate|convertAgoTime}}</span>
						<span class="small-sprittext noborder">By <span class="">@{{blogdetail.post_creator}}</span></span>
					</div>
					<div class="blog-content">
						<div class="blog-content-expand"><p ng-bind-html="to_trusted(blogdetail.content)"></p></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>