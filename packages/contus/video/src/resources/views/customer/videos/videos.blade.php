 <div class="browse-all-video">
		   <div class="container no-padding">
		       <div class="name-filter clearfix">
		           <div class="col-md-6 no-padding">
		                 <h3>all categories</h3>
		           </div>
		           <div class="col-md-6 no-padding">
		                
		           </div>
		       </div>
		       <div class="browse-all-video-list">
		           <div class="row1 clearfix">
					   <div class="col-md-20 col-sm-20 col-xs-20" data-ng-repeat="video in videos.data">
					       <a @if(auth()->user()) ui-sref="videoDetail({slug:video.slug})"
										@else ui-sref="login" @endif >
		                       <div class="video-list">
			                       <div class="video-list-image"><img src="contus/base/images/no-preview.png"
                                            ng-src="@{{(video.thumbnail_image)?video.thumbnail_image:video.selected_thumb}}"></div>
			                       <div class="hover-top">
			                            <div class="hover-top-effect">
			                                 <h3>@{{video.title}}</h3>
			                            </div>
			                       </div>
		                       </div>
		                  </a>
					   </div>					   
				 </div>
    </div>
		   </div>
		</div>