<div class="menu_container clearfix">
    <div class="page_menu pull-left">
        <ul class="nav">
            <li>
                <a href="{{url('admin/videos')}}" data-ng-click="selectTab('normal_videos')" data-ng-class="{'active': requestParams.grid == 'videos'}" >{{trans('video::videos.videos')}}</a>
            </li>

            <li>
                <a href="{{url('admin/categories')}}" data-ng-class="{'active': requestParams.grid == 'categories'}" >{{trans('video::categories.categories')}}</a>
            </li>
            <li>
                <a href="{{url('admin/playlists')}}" data-ng-class="{'active': requestParams.grid == 'playlists'}" >{{trans('video::playlist.playlists')}}</a>
            </li>
            <li>
                <a href="{{url('admin/collections')}}" data-ng-class="{'active': requestParams.grid == 'collections'}" >{{trans('video::collection.exams')}}</a>
            </li>
             <li>
                <a href="{{url('admin/examgroups')}}" data-ng-class="{'active': requestParams.grid == 'examgroups'}" >{{trans('video::playlist.groups')}}</a>
            </li>
            <li>
                <a href="{{url('admin/presets')}}" data-ng-class="{'active': requestParams.grid == 'presets'}" >{{trans('video::videos.presets')}}</a>
            </li>
            <li>
                <a href="{{url('admin/comments')}}" data-ng-class="{'active': requestParams.grid == 'comments'}" >{{trans('video::videos.comment')}}</a>
            </li>
        </ul>
    </div>
</div>