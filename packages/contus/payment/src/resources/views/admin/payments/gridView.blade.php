<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="payment">
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div><div class="table-responsive">
            <table class="table table-hover" data-ng-init="payCtrl.setQuery('{{auth()->user()->id}}')">
                <thead>
                    <tr>
                        <th class="center">{{trans('payment::payment.serial_no')}}</th>
                        <th data-ng-repeat="field in heading">
                            @{{field.name}}
                            <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,'id')"></span>
                            <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="search_text">
                        <td></td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('payment::payment.enter_name')}}">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.type" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('payment::payment.enter_type')}}">
                        </td>
                        <td>


                        <td></td>
                        <td>
                            <select class="form-control mb15" data-boot-tooltip="true" data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_status')}}">
                                <option value="all">{{trans('base::general.all')}}</option>
                                <option value='1'>{{trans('payment::payment.active')}}</option>
                                <option value='0'>{{trans('payment::payment.inactive')}}</option>
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
                        <td>@{{record.name}}</td>
                        <td>@{{record.type}}</td>
                        <td>@{{record.description}}</td>
                        <td>
                            <span class="label label-success" ng-if="record.is_test == 1" style="cursor: pointer;" data-ng-click="updateMode(record)" title="{{trans('payment::payment.deactivate_mode')}}" data-boot-tooltip="true">{{trans('payment::payment.mode.test')}}</span>
                            <span class="label label-info" ng-if="record.is_test != 1" style="cursor: pointer;" data-ng-click="updateMode(record)" title="{{trans('payment::payment.activate_mode')}}" data-boot-tooltip="true">{{trans('payment::payment.mode.live')}}</span>
                        </td>
                        <td>
                            <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('payment::payment.deactivate_payment')}}" data-boot-tooltip="true">{{trans('payment::payment.message.active')}}</span>
                            <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('payment::payment.activate_payment')}}" data-boot-tooltip="true">{{trans('payment::payment.message.inactive')}}</span>
                        </td>
                        <td class="table-action">
                            <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit">
                                <button data-effect="st-effect-17" class="table_action" data-ng-click="payCtrl.editPayment(record)">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody></div>
            </table>
            @include('base::layouts.pagination')
        </div>
    </div>
</div>
<!-- To add or edit the lastest news  -->
<nav class="st-menu st-effect-17" id="menu-17">
    <div class="pop_over_continer">
        <form name="paymentForm" method="POST" data-base-validator data-ng-submit="payCtrl.save($event,payCtrl.payment.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5 data-ng-if="!payCtrl.payment.id">{{trans('payment::payment.content_heading')}} - {{trans('payment::payment.add_payment')}}</h5>
                <h5 data-ng-if="payCtrl.payment.id">{{trans('payment::payment.content_heading')}} - {{trans('payment::payment.edit_payment')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label class="control-label">
                        {{trans('payment::payment.name')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="name" data-ng-model="payCtrl.payment.name" class="form-control" placeholder="{{trans('payment::payment.enter_name')}}" value="{{old('name')}}" />
                    <p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.type.has}">
                    <label class="control-label">
                        {{trans('payment::payment.type')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="type" data-ng-model="payCtrl.payment.type" class="form-control" placeholder="{{trans('payment::payment.enter_type')}}" value="{{old('slug')}}" />
                    <p class="help-block" data-ng-show="errors.type.has">@{{ errors.type.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.description.has}">
                    <label class="control-label">
                        {{trans('payment::payment.description')}}
                        <span class="asterisk">*</span>
                    </label>
                    <textarea type="text" name="description" class="form-control" data-ng-model="payCtrl.payment.description" placeholder="{{trans('payment::payment.enter_description')}}" value="{{old('content')}}"  rows="5" cols="50"></textarea>
                    <p class="help-block" data-ng-show="errors.description.has">@{{ errors.description.message }}</p>
                </div>
                <div class="form-group">
                    <label class="control-label">{{trans('payment::payment.is_test')}}</label>
                    <select class="form-control mb10" name="is_test" data-ng-model="payCtrl.payment.is_test">
                        <option value="1">{{trans('payment::payment.test')}}</option>
                        <option value="0">{{trans('payment::payment.live')}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">{{trans('payment::payment.status')}}</label>
                    <select class="form-control mb10" name="is_active" data-ng-model="payCtrl.payment.is_active">
                        <option value="1">{{trans('payment::payment.active')}}</option>
                        <option value="0">{{trans('payment::payment.inactive')}}</option>
                    </select>
                </div>
                <div class="form-group" ng-repeat=" setting in payment.child track by $index" data-ng-class="{'has-error': errors[setting.key].has}">
                    <label style="text-transform:capitalize;" class="control-label" ng-if="setting.is_test == payCtrl.payment.is_test">@{{getspacekey(setting.key)}}</label>
                    <input ng-if="setting.is_test == payCtrl.payment.is_test" type="text" data-ng-model="payCtrl.payment.setting[setting.key]" name="@{{setting.key}}" class="form-control" />
                    <p ng-if="setting.is_test == payCtrl.payment.is_test" class="help-block" data-ng-show="errors[setting.key].has">@{{ errors[setting.key].message }}</p>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                &nbsp;
			<a class="btn btn-danger pull-right mr10" href="{{url('admin/payments')}}">{{trans('base::general.cancel')}}</a>
            </div>
        </form>
    </div>
</nav>
