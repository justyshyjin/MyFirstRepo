<div id="preloader">
    <div id="status">
        <i></i>
    </div>
</div>
<body>
   <div class="broadcast-body">
   <!-- header -->
     <header class="broadcast-header">
         <div class="container no-padding">
             <div class="hopmedia-header clearfix">
                <div class="hop-logo text-left col-md-4 col-sm-3 col-xs-12 no-padding">
                   <a href="#" ><h1 class="hopsprite broadcast-logo"></h1></a>                
                <div class="disply-inlne">
                   <div ng-hide="location.path() === '/'" style="cursor: pointer"><i class="hopsprite cat-iocn"></i><span>browse videos</span></div>
                    <div class="disply-inlne-content">
                        <div class="col-md-3 no-padding">
                            <div class="cat-list-browser">
                               <a ng-repeat="category in allcategory.response.categories" data-index=@{{$index}} ng-class="{active : $index == '0'}" ng-click="selectcategory($event)" href="javascript:void(0)">@{{category.title}}</a>                               
                            </div>
                        </div>
                        <div class="col-md-9 no-padding tab-content">
                                   <div ng-repeat="category in allcategory.response.categories" class="tab-content-inner" style="display:block">
                                        <ul class="cat-inside clearfix">
                                            <li ng-repeat="childcategory in category.child_category" class="col-sm-4 no-padding cat-inside-list"> 
                                             <a ui-sref="categoryvideos({slug:category.slug})"> 
                                                <p class="cat-image"><img src="@{{childcategory.image_url}}" alt=""></p> 
                                                <P class="cat-description">
                                                 <span class="cat-date">@{{childcategory.created_at|convertDate|convertAgoTime}}</span>
                                                  <span class="cat-name">@{{childcategory.title}}</span>
                                                  </p>
                                                 </a> 
                                            </li>
                                        </ul>
                                  </div>
                                   
                                  
                      
                        </div>
                   </div>
                   </div>
                   </div>
                <div class="sin-in text-right col-md-8  col-sm-9 col-xs-12 no-padding">
                    <div class="header-right clearfix">
                        <div class="col-md-9 col-sm-9 col-xs-8 no-padding">
                           <div class="search-box">
                           <form data-ng-submit="$root.filter()"
                                    search-root list="lists">
                                    <div class="input-group stylish-input-group">                                       
                                        <input list="searchsuggestions"
                                            type="text"
                                            class="broadcast-searchbox"
                                            placeholder="Search for videos"
                                            ng-model="fields.search">
                                        <datalist id="searchsuggestions">
                                            <option
                                                ng-repeat="video in $root.searchsuggesionlist"
                                                value="@{{video.title}}">

                                        </datalist>
                                        <span class="input-group-addon" style="background: none;">
                                            <button type="submit" style="background: none">
                                        </button>
                                        </span>
                                    </div>
                                </form>
                           </div>
                        </div>
                       @if(auth()->user())
                        <div class="col-md-3 col-sm-3 col-xs-4 no-padding">
                            <div class="signin-broadcaste">
                              <a role="menuitem" tabindex="-1" href="{{url('/auth/logout')}}">{{trans('base::adminsidebar.log_out')}}</a>
                            </div>
                        </div>
                         @else
                        <div class="col-md-3 col-sm-3 col-xs-4 no-padding">
                            <div class="signin-broadcaste">
                               <a href="#login" title="">sign in</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
             </div>
         </div>
     </header>
   <!-- header -->   
