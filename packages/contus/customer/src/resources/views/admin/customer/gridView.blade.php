<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="latest_video">
            <div class="tab_search clearfix">
                <div id="st-trigger-effects" class="search_upload_btn pull-right">
                    <button data-effect="st-effect-17" data-intialize-sidebar data-ng-click="usrCtrl.addUser($event)" class="btn btn-primary upload_video pull-right">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        {{trans('user::user.create_user')}}
                    </button>
                </div>
            </div>
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table" data-ng-init="usrCtrl.setQuery('{{auth()->user()->id}}')">
                <thead>
                    <tr>
                        <th class="center">{{trans('user::adminuser.serial_no')}}</th>
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
                            <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_user_name')}}">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.email" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_email')}}">
                        </td>
                        <td class="search_product userphone">
                          <input type="text" maxlength="13" class="form-control" data-ng-model="searchRecords.phone" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('base::general.enter_phone')}}">
                        </td>
                        <td></td>
                        <td>
                          <select class="form-control mb15" data-boot-tooltip="true" data-ng-model="searchRecords.subscriber" data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_plan')}}">
                              <option value="">{{trans('base::general.all')}}</option>
                              <option ng-repeat="subcription_plan in subcription_plans" value="@{{subcription_plan.id}}">@{{subcription_plan.name}}</option>
                          </select>
                        </td>
                        <td class="search_product planstart_date">
                            <input type="text" name="filter_startdate" id="filter_startdate" class="form-control" data-ng-model="searchRecords.filter_startdate" placeholder="DD-MM-YYYY" data-ng-change="search()" data-original-title="{{trans('base::general.select_startdate')}}"/>
                        </td>
                        <td>
                            <input type="text" name="filter_enddate" id="filter_enddate" class="form-control" data-ng-model="searchRecords.filter_enddate" placeholder="DD-MM-YYYY" data-ng-change="search()" data-original-title="{{trans('base::general.select_enddate')}}"/>
                        </td>
                        <td>
                            <select class="form-control mb15" data-boot-tooltip="true" data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_status')}}">
                                <option value="all">{{trans('base::general.all')}}</option>
                                <option value='1'>{{trans('user::banner.active')}}</option>
                                <option value='0'>{{trans('user::banner.inactive')}}</option>
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
                        <td>@{{record.email}}</td>
                        <td>@{{record.phone}}</td>
                        <td>@{{record.created_at}}</td>
                        <td>@{{record.active_subscriber[0].name || 'NA'}}</td>
                       <td>@{{record.active_subscriber[0].pivot.start_date || "NA"}}</td>
                       <td>@{{record.active_subscriber[0].pivot.end_date || "NA"}}</td>
                        <td>
                            <span class="label label-success" ng-if="record.is_active == 1" style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('user::user.deactivate_user')}}" data-boot-tooltip="true">{{trans('user::user.message.active')}}</span>
                            <span class="label label-danger" ng-if="record.is_active != 1 " style="cursor: pointer;" data-ng-click="updateStatus(record)" title="{{trans('user::user.activate_user')}}" data-boot-tooltip="true">{{trans('user::user.message.inactive')}}</span>
                        </td>
                        <td class="table-action">
                        <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Add Subscription"  data-ng-if="record.deleted_at == null">
                                <button data-intialize-sidebar="" data-effect="st-effect-17" class="table_action" data-ng-click="usrCtrl.addSubscription(record)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit"  data-ng-if="record.deleted_at == null">
                                <button data-intialize-sidebar="" data-effect="st-effect-17" class="table_action" data-ng-click="usrCtrl.editUser(record)">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <span ng-mouseover="getTooltip($event)" data-ng-if="record.deleted_at == null" title="{{trans('base::general.delete')}}" data-toggle="modal" data-target="#deleteModal" ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
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
<!-- To add or edit the user  -->
<nav class="st-menu st-effect-17" id="menu-17" ng-show="!usrCtrl.user.subsciptionform">
    <div class="pop_over_continer">
        <form name="userForm" method="POST" data-base-validator data-ng-submit="usrCtrl.save($event, usrCtrl.user.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5 data-ng-if="!usrCtrl.user.id">{{trans('customer::customer.customer')}} - {{trans('customer::customer.add_new_customer')}}</h5>
                <h5 data-ng-if="usrCtrl.user.id">{{trans('customer::customer.customer')}} - {{trans('customer::customer.edit_new_customer')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
                    <label class="control-label">
                        {{trans('customer::customer.name')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="name" data-ng-model="usrCtrl.user.name" class="form-control" placeholder="{{trans('customer::customer.customername_placeholder')}}" value="{{old('name')}}" />
                    <p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                    <label class="control-label">
                        {{trans('customer::customer.email')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="email" data-ng-model="usrCtrl.user.email" class="form-control" placeholder="{{trans('customer::customer.email_placeholder')}}" value="{{old('email')}}" />
                    <p class="help-block" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
                    <label class="control-label">
                        {{trans('customer::customer.phone')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="phone" maxlength="15" class="form-control" data-ng-model="usrCtrl.user.phone" placeholder="{{trans('customer::customer.phone_placeholder')}}" value="{{old('phone')}}" />
                    <p class="help-block" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
                </div>

                <div class="form-group" data-ng-class="{'has-error': errors.age.has}">
                    <label class="control-label">
                        {{trans('customer::customer.dob')}}
                        <span class="asterisk">*</span>
                    </label>
                     <input type="text" name="age" id="age" data-ng-model="usrCtrl.user.age" size="30"  placeholder="DD-MM-YYYY" data-validation-name = "DOB" value="{{old('age')}}" class="form-control" ng-blur="dateBlur($event,usrCtrl.user.age)" ng-keyup="dateKeyup($event,usrCtrl.user.age)"/>
                    <p class="help-block" data-ng-show="errors.age.has">@{{ errors.age.message }}</p>
                </div>
               <div class="form-group" ng-init="showhidepassword = (usrCtrl.user.email)?0:1"  ng-hide="showhidepassword">
                    <label class="control-label">
                        <input type="checkbox" ng-checked="showhidepassword" ng-click="showhidepassword = !showhidepassword">
                        Change Password
                    </label>
                </div>
                <div ng-if="showhidepassword" class="form-group" data-ng-class="{'has-error': errors.password.has}">
                    <label class="control-label">
                        {{trans('customer::customer.changepassword.newpassword')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="password" name="password" data-ng-model="usrCtrl.user.password" class="form-control" placeholder="{{trans('customer::customer.changepassword.placeholder_newpassword')}}" value="{{old('password')}}" />
                    <p class="help-block" data-ng-show="errors.password.has">@{{ errors.password.message }}</p>
                </div>
                <div ng-if="showhidepassword" class="form-group" data-ng-class="{'has-error': errors.password_confirmation.has}">
                    <label class="control-label">
                        {{trans('customer::customer.changepassword.confirmpassword')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="password" name="password_confirmation" data-ng-model="usrCtrl.user.password_confirmation" class="form-control" placeholder="{{trans('customer::customer.changepassword.placeholder_confirmpassword')}}" value="{{old('password_confirmation')}}" data-validation-name="Confirm Password" />
                    <p class="help-block" data-ng-show="errors.password_confirmation.has">@{{ errors.password_confirmation.message }}</p>
                </div>
                <div class="form-group">
                    <label class="control-label">{{trans('customer::customer.status')}}</label>
                    <select class="form-control mb10" name="is_active" data-ng-model="usrCtrl.user.is_active">
                        <option value="1">{{trans('customer::customer.active')}}</option>
                        <option value="0">{{trans('customer::customer.inactive')}}</option>
                    </select>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                &nbsp;
                <span class="btn btn-danger pull-right mr10" data-ng-click="usrCtrl.closeUserEdit()">{{trans('base::general.cancel')}}</span>
            </div>
        </form>
    </div>
</nav>
<nav class="st-menu st-effect-17" id="menu-17" ng-show="usrCtrl.user.subsciptionform">
    <div class="pop_over_continer">
        <form name="subscriptionForm" method="POST" data-base-validator data-ng-submit="usrCtrl.saveSubcription($event, usrCtrl.user.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5>{{trans('customer::customer.customer')}} - {{trans('customer::customer.add_new_subscription')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.orderid.has}">
                    <label class="control-label">
                        {{trans('customer::customer.orderid')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="orderid" data-ng-model="usrCtrl.user.orderid" data-validation-name="Transaction Id" class="form-control" placeholder="{{trans('customer::customer.orderid_placeholder')}}" value="{{old('orderid')}}" />
                    <p class="help-block" data-ng-show="errors.orderid.has">@{{ errors.orderid.message }}</p>
                </div>
                 <div class="form-group" data-ng-class="{'has-error': errors.subscription_plan.has}">
                    <label class="control-label">{{trans('customer::customer.plan')}}</label>
                    <span class="asterisk">*</span>
                    <select class="form-control mb10" name="subscription_plan" data-ng-model="usrCtrl.user.subscription_plan" data-validation-name="Subscription Plan">
                       <option value="">Please Select Plan</option>
                       <option ng-repeat="subcription_plan in subcription_plans" value="@{{subcription_plan.id}}">@{{subcription_plan.name}}</option>
                    </select>
                     <p class="help-block" data-ng-show="errors.subscription_plan.has">@{{ errors.subscription_plan.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.start_date.has}">
                    <label class="control-label">
                        {{trans('customer::customer.start_date')}}
                        <span class="asterisk">*</span>
                    </label>
                     <input type="text" name="start_date" id="start_date" data-ng-model="usrCtrl.user.start_date" size="30"  placeholder="DD-MM-YYYY" data-validation-name = "Start Date" value="{{old('start_date')}}" class="form-control" ng-blur="dateBlur($event,usrCtrl.user.start_date)" ng-keyup="dateKeyup($event,usrCtrl.user.start_date)"/>
                    <p class="help-block" data-ng-show="errors.start_date.has">@{{ errors.start_date.message }}</p>
                </div>
            </div>
            <input type="hidden" name="userid" data-ng-model="usrCtrl.user.id"/>

            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
                &nbsp;
                <span class="btn btn-danger pull-right mr10" data-ng-click="usrCtrl.closeUserEdit()">{{trans('base::general.cancel')}}</span>
            </div>
        </form>
    </div>
</nav>
