<div class="video-ply">
   <div class="container clearfix">
   			<div class="video-player">
   			<div class="big-video-container">
                    <div class="big-video">
                        <div init-flow-player video="videos" class="flowplayer functional"></div>
                    </div>
                </div>
			
        	</div>
	</div>  
    </div>
    <div class="vid-detail">
		    <div class="vid-detail-main-section container clearfix">
			    <div class="video-sidebar">
				    <div class="vid-head">
				    <div class="headline clearfix">
				    
				    <h5>@{{videos.categories[0].title}}</h5>
				    <h1>@{{videos.title}} </h1>
					<span>@{{videos.created_at|convertDate|date:'MMM dd, yyyy'}}</span>
					</div>
					<p>@{{videos.description}}</p>
					<span class="cast"><strong>Director:</strong><span ng-repeat="auther in videos.videocast"> @{{auther.role}}</span></span>
					<span class="cast"><strong>Cast:</strong><span ng-repeat="cast in videos.videocast"> @{{cast.name}}</span></span>
				    </div>
				    		<div class="secondary-action clearfix">
							    <div class="vid-action">
							    <button class="vid-action-btn-add" ng-disabled="watchlaterstatus==1" ng-class="{true: 'active'}[watchlaterstatus == 1]" ng-click="watchlater(videos.id)"><i class="hopsprite add-icon"></i><span>Watch Later</span></button>							    
							    </div>
							    <div class="vid-action-rating">
							    <ul>
							    <li><button class="vid-action-btn-like" ng-disabled="likestatus.like_count==1" ng-class="{true: 'active'}[likestatus.like_count == 1]" ng-click="videolike(videos.id,'like')">
							    <i class="hopsprite like-icon"></i>
								<span class="">@{{likescount}}</span>
								</button></li>
								<li>
								<button class="vid-action-btn-unlike" ng-disabled="likestatus.dislike_count==1" ng-class="{true: 'active'}[likestatus.dislike_count == 1]" ng-click="videolike(videos.id,'dislike')">
								<i class="hopsprite unlike-icon"></i>
								<span class="">@{{dislikescount}}</span>
								</button></li> 
								</ul>								
							    </div>
						    </div>
						    
						    
						    
			    </div>
			    
			    <div class="rightside-bar">
			    <h4>Related Videos</h4>
			    <div ng-repeat="related in related.data" class="related-videos-recent clearfix">
			    <div class="related-vds">
			    <a href="javascript:;" ng-click="passroute(related.slug)" title="@{{related.title}}">
			    <img width="140" ng-src="@{{(related.thumbnail_image)?related.thumbnail_image:related.selected_thumb}}" src="contus/base/images/no-preview.png">
			    <span>@{{related.video_duration}}</span>
			    </a>
			    </div>
			    <div class="video-relatd">
			    <h4>@{{related.title}}</h4>
			    <span class="auther">@{{related.created_at|convertDate|date:'MMM dd, yyyy'}}</span>
			    </div>
			    </div>
			   
			    
    		</div>
    		<div class="video-sidebar">
    		<div class="comment-section clearfix">
						    <h2><strong>@{{(comment.total)?comment.total:0}}</strong> Comments</h2>
						    <div class="comment-field">
						    <i class="hopsprite pro-pic"></i>
							
							<textarea ng-model="parentcomment" type="comments" name="firstname" class="add-yr-comment" placeholder="Add Your Comments..."></textarea>
							<div class="sbmt-comment">
								<input type="button" ng-click="parentcomment=''" value="Cancel" class="cancel">
								<input type="button" ng-click="postparentcomment(parentcomment)" value="Comment" class="commment">
							</div>
							</div>
							<div class="text-center">
                            <a title="Show Previous" class="view-more-comments"
                                ng-show="comment.prev_page_url !== null"
                                href="javascript:;"
                                ng-click="loadprevcomment()">Show
                                Previous</a>
                        </div>
						    
						    </div>
						    
						    <div class="secondary-comment-section clearfix">
						    <div ng-repeat="com in comment.data">
						    <div class="main-comment" > 
						    <div class="pro-picture">
						    <img src="contus/base/images/admin/user_images.png" ng-src="@{{com.customer.profile_picture}}"></div>
						    <div class="comment-part">
						    <div class="person-comment clearfix">
						    <h3>@{{com.customer.name}}</h3><span class="time">@{{com.created_at|convertDate|convertAgoTime}}</span>
						    <p>@{{com.comment}}</p>
						    
						    <p>
                                    <a href="javascript:;"
                                        class="reply-link"
                                        ng-click="isReplyFormOpen = !isReplyFormOpen"><span
                                        ng-hide="isReplyFormOpen" title="Reply"><i class="hopsprite reply-button"></i></span><span ng-hide="!isReplyFormOpen" title="Close">close</span></a>                                    
                                </p>
                                <div ng-show="isReplyFormOpen" class="comment-field">
						    <i class="hopsprite pro-pic"></i>
							
							<textarea ng-model="childcomment[com.id]" type="comments" name="firstname" class="add-yr-comment" placeholder="Add Your Comments..."></textarea>
							<div class="sbmt-comment">
								<input type="button" ng-click="childcomment=''" value="Cancel" class="cancel">
								<input type="button" ng-click="postchildcomment(com.id,childcomment[com.id])" value="Comment" class="commment">
							</div>
							</div>
						    </div>
						    </div>
						    </div>   
						    <div ng-repeat="reply in com.reply_comment">
						    <div class="reply-comment" > 
						    <div class="pro-picture">
						    <img src="contus/base/images/admin/user_images.png" ng-src="@{{reply.customer.profile_picture}}"></div>
						    <div class="comment-part">
						    <div class="person-comment clearfix">
						    <h3>@{{reply.customer.name}}</h3><span class="time">@{{reply.created_at|convertDate|convertAgoTime}}</span>
						    <p>@{{reply.comment}}</p>
						    </div>
						    </div>
						    </div>						    
						    </div></div>
						    
						    
						    <div class="viewmore">
						    <a class="viewmore-btn" title="View More"
                                ng-show="comment.next_page_url !== null"
                                href="javascript:;"
                                ng-click="loadmorecomment()">View More</a>
							</div>
						    
						    </div>
    		</div>
    </div>
       