<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="latest_video">
            <div class="tab_search clearfix">
                <div class="search_upload_btn pull-right">
                    <a data-ng-hide="tabSelected =='live_videos'" title="{{trans('video::videos.upload_video')}}" href="javascript:void(0)" class="btn btn-primary upload_video pull-right" data-ng-click="vgridCtrl.showUploadOption()">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                        {{trans('video::videos.upload_video')}}
                    </a>
                    <a data-ng-show="tabSelected =='live_videos'" href="{{url()}}/admin/videos/add" id="add_playlist" data-boot-tooltip="true" data-toggle="tooltip" class="btn btn-primary  pull-right mr10" >Add Stream</a>
                    <a data-ng-hide="tabSelected =='live_videos'" href="#" id="move_collection" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('video::videos.select_video_in_the_grid_to_add_a_exam')}}" class="btn move_collection_btn  pull-right mr10" data-toggle="modal" data-target="#myModal" data-ng-disabled="vgridCtrl.selectedRecords == 0" data-ng-click="vgridCtrl.resetFormData($event)">{{trans('video::videos.move_to_exam')}}</a>
                    <a data-ng-hide="tabSelected =='live_videos'" href="#" id="move_playlist" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('video::videos.select_video_in_the_grid_to_add_a_playlist')}}" class="btn move_collection_btn  pull-right mr10" data-toggle="modal" data-target="#myModalplaylist" data-ng-disabled="vgridCtrl.selectedRecords == 0" data-ng-click="vgridCtrl.resetFormDataPlaylist($event)">{{trans('video::videos.move_to_playlist')}}</a>
                </div>
                <div class="dropdown" style="float: left; right: 20px;" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="Select video in the grid to perform a bulk action">
                    <button class="btn btn-warning dropdown-toggle" data-ng-disabled="vgridCtrl.selectedRecords == 0" type="button" data-toggle="dropdown">
                        Bulk Action
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.deleteBulkRecord()" href="#">Delete</a>
                        </li>
                        <li>
                            <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.activateOrDeactivateBulkRecord('activate')" href="#">Activate</a>
                        </li>
                        <li>
                            <a data-toggle="modal" data-target="#videoBulkDeleteModal" ng-click="vgridCtrl.activateOrDeactivateBulkRecord('deactivate')" href="#">Deactivate</a>
                        </li>
                    </ul>
                </div>
                {{--<Button href="#" data-ng-click="toggleTab('live_videos');" class="btn btn-primary " data-ng-class="{'active': tabSelected == 'live_videos'}">{{trans('video::videos.livevideos')}}</Button>--}}
                 <a href="{{url('admin/youtube-live')}}" ng-if="livedetails.updated_at" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="@{{(livedetails.status)?'Synced':'Sync Failed'}} @{{livedetails.updated_at}}@{{(livedetails.status)?'':', Please sync manually to re-initiate sync'}}" class="btn " ng-class="{'btn-info':livedetails.status,'btn-danger':!livedetails.status}">{{trans('video::videos.synclivevideos')}} (@{{(livedetails.status)?'Synced':'Sync Failed'}} @{{livedetails.updated_at}})</a>
                <a href="{{url('admin/youtube-live')}}" ng-if="!livedetails.updated_at" class="btn btn-info " ng-class={}>{{trans('video::videos.synclivevideos')}}</a>
            </div>
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="table_responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <div class="ckbox ckbox-default">
                                    <input type="checkbox" id="selectall" value="1" data-ng-click="vgridCtrl.selectAllRecords()" />
                                    <label for="selectall" class="nopadding"></label>
                                </div>
                            </th>
                            <th class="center">{{trans('base::general.s_no')}}</th>
                            <th data-ng-repeat="field in heading">
                                @{{field.name}}
                                <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                                <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="search_text">
                            <td></td>
                            <td></td>
                            <td class="search_product">
                                <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" title="{{trans('video::videos.enter_video_title')}}">
                            </td>
                            <td data-ng-hide="tabSelected =='live_videos'"></td>
                            <td data-ng-hide="tabSelected =='live_videos'"></td>
                            <td>
                                <select class="form-control mb15" data-ng-change="search()" data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
                                    <option value="all">{{trans('base::general.all')}}</option>
                                    <option value='1'>{{trans('video::collection.banner.active')}}</option>
                                    <option value='0'>{{trans('video::collection.banner.inactive')}}</option>
                                </select>
                            </td>
                            <td data-ng-show="tabSelected =='live_videos'">
                                <select class="form-control mb15" data-ng-change="search()" data-ng-model="searchRecords.type" data-boot-tooltip="true" title="{{trans('base::general.type')}}">
                                    <option value="all">{{trans('base::general.all')}}</option>
                                    <option value='wowza'>Wowza</option>
                                    <option value='private'>Youtube private</option>
                                    <option value='public'>Youtube public</option>
                                    <option value='unlisted'>Youtube unlisted</option>
                                </select>
                            </td>
                            <td></td>
                            <td data-ng-hide="tabSelected !='live_videos'"></td>
                            <td></td>
                            <td>
                                <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" title="{{trans('base::general.search_filter')}}">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td data-ng-if="noRecords" colspan="@{{heading.length + 2}}" class="no-data">{{trans('base::general.not_found')}}</td>
                        </tr>
                        <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                            <td>
                                <div class="ckbox ckbox-default">
                                    <input type="checkbox" class="checkbox" id="roles_@{{record.id}}" ng-click="vgridCtrl.selectRecord($event, record.id)" value="@{{record.id}}" name="selectedCheckbox[]">
                                    <label for="roles_@{{record.id}}"></label>
                                </div>
                            </td>
                            <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                            <td>
                                <div class="product_img">
                                    <a href="{{url('admin/videos/view-details-video')}}/@{{record.id}}">
                                        <span class="img_title" data-ng-if="record.job_status == 'Complete'">
                                            <img src="{{url('contus/base/images/no-preview.png')}}" data-ng-src="@{{ record.selected_thumb }}" alt="" />
                                        </span>
                                        <span class="img_title" data-ng-if="record.job_status != 'Complete'">
                                            <img src="{{url('contus/base/images/no-preview.png')}}" alt="" />
                                        </span>
                                        <p class="img_description">@{{record.title}}

                                        </p>
                                    </a>
                                    <div ng-if="record.username !== ''">
                                        <button class="btn btn-sm ng-scope" data-toggle="modal" data-target="#deleteModals@{{ record.id }}"><i class="fa fa-info-circle"></i>
                                            <span class="tooltip">Credentials</span>
                                        </button>
                                        {{--Modal Starting--}}
                                        <div class="modal fade" id="deleteModals@{{record.id}}" data-role="dialog">
                                            <div class="modal-dialog modal-sm">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h3 class="modal-title">Wowza Credentials</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                    <span><code>Wowza Push URL : @{{record.source_url}}</code></br>
                                                        <code>Source Name : @{{record.stream_name}}</code> </br>
                                                        <code>Username :  @{{record.username}}</code> </br>
                                                        <code>Password :  @{{record.password}}</code>
                                                    </span>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--Modal Ending--}}
                                    </div>
                                </div>

                            </td>
                            <td data-ng-if="tabSelected !='live_videos'">
                                <div data-ng-hide="tabSelected =='live_videos'" data-ng-repeat="category in record.videocategory track by $index">
                                    <span>@{{ category.category.title }}</span>
                                    <span data-ng-if="record.videocategory.length != $index+1">,</span>
                                </div>
                            </td>
                            <td data-ng-if="tabSelected !='live_videos'">
                                <div data-ng-hide="tabSelected =='live_videos'" data-ng-repeat="category in record.collections track by $index">
                                    <span>@{{ category.name }}</span>
                                    <span data-ng-if="record.collections.length != $index+1">,</span>
                                </div>
                            </td>
                            <td>
                                <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="vgridCtrl.updateStatus(record)" title="{{trans('video::videos.deactivate_video')}}" data-boot-tooltip="true">{{trans('video::videos.message.active')}}</span>
                                <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="vgridCtrl.updateStatus(record)" title="{{trans('video::videos.activate_video')}}" data-boot-tooltip="true">{{trans('video::videos.message.inactive')}}</span>
                            </td>
                            <td data-ng-if="tabSelected =='live_videos'">
                                <span class="label label-primary" ng-if="record.username !== ''"> Wowza</span>
                                <span class="label label-warning" ng-if="record.youtubePrivacy == 'private'"> Youtube @{{record.youtubePrivacy}}</span>
                                <span class="label label-primary" ng-if="record.youtubePrivacy == 'unlisted'">  Youtube @{{record.youtubePrivacy}}</span>
                                <span class="label label-success" ng-if="record.youtubePrivacy == 'public'">  Youtube @{{record.youtubePrivacy}}</span>
                            </td>
                            <td data-ng-if="tabSelected =='live_videos'">
                                <span class="label label-success" ng-if="record.liveStatus == 'starting'">Initiating</span>
                                <span class="label label-success" ng-if="record.liveStatus == 'started'">Live</span>
                                <span class="label label-warning" ng-if="record.liveStatus == 'ready'">Ready</span>
                                <span class="label label-info" ng-if="record.liveStatus == 'stopped'">Completed</span>
                                <span class="label label-info" ng-if="record.liveStatus == 'complete'">{{trans('video::videos.recorded')}}</span>
                                <span class="label label-success" ng-if="record.liveStatus == 'live'">Live</span>
                            </td>
                            <td data-ng-if="tabSelected !='live_videos'">
                                <span class="label label-primary" ng-if="record.job_status == 'Video Uploaded' || record.job_status == 'Submitted'">{{trans('video::videos.uploaded_status')}}</span>
                                <span class="label label-warning" ng-if="record.job_status == 'Progressing'">@{{record.job_status}}</span>
                                <span class="label label-success" ng-if="record.job_status == 'Complete'">@{{record.job_status}}</span>
                                <span class="label label-danger" ng-if="record.job_status == 'Error' || record.job_status == 'Canceled'">{{trans('video::videos.error_status')}}</span>
                                <span class="label label-info" ng-if="record.job_status == 'Uploading'">@{{record.job_status}}</span>
                                <span class="label label-info" ng-if="record.job_status == 'Uploaded'">@{{record.job_status}}</span>
                                <span class="label label-info" ng-if="record.job_status == 'Added'">@{{record.job_status}}</span>
                            </td>
                            <td data-ng-if="tabSelected =='live_videos'"><span ng-if="record.username === ''">@{{ (record.scheduledStartTime)?$root.getFormattedDate(record.scheduledStartTime,'datetime'):'' }}</span>
<button ng-if="record.username !== ''" ng-show="record.liveStatus==='ready'||record.liveStatus==='stopped'" ng-click="startlivestream(record)" class="label label-success">Start</button>
                                        <button ng-if="record.username !== ''" ng-click="stoplivestream(record)" ng-show="record.liveStatus==='started'" class="label label-danger" >Stop</button>
                            </td>
                            <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                            <td class="action">
                                <div class="column edit_table_icon">
                                    <a data-boot-tooltip="true" title="{{trans('video::videos.edit_video')}}" class="table_action" href="{{url('admin/videos/details-video-edit')}}/@{{record.id}}">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <span class="delete_table_icon" title="{{trans('base::general.delete')}}" data-toggle="modal" data-target="#videoDeleteModal" data-ng-click="vgridCtrl.deleteSingleRecordVideos(record.id)" data-boot-tooltip="true">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @include('base::layouts.pagination')
        </div>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('video::collection.add_to_exam')}}</h5>
                <p>Organize and display your subscription videos on the web and in your apps.</p>
            </div>
            <div class="modal-body">
                <form name="collectionForm" method="POST" data-base-validator="" data-ng-submit="vgridCtrl.save($event)" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="collection_model">
                        <div class="collection_listing">
                            <div class="rdio rdio-primary collection_listing_input" data-ng-repeat="(key, value) in vgridCtrl.allCollections">
                                <input type="radio" name="radio" data-ng-value="@{{key}}" data-ng-model="vgridCtrl.collection.id" data-ng-change="vgridCtrl.createCollection(key)" id="radioPrimary_@{{key}}">
                                <label for="radioPrimary_@{{key}}" data-ng-class="{'text-primary': key == 0}">@{{value}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default  mr10" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn btn-primary ">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="myModalplaylist" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('video::playlist.add_to_playlist')}}</h5>
                <p>Organize and display your subscription videos on the web and in your apps.</p>
            </div>
            <div class="modal-body">
                <form name="playlsitForm" method="POST" data-base-validator="" data-ng-submit="vgridCtrl.Playlistsave($event)" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="collection_model">
                        <div class="collection_listing">
                            <div class="rdio rdio-primary collection_listing_input" data-ng-repeat="(key, value) in vgridCtrl.allPlaylists">
                                <input type="radio" name="radio" data-ng-value="@{{key}}" data-ng-model="vgridCtrl.playlsit.id" data-ng-change="vgridCtrl.createPlaylist(key)" id="radioPrimaryplay_@{{key}}">
                                <label for="radioPrimaryplay_@{{key}}" data-ng-class="{'text-primary': key == 0}">@{{value}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default  mr10" data-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn btn-primary ">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="videoPresetsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('video::videos.presets_of_video')}}</h5>
            </div>
            <div class="modal-body">
                <div class="preset_wrap" data-ng-repeat="preset in vgridCtrl.commonVideoPresets track by $index">@{{ $index+1 }}. @{{ preset }}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr10" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

