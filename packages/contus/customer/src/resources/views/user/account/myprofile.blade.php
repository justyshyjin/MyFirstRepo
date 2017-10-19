    @section('profilecontent')
<div class="col-md-9">
	<div class="row">
		<div class="subscription-contanier">
			<div class="row">
				<div class="col-md-9" >
					<h5>Upgrade to @{{subscription.name}}</h5>
					<p>
						<span class="text-blue">@{{subscription.amount}}</span> @{{subscription.description}}
					</p></div>
					<div class="col-md-3">
					<a title="Subscribe now" class="btn full-btn btn-subscription" ui-sref="subscribeinfo" >Subscribe now</a>
				   </div>


			</div>
		</div>
		<div class="panel panel-default myaccount">
			<div class="panel-heading">
				My Profile <a title="Edit" class="pull-right edit-info" ui-sref="editProfile">Edit</a>
			</div>
			<div class="panel-body">
				<div class="user-acc-details">
					<div class="media">
					<div class="media-left">
					
							<img alt="" class="media-object img-circle" data-src="holder.js/64x64" src="contus/base/images/user.png" data-holder-rendered="true" style="width: 120px; height: 120px;"> </div>
							<div class="media-body"> <h4 class="media-heading text-blue">@{{profile.name}}</h4> <p>@{{profile.phone}}</p> <p>@{{profile.email}}</p></div> </div>
				</div>
			</div>
		</div>
		<div class="recently-viewed" ng-hide="true">
			<h3>Recently viewed</h3>
			<ul class="videos-grid clearfix">
				<li>
					<div class="">
						<a href="#!" title="Current Affairs Q&amp;A 39th Week (26th..." class="video-collections-links"> <span
							class="video-icon-overlay"> <span class="demo-label">demo</span>
								<img src="contus/base/images/0001.jpg" alt="Owl Image"> <span
								class="video-timing"> <span class="play-icons"></span><span
									class="time-label"> 40min</span>
							</span>

						</span> <span class="trending-news-content">
								<p class="settext-count">Current Affairs Q&amp;A 39th Week (26th
									...</p>
						</span>

						</a>
						<div class="followers-count">
							<p>
								<span class="followers-counts">1 Year ago</span>
								<button title="wishlist" class="follow-btn btn pull-right wishlist">
									<i class="wishlist-icon"></i>
								</button>
							</p>
						</div>
					</div>
				</li>
				<li>
					<div class="">
						<a href="#!" class="video-collections-links"> <span
							class="video-icon-overlay"> <span class="demo-label">demo</span>
								<img src="contus/base/images/0001.jpg" alt="Owl Image"> <span
								class="video-timing"> <span class="play-icons"></span><span
									class="time-label"> 40min</span>
							</span>

						</span> <span class="trending-news-content">
								<p class="settext-count">Current Affairs Q&amp;A 39th Week (26th
									...</p>
						</span>

						</a>
						<div class="followers-count">
							<p>
								<span class="followers-counts">1 Year ago</span>
								<button class="follow-btn btn pull-right wishlist">
									<i class="wishlist-icon"></i>
								</button>
							</p>
						</div>
					</div>
				</li>
				<li>
					<div class="">
						<a href="#!" class="video-collections-links"> <span
							class="video-icon-overlay"> <span class="demo-label">demo</span>
								<img src="contus/base/images/0001.jpg" alt="Owl Image"> <span
								class="video-timing"> <span class="play-icons"></span><span
									class="time-label"> 40min</span>
							</span>

						</span> <span class="trending-news-content">
								<p class="settext-count">Current Affairs Q&amp;A 39th Week (26th
									...</p>
						</span>

						</a>
						<div class="followers-count">
							<p>
								<span class="followers-counts">1 Year ago</span>
								<button class="follow-btn btn pull-right wishlist">
									<i class="wishlist-icon"></i>
								</button>
							</p>
						</div>
					</div>
				</li>

			</ul>
		</div>
	</div>
</div>@endsection
@include('customer::user.account.index')
