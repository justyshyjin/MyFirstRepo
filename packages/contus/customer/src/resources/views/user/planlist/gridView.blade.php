 @section('profilecontent')<div class="col-md-9">
    <div class="row">
        <div class="subscription-contanier">
            <div class="row">
                <div class="col-md-9">
                    <h5>Upgrade to @{{subscription.name}}</h5>
                    <p>
                        <span class="text-blue">@{{subscription.amount}}</span>
                        @{{subscription.description}}
                    </p>
                </div>
                <div class="col-md-3">
                    <a title="Subscribe now" class="btn full-btn btn-subscription"
                        ui-sref="subscribeinfo">Subscribe now</a>
                </div>
            </div>
        </div>
        
        <h3>My Plan</h3>
        <div class="panel panel-default">
        
            <div class="tab-content">
                <div class="tab-pane active" id="subscriptions_plans">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="center">{{trans('customer::subscription.serial_no')}}</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="search_text">
                                    <td></td>
                                    <td class="search_product"><input
                                        type="text" class="form-control"
                                        data-ng-model="searchRecords.name"
                                        data-boot-tooltip="true"
                                        data-toggle="tooltip"
                                        data-original-title="{{trans('customer::subscription.enter_name')}}"></td>
                                    <td></td>
                                    <td></td>
                                    <td><select
                                        class="form-control mb15"
                                        data-boot-tooltip="true"
                                        data-ng-model="searchRecords.is_active"
                                        data-ng-change="search()"
                                        data-toggle="tooltip"
                                        data-original-title="{{trans('base::general.select_status')}}">
                                            <option value="all">{{trans('base::general.all')}}</option>
                                            <option value='1'>{{trans('customer::subscription.active')}}</option>
                                            <option value='0'>{{trans('customer::subscription.inactive')}}</option>
                                    </select></td>
                                    <td></td>
                                    <td class="">
                                        <button type="button"
                                            class="btn search"
                                            data-ng-click="search()"
                                            data-boot-tooltip="true"
                                            data-toggle="tooltip"
                                            data-original-title="{{trans('base::general.search_filter')}}">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button"
                                            class="btn search"
                                            data-ng-click="gridReset()"
                                            data-boot-tooltip="true"
                                            title="{{trans('base::general.reset')}}">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-ng-if="noRecords"
                                        colspan="@{{heading.length + 1}}"
                                        class="no-data text-center">{{trans('base::general.not_found')}}</td>
                                </tr>
                                <tr data-ng-if="showRecords"
                                    data-ng-repeat="record in records track by $index"
                                    data-ng-show="showRecords"
                                    class="list-repeat"
                                    data-intialize-sidebar="">
                                    <td class="center">@{{((currentPage
                                        - 1) * rowsPerPage) + $index
                                        +1}}</td>
                                    <td>@{{record.subscriptionplan.name}}</td>
                                    <td>@{{record.start_date}}</td>
                                    <td>@{{record.end_date}}</td>
                                    <td class=""><span
                                        class="label label-success"
                                        ng-if="record.is_active == 1"
                                        style="cursor: pointer;"
                                        title="{{trans('user::user.message.active')}}"
                                        data-boot-tooltip="true">{{trans('user::user.message.active')}}</span>
                                        <span class="label label-danger"
                                        ng-if="record.is_active != 1 "
                                        style="cursor: pointer;"
                                        title="{{trans('user::user.message.inactive')}}"
                                        data-boot-tooltip="true">{{trans('user::user.message.inactive')}}</span>
                                    </td>
                                    <td>@{{record.created_at}}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="cusomt-pagination">
                        @include('base::layouts.pagination')</div>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@include('customer::user.account.index')