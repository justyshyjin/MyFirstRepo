<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="static_content">
            <div class="tab_search clearfix">
                <div id="st-trigger-effects" class="search_upload_btn pull-right"></div>
            </div>
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <table class="table" data-ng-init="staticCtrl.setQuery('{{auth()->user()->id}}')">
                <thead>
                    <tr>
                        <th class="center">{{trans('cms::staticcontent.serial_no')}}</th>
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
                            <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::staticcontent.enter_title')}}">
                        </td>
                        <td></td>
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
                        <td>@{{record.updated_at}}</td>
                        <td class="table-action">
                            <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit">
                                <a data-boot-tooltip="true" title="Edit" class="table_action" href="{{url('admin/staticContent/edit-static-content')}}/@{{record.id}}" data-original-title="Edit Video">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            @include('base::layouts.pagination')
        </div>
    </div>
</div>
<!-- To add or edit the lastest news  -->
<nav class="st-menu st-effect-17" id="menu-17">
    <div class="pop_over_continer">
        <form name="staticContentForm" method="POST" data-base-validator data-ng-submit="staticCtrl.save($event,staticCtrl.static_content.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5 data-ng-if="!staticCtrl.static_content.id">{{trans('cms::staticcontent.content_heading')}} - {{trans('cms::staticcontent.add_new_content')}}</h5>
                <h5 data-ng-if="staticCtrl.static_content.id">{{trans('cms::staticcontent.content_heading')}} - {{trans('cms::staticcontent.edit_new_content')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                    <label class="control-label">
                        {{trans('cms::staticcontent.title')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="name" data-ng-model="staticCtrl.static_content.title" class="form-control" placeholder="{{trans('cms::staticcontent.title_placeholder')}}" value="{{old('title')}}" />
                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.content.has}">
                    <label class="control-label">
                        {{trans('cms::staticcontent.content')}}
                        <span class="asterisk">*</span>
                    </label>
                    <textarea type="text" name="phone" class="form-control" data-ng-model="staticCtrl.static_content.content" placeholder="{{trans('cms::staticcontent.content_placeholder')}}" value="{{old('content')}}" rows="5" cols="50"></textarea>
                    <p class="help-block" data-ng-show="errors.content.has">@{{ errors.content.message }}</p>
                </div>
                <div class="form-group">
                    <label class="control-label">{{trans('cms::staticcontent.status')}}</label>
                    <select class="form-control mb10" name="is_active" data-ng-model="staticCtrl.static_content.is_active">
                        <option value="1">{{trans('cms::staticcontent.active')}}</option>
                        <option value="0">{{trans('cms::staticcontent.inactive')}}</option>
                    </select>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                &nbsp;
                <span class="btn btn-danger pull-right mr10" data-ng-click="staticCtrl.closeStaticContentEdit()">{{trans('base::general.cancel')}}</span>
            </div>
        </form>
    </div>
</nav>
