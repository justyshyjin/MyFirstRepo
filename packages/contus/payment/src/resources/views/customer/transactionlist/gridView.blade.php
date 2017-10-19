<div class="col-md-9 ">
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
         <h3>My Transactions</h3>
        <div class="panel panel-default">
            <div class="tab-content">
                <div class="tab-pane active" id="subscriptions_plans">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="center">{{trans('customer::subscription.serial_no')}}</th>
                                    <th
                                        data-ng-repeat="field in heading">@{{field.name}}
                                        <span
                                        data-ng-if="field.sort==true"
                                        id=""
                                        class="th-inner sortable both"
                                        data-ng-class="{showGridArrow:field.sort}"
                                        data-ng-click="fieldOrder($event,field.value)"></span>
                                        <span
                                        data-ng-if="field.sort==false"
                                        data-ng-class="{showGridArrow:field.sort}"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="search_text">
                                    <td></td>
                                    <td class="search_product"><input
                                        type="text" class="form-control"
                                        data-ng-model="searchRecords.transaction_id"
                                        data-boot-tooltip="true"
                                        data-toggle="tooltip"
                                        data-original-title="{{trans('customer::subscription.transaction_id')}}"></td>
                                    <td class="search_product"><input
                                        type="text" class="form-control"
                                        data-ng-model="searchRecords.status"
                                        data-boot-tooltip="true"
                                        data-toggle="tooltip"
                                        data-original-title="{{trans('customer::subscription.transaction_id')}}"></td>
                                    <td class="search_product"><input
                                        type="text" class="form-control"
                                        data-ng-model="searchRecords.transaction_message"
                                        data-boot-tooltip="true"
                                        data-toggle="tooltip"
                                        data-original-title="{{trans('customer::subscription.transaction_id')}}"></td>
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
                                        class="no-data">{{trans('base::general.not_found')}}</td>
                                </tr>
                                <tr data-ng-if="showRecords"
                                    data-ng-repeat="record in records track by $index"
                                    data-ng-show="showRecords"
                                    class="list-repeat"
                                    data-intialize-sidebar="">
                                    <td class="center">@{{((currentPage
                                        - 1) * rowsPerPage) + $index
                                        +1}}</td>
                                    <td>@{{record.transaction_id}}</td>
                                    <td>@{{record.status}}</td>
                                    <td>@{{record.transaction_message}}</td>
                                    <td>@{{record.created_at}}</td>
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
</div>
