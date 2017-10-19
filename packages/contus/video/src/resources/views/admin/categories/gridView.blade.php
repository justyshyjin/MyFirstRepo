<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="latest_video">
            <div class="tab_search clearfix">
                <div id="st-trigger-effects" class="search_upload_btn pull-right column">
                    <button data-effect="st-effect-17" data-intialize-sidebar data-ng-click="catgridCtrl.addCategory($event)" class="btn btn-primary upload_video pull-right">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        {{trans('video::categories.add_category')}}
                    </button>

                </div><Button data-ng-click="toggleTab('live_videos');" class="btn btn-primary " data-ng-class="{'active': tabSelected == 'live_videos'}">Preference default</Button>
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
                            <td class="search_product">
                                <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" title="{{trans('video::categories.enter_category_name')}}">
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <select class="form-control mb15" data-ng-change="search()" data-ng-model="searchRecords.is_active" data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
                                    <option value="all">{{trans('base::general.all')}}</option>
                                    <option value='1'>{{trans('video::collection.banner.active')}}</option>
                                    <option value='0'>{{trans('video::collection.banner.inactive')}}</option>
                                </select>
                            </td>
                            <td></td>
                            <td class="">
                                <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" data-original-title="{{trans('base::general.search_filter')}}">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td data-ng-if="noRecords" colspan="@{{heading.length + 1}}" class="no-data">{{trans('base::general.not_found')}}</td>
                        </tr>
                        <tr data-ng-if="showRecords" data-ng-repeat="record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                            <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                            <td>
                                <div class="product_img">
                                    <a ng-if="record.videocategory.length" href="{{url('admin/categories/videos')}}/@{{record.id}}">
                                        <span class="img_title">
                                            <img data-ng-if="record.image_url.length > 0" data-ng-src="@{{ record.image_url }}" alt="" />
                                            <img data-ng-if="record.image_url.length == 0" src="{{ url('contus/base/images/admin/no_image_available.jpg') }}" alt="" />
                                        </span>
                                        <p class="img_description">@{{record.title}}</p>
                                    </a>
                                    <a ng-if="!record.videocategory.length" class="disabled" style="cursor: no-drop;" href="javascript:;">
                                        <span class="img_title">
                                            <img data-ng-if="record.image_url.length > 0" data-ng-src="@{{ record.image_url }}" alt="" />
                                            <img data-ng-if="record.image_url.length == 0" src="{{ url('contus/base/images/admin/no_image_available.jpg') }}" alt="" />
                                        </span>
                                        <p class="img_description">@{{record.title}}</p>
                                    </a>
                                </div>
                            </td>
                            <td class="center">
                                <span style="margin-left: -53px;">@{{record.videocategory.length}}</span>
                            </td>
                            <td>
                                <span data-ng-if="record.parent_category.parent_category != null">@{{record.parent_category.parent_category.title}} > </span>
                                <span data-ng-if="record.parent_category != null">@{{record.parent_category.title}}</span>
                                <span data-ng-if="record.parent_category == null">-</span>
                            </td>
                            <td>
                                <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="catgridCtrl.updateStatus(record)" title="{{trans('video::categories.deactivate_category')}}" data-boot-tooltip="true">{{trans('video::categories.message.active')}}</span>
                                <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="catgridCtrl.updateStatus(record)" title="{{trans('video::categories.activate_category')}}" data-boot-tooltip="true">{{trans('video::categories.message.inactive')}}</span>
                            </td>
                            <td>@{{ $root.getFormattedDate(record.created_at) }}</td>
                            <td class="table-action">
                                <div class="column edit_table_icon" style="margin-right: 17px;">
                                    <a data-boot-tooltip="true" title="{{trans('video::videos.click_to_view_category_videos')}}" class="table_action" href="{{url('admin/categories/videos')}}/@{{record.id}}">
                                        <i class="fa fa-th-list" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div id="st-trigger-effects" class="column edit_table_icon" data-ng-show="record.is_deletable == 1">
                                    <button data-effect="st-effect-17" class="table_action" data-ng-click="catgridCtrl.editCategory(record)" title="{{trans('base::general.view_or_edit')}}" data-boot-tooltip="true">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <span data-ng-if="record.is_deletable == 1 && record.videocategory.length == 0 && record.child_category.length == 0" ng-mouseover="getTooltip($event)" class="delete_table_icon" title="{{trans('base::general.delete')}}" data-toggle="modal" data-target="#deleteModal" data-ng-click="deleteSingleRecord(record.id)" data-boot-tooltip="true">
                                    <i class="fa fa-trash-o"></i>
                                </span>
                                <span data-ng-if="record.is_deletable == 1 && (record.videocategory.length > 0 || record.child_category.length > 0)" ng-mouseover="getTooltip($event)" class="delete_table_icon delete_disabled" title="{{trans('video::categories.delete_disabled')}}" data-boot-tooltip="true">
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
