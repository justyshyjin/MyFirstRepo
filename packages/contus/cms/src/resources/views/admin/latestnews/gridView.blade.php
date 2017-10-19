<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="latest_news">
            <div class="tab_search clearfix">
                <div id="st-trigger-effects" class="search_upload_btn pull-right">
                     <a  href="{{url()}}/admin/latest/edit-latest-content/add"  class="btn btn-primary upload_video pull-right">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        {{trans('cms::latestnews.create_news')}}
                    </a>
                </div>
            </div>
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" data-ng-init="latestNewsCtrl.setQuery('{{auth()->user()->id}}')">
                    <thead>
                        <tr>
                            <th class="center">{{trans('cms::latestnews.serial_no')}}</th>
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
                                <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::latestnews.enter_title')}}">
                            </td>
                            <td class="search_product">
                                <input type="text" class="form-control" data-ng-model="searchRecords.post_creator" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::latestnews.enter_author')}}">
                            </td>
                            <td>
                            <td></td>
                            <td>
                                <select class="form-control mb15" data-boot-tooltip="true" data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_status')}}">
                                    <option value="all">{{trans('base::general.all')}}</option>
                                    <option value='1'>{{trans('cms::latestnews.active')}}</option>
                                    <option value='0'>{{trans('cms::latestnews.inactive')}}</option>
                                </select>
                            </td>
                            <td class="">
                                <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('base::general.search_filter')}}">
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
                            <td>@{{record.title}}</td>
                            <td>@{{record.post_creator}}</td>
                            <td>
                                <img alt="" class="cs-testimonial-img" ng-src="@{{record.post_image}}" src="{{url('contus/base/images/admin/no_image_available.jpg')}}">
                            </td>
                            <td>@{{record.created_at}}</td>
                            <td>
                                <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('cms::latestnews.deactivate_user')}}" data-boot-tooltip="true">{{trans('cms::latestnews.message.active')}}</span>
                                <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('cms::latestnews.activate_user')}}" data-boot-tooltip="true">{{trans('cms::latestnews.message.inactive')}}</span>
                            </td>
                            <td class="table-action">
                                <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit">
                                    <a data-boot-tooltip="true" title="" class="table_action" href="{{url()}}/admin/latest/edit-latest-content/@{{record.id}}" data-original-title="Edit">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <span ng-mouseover="getTooltip($event)" title="{{trans('base::general.delete')}}" data-toggle="modal" data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
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
<!-- To add or edit the lastest news  -->
<nav class="st-menu st-effect-17" id="menu-17">
    <div class="pop_over_continer">
        <form name="latestNewsForm" method="POST" data-base-validator data-ng-submit="latestNewsCtrl.save($event,latestNewsCtrl.latestnews.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5 data-ng-if="!latestNewsCtrl.latestnews.id">{{trans('cms::latestnews.news_heading')}} - {{trans('cms::latestnews.add_new_news')}}</h5>
                <h5 data-ng-if="latestNewsCtrl.latestnews.id">{{trans('cms::latestnews.news_heading')}} - {{trans('cms::latestnews.edit_new_news')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                    <label class="control-label">
                        {{trans('cms::latestnews.title')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="name" data-ng-model="latestNewsCtrl.latestnews.title" class="form-control" placeholder="{{trans('cms::latestnews.title_placeholder')}}" value="{{old('title')}}" />
                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.content.has}">
                    <label class="control-label">
                        {{trans('cms::latestnews.content')}}
                        <span class="asterisk">*</span>
                    </label>
                    <textarea type="text" name="phone" class="form-control" data-ng-model="latestNewsCtrl.latestnews.content" placeholder="{{trans('cms::latestnews.content_placeholder')}}" value="{{old('content')}}" rows="5" cols="50"></textarea>
                    <p class="help-block" data-ng-show="errors.content.has">@{{ errors.content.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.post_creator.has}">
                    <label class="control-label">{{trans('cms::latestnews.post_creator')}}</label>
                    <span class="asterisk">*</span>
                    <input type="text" name="post_creator" maxlength="250" class="form-control" data-ng-model="latestNewsCtrl.latestnews.post_creator" placeholder="{{trans('cms::latestnews.post_creator_placeholder')}}" value="{{old('post_creator')}}" />
                    <p class="help-block" data-ng-show="errors.post_creator.has">@{{ errors.post_creator.message }}</p>
                </div>
                <div class="form-group">
                    <label class="control-label">{{trans('cms::latestnews.status')}}</label>
                    <select class="form-control mb10" name="is_active" data-ng-model="latestNewsCtrl.latestnews.is_active">
                        <option value="1">{{trans('cms::latestnews.active')}}</option>
                        <option value="0">{{trans('cms::latestnews.inactive')}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <div flow-object="existingFlowObject" flow-init flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]" flow-files-submitted="$flow.upload()">
                        <div class="">
                            <p class="help-block" data-ng-show="errors.latestnews_image.has">@{{ errors.latestnews_image.message }}</p>
                            <hr class="soften" />
                            <div>
                                <div class="thumbnail" ng-hide="$flow.files.length">
                                    <img ng-if="latestNewsCtrl.latestnews.post_image" src="@{{latestNewsCtrl.latestnews.post_image}}" />
                                    <img ng-if="!latestNewsCtrl.latestnews.post_image" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                </div>
                                <div class="thumbnail" ng-show="$flow.files.length">
                                    <img ng-if="!latestNewsCtrl.latestnews.post_image" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
                                    <img ng-if="latestNewsCtrl.latestnews.post_image" flow-img="$flow.files[0]" />
                                </div>
                                <div>
                                    <a href="javascript:;" class="btn btn-primary upload_video" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        Select image
                                    </a>
                                    <a href="javascript:;" class="btn btn-default" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Change</a>
                                    <a href="javascript:;" class="btn btn-danger" ng-show="latestNewsCtrl.latestnews.post_image || $flow.files.length" ng-click="$flow.cancel();latestNewsCtrl.latestnews.post_image='';"> Remove </a>
                                </div>
                                <p class="intimation">Only PNG,GIF,JPG files allowed.</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="post_image" id="postImage" data-ng-model="latestNewsCtrl.latestnews.post_image" value="{{old('post_image')}}" />
                </div>
                <div class="panel-footer clearfix">
                    <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                    &nbsp;
                    <span class="btn btn-danger pull-right mr10" data-ng-click="latestNewsCtrl.closeLatestNewsEdit()">{{trans('base::general.cancel')}}</span>
                </div>

        </form>
    </div>
</nav>
