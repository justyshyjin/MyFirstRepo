 <!-- section -->
        <section class="broadcast-extra">
          <div class="hopmedia-broadcaste-banner">
              <img class="img-responsive" src="assets/images/homepage_banner.jpg" alt="" height="" width="">
          </div>
          <div class="container no-padding">
            <div class="broadcaste-banner-overlay">
               <h3>Watch TV Shows & Movies anytime, anywhere</h3>
               <p>Plan from $7.99 a month</p>
               <div class="join-for-free">
                  <a ui-sref="subscribeinfo()" title="">Join free for a month</a>
               </div>
            </div>
          </div>
       </section>
   <!-- section --> 
   <!-- section -->
       <section class="broadcast-media text-center">
            <div class="container">
                <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                  <div class="broadcast-media-inner">
                       <i class="hopsprite broadcast-media-tv"></i>
                       <h3>Watch anywhere</h3>
                       <p>Watch TV shows and movies anytime, anywhere — personalized for you.</p>
                  </div>
               </div>
               <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                  <div class="broadcast-media-inner">
                       <i class="hopsprite broadcast-media-movie"></i>
                       <h3>Watch anywhere</h3>
                       <p>Smart TVs, PlayStation, Xbox, Chromecast , Apple TV,Blu-ray players and more..</p>
                  </div>
               </div>
               <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                  <div class="broadcast-media-inner">
                       <i class="hopsprite broadcast-media-awards"></i>
                       <h3>Watch anywhere</h3>
                       <p>If you decide isn't you no problem No commitment.Cancel online anytime.</p>
                  </div>
               </div>
            </div>
       </section>
   <!-- section --> 
   <!-- section -->
       <section class="moviesandseries">
            <div class="container no-padding">
                <div class="unlimited-movie-series clearfix">
                   <div class="col-md-6 col-sm-6">
                       <div class="unlimited-movie-series-content">
                             <h2>Unlimited movies and series</h2>
                             <p>Keep your growing library organized and accessible. Perfect your images and create beautiful gifts for sharing. And with iCloud Photo Library, you can store a lifetime’s worth of photos and videos in the cloud.</p>
                       </div>
                   </div>
                   <div class="col-md-6 col-sm-6">
                       <div class="unlimited-movie-series-image  ">
                             <img src="assets/images/unlimited_movie_series_image.jpg" alt="" height="" width="">
                       </div>
                   </div>
                </div>
            </div>
       </section>
   <!-- section --> 
   <!-- section -->
     <section class="browse-unlimited">
         <div class="container no-padding">
             <h3>Browse Unlimited Videos</h3>
             <div class="browse-unlimited-slider slideanim">
                  <div class="item">
                     <div class="item" data-initialize-owl-carousel
                        data-owl-carousel-options="videoOwlCarouselOptions"
                        ng-repeat="video in data.response.videos.data"                    
                        title="@{{video.title}}">
                           <div class="col-md-6 col-sm-12 col-xs-12 " ng-class="{ 'res-bottom' : $index%5 == 0 }" ng-show="$index%5 == 0">
                               <div class="" ng-class="{ 'left-browse-unlimited' : $index%5 == 0 , 'row' : $index%5 == 1, 'row' : $index%5 == 2 ,'row carousel-top' : $index%5 == 3,'row carousel-top' : $index%5 == 4 }">
                                  <a  ui-sref="videoDetail({slug:video.slug})" title="">
                                  <img class="img-responsive" src="@{{(video.thumbnail_image)?video.thumbnail_image:video.transcodedvideos[0].thumb_url}}" alt=""></a>
                               </div> 
                           </div>
                         
                      </div>
                      
           </div>
             </div>
             <div class="browser-all-video"><a ui-sref="videos()"><b>Browse all videos</b></a></div>
         </div>
     </section>
   <!-- section -->
  <!-- section -->
     <section class="watch-all-videos">
            <h3>Watch all-new tv episodes and hottest shows </h3>
            <div class="join-free-a-month">
               <img class="img-responsive "  src="assets/images/watch_all_videos.jpg" alt="">
                <div class="watch-all-videos-button"><a href="#"><b>join free for a month</b></a></div>
            </div>
     </section>
  <!-- section -->
  <!-- section -->
       <section class="subscription-plan">
           <div class="container  no-padding">
                <div class="subscription-plan-methods clearfix">
                     <div class="col-md-3 col-sm-5">
                         <div class="getmonth-free">
                              <p>Get <b>one month</b> free on us</p>
                              <div class="getmonth-free-link"><a href="#" title="">Join free for a month</a></div>
                         </div>
                     </div>
                      <div class="col-md-9 col-sm-7">
                           <div class="getmonth-free-table clearfix">
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><b>Basic</b></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><b>Premium</b></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Monthly price after free month</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><b>$ 5000</b></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><b>$ 600</b></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>HD available</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-remove"></span></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></span></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Ultra HD available</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-remove"></span></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Watch on your laptop, TV, phone and tablet</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Watch on your laptop, TV, phone and tablet</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Watch on your laptop, TV, phone and tablet</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                              </div>
                              <div class="row-color clearfix">
                                  <div class="col-md-8 col-sm-8 col-xs-6 no-padding">
                                     <p>Watch on your laptop, TV, phone and tablet</p>
                                  </div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                                  <div class="col-md-2 col-sm-2 col-xs-3 text-center no-padding"><span class="glyphicon glyphicon-ok"></div>
                              </div>
                         </div>
                      </div>
                </div>
           </div>
       </section>
  <!-- section -->
