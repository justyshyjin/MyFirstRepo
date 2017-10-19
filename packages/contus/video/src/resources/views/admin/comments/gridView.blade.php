<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="latest_video">
            
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="table_responsive">
                <table class="table playlist_table">
                    <thead>
                        <tr>
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
                            <td>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <select class="form-control mb15" data-ng-change="search()" data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
                                    <option value="all">{{trans('base::general.all')}}</option>
                                    <option value='1'>{{trans('video::playlist.banner.active')}}</option>
                                    <option value='0'>{{trans('video::playlist.banner.inactive')}}</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td data-ng-if="noRecords" colspan="@{{heading.length + 1}}" class="no-data">{{trans('base::general.not_found')}}</td>
                        </tr>
                      
                        <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat">
                            <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                            <td> <a title="@{{record.title}}" href="{{url('admin/videos/view-details-video')}}/@{{record.video.id}}" class="view-categories">@{{record.video.title}}</a>
                            </td> 
                            <td class="center" ng-show="@{{record.customer}}">                         
                                <p>@{{record.customer.name}}</p>
                            
                        </td>
                        <td class="center" ng-show="!@{{record.customer}}">                         
                                <p>Admin</p>
                            
                        </td>
                        
                            <td>
                                <p>@{{record.comment}}</p>
                            </td>
                            <td>
                                <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="commentsCtrl.updateStatus(record)" title="{{trans('video::videos.deactivate_comment')}}" data-boot-tooltip="true">{{trans('video::playlist.message.active')}}</span>
                                <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="commentsCtrl.updateStatus(record)" title="{{trans('video::videos.activate_comment')}}" data-boot-tooltip="true">{{trans('video::playlist.message.inactive')}}</span>
                            </td>
                            <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                         
                        </tr>
                    </tbody>
                </table>
            </div>
            @include('base::layouts.pagination')
        </div>
    </div>
</div>