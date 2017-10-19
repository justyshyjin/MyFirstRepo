<div class="panel main_container">
	<div class="tab-content">
		<div class="tab-pane active" id="subscriptions_plans">
			<div class="tab_search clearfix">
				<div id="st-trigger-effects" class="search_upload_btn pull-right">
					<button data-effect="st-effect-17" data-intialize-sidebar
						data-ng-click="subscriptionCtrl.addSubscriptionsPlans($event)"
						class="btn btn-primary upload_video pull-right">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>{{trans('customer::subscription.create_subscription')}}
					</button>
				</div>
			</div>

			<div id="table_loader" class="table_loader_container"
				data-ng-show="gridLoadingBar">
				<div class="table_loader">
					<div class="loader"></div>
				</div>
			</div><div class="table-responsive">
			<table class="table"
				data-ng-init="subscriptionCtrl.setQuery('{{auth()->user()->id}}')">
				<thead>
					<tr>
						<th class="center">{{trans('customer::subscription.serial_no')}}</th>
						<th data-ng-repeat="field in heading">@{{field.name}} <span
							data-ng-if="field.sort==true" id=""
							class="th-inner sortable both"
							data-ng-class="{showGridArrow:field.sort}"
							data-ng-click="fieldOrder($event,'id')"></span> <span
							data-ng-if="field.sort==false"
							data-ng-class="{showGridArrow:field.sort}"></span>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="search_text">
						<td></td>
						<td class="search_product"><input type="text" class="form-control"
							data-ng-model="searchRecords.name" data-boot-tooltip="true"
							data-toggle="tooltip"
							data-original-title="{{trans('customer::subscription.enter_name')}}"></td>
						<td class="search_product"><input type="text" class="form-control"
							data-ng-model="searchRecords.type" data-boot-tooltip="true"
							data-toggle="tooltip"
							data-original-title="{{trans('customer::subscription.enter_type')}}"></td>
						<td>

						<td class="search_product"><input type="text" class="form-control"
							data-ng-model="searchRecords.amount" data-boot-tooltip="true"
							data-toggle="tooltip"
							data-original-title="{{trans('customer::subscription.enter_amount')}}"></td>
						<td>

						<td><select class="form-control mb15" data-boot-tooltip="true"
							data-ng-model="searchRecords.is_active" data-ng-change="search()"
							data-toggle="tooltip"
							data-original-title="{{trans('base::general.select_status')}}">
								<option value="all">{{trans('base::general.all')}}</option>
								<option value='1'>{{trans('customer::subscription.active')}}</option>
								<option value='0'>{{trans('customer::subscription.inactive')}}</option>
						</select></td>
						<td></td>
						<td class="">
							<button type="button" class="btn search" data-ng-click="search()"
								data-boot-tooltip="true" data-toggle="tooltip"
								data-original-title="{{trans('base::general.search_filter')}}">
								<i class="fa fa-search"></i>
							</button>
							<button type="button" class="btn search"
								data-ng-click="gridReset()" data-boot-tooltip="true"
								title="{{trans('base::general.reset')}}">
								<i class="fa fa-refresh"></i>
							</button>
						</td>
					</tr>

					<tr>
						<td data-ng-if="noRecords" colspan="@{{heading.length + 1}}"
							class="no-data">{{trans('base::general.not_found')}}</td>
					</tr>
					<tr data-ng-if="showRecords"
						data-ng-repeat="record in records track by $index"
						data-ng-show="showRecords" class="list-repeat"
						data-intialize-sidebar="">
						<td class="center">@{{((currentPage - 1) * rowsPerPage) + $index
							+1}}</td>
						<td>@{{record.name}}</td>
						<td>@{{record.type}}</td>
						<td>@{{record.description}}</td>
						<td>@{{record.amount}}</td>
						<td>@{{record.duration}}</td>
						<td>@{{record.created_at}}</td>

						<td><span class="label label-success"
							ng-if="record.is_active == 1" style="cursor: pointer;"
							data-ng-click="updateStatus(record)"
							title="{{trans('customer::subscription.deactivate_subscription')}}"
							data-boot-tooltip="true">{{trans('customer::subscription.message.active')}}</span>
							<span class="label label-danger" ng-if="record.is_active != 1"
							style="cursor: pointer;" data-ng-click="updateStatus(record)"
							title="{{trans('customer::subscription.activate_subscription')}}"
							data-boot-tooltip="true">{{trans('customer::subscription.message.inactive')}}</span>
						</td>
						<td class="table-action">
							<div id="st-trigger-effects" class="column edit_table_icon">
								<button data-effect="st-effect-17" class="table_action"
									data-ng-click="subscriptionCtrl.editSubscriptionsPlans(record)">
									<i class="fa fa-ellipsis-h"></i>
								</button>
							</div> <span ng-mouseover="getTooltip($event)"
							title="{{trans('base::general.delete')}}" data-toggle="modal"
							data-target="#deleteModal"
							ng-click="deleteSingleRecord(record.id)"
							class="tooltips delete_table_icon" data-boot-tooltip="true"
							data-original-title=""> <i class="fa fa-trash-o"></i>
						</span>
						</td>
					</tr>
				</tbody>
			</table></div>
			@include('base::layouts.pagination')
		</div>
	</div>
</div>

<!-- To add or edit the lastest news  -->
<nav class="st-menu st-effect-17" id="menu-17">
	<div class="pop_over_continer">
		<form name="subscriptionForm" method="POST" data-base-validator
			data-ng-submit="subscriptionCtrl.save($event,subscriptionCtrl.subscriptions_plans.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="video_form add_form">

				<h5 data-ng-if="!subscriptionCtrl.subscriptions_plans.id">{{trans('customer::subscription.content_heading')}}
					- {{trans('customer::subscription.add_new_subscription')}}</h5>
				<h5 data-ng-if="subscriptionCtrl.subscriptions_plans.id">{{trans('customer::subscription.content_heading')}}
					- {{trans('customer::subscription.edit_new_subscription')}}</h5>
				@include('base::partials.errors')

				<div class="form-group"
					data-ng-class="{'has-error': errors.name.has}">
					<label class="control-label">{{trans('customer::subscription.subscription_name')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="name"
						data-unique="@{{subscriptionCtrl.uniqueRoute}}"
						data-ng-model="subscriptionCtrl.subscriptions_plans.name"
						class="form-control"
						placeholder="{{trans('customer::subscription.subscription_placeholder')}}"
						value="{{old('title')}}" />
					<p class="help-block" data-ng-show="errors.name.has">@{{
						errors.name.message }}</p>
				</div>

				<div class="form-group"
					data-ng-class="{'has-error': errors.type.has}">
					<label class="control-label">{{trans('customer::subscription.type')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="type"
						data-ng-model="subscriptionCtrl.subscriptions_plans.type"
						class="form-control"
						placeholder="{{trans('customer::subscription.type_placeholder')}}"
						value="{{old('type')}}" />
					<p class="help-block" data-ng-show="errors.type.has">@{{
						errors.type.message }}</p>
				</div>


				<div class="form-group"
					data-ng-class="{'has-error': errors.description.has}">
					<label class="control-label">{{trans('customer::subscription.description')}}
						<span class="asterisk">*</span>
					</label>
					<textarea type="text" name="phone" class="form-control"
						data-ng-model="subscriptionCtrl.subscriptions_plans.description"
						placeholder="{{trans('customer::subscription.description_placeholder')}}"
						value="{{old('description')}}"></textarea>
					<p class="help-block" data-ng-show="errors.description.has">@{{
						errors.description.message }}</p>
				</div>

				<div class="form-group"
					data-ng-class="{'has-error': errors.amount.has}">
					<label class="control-label">{{trans('customer::subscription.amount')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="amount"
						data-ng-model="subscriptionCtrl.subscriptions_plans.amount"
						class="form-control"
						placeholder="{{trans('customer::subscription.amount_placeholder')}}"
						value="{{old('amount')}}" />
					<p class="help-block" data-ng-show="errors.amount.has">@{{
						errors.amount.message }}</p>
				</div>


				<div class="form-group"
					data-ng-class="{'has-error': errors.duration.has}">
					<label class="control-label">{{trans('customer::subscription.duration')}}
						<span class="asterisk">*</span>
					</label>
					<input type="text" name="amount"
						data-ng-model="subscriptionCtrl.subscriptions_plans.duration"
						class="form-control"
						placeholder="{{trans('customer::subscription.duration_placeholder')}}"
						value="{{old('amount')}}" />
					<p class="help-block" data-ng-show="errors.duration.has">@{{
						errors.duration.message }}</p>
				</div>


				<div class="form-group">
					<label class="control-label">{{trans('customer::subscription.status')}}</label>
					<select class="form-control mb10" name="is_active"
						data-ng-model="subscriptionCtrl.subscriptions_plans.is_active">
						<option value="1">{{trans('customer::subscription.active')}}</option>
						<option value="0">{{trans('customer::subscription.inactive')}}</option>
					</select>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
				&nbsp; <span class="btn btn-danger pull-right mr10"
					data-ng-click="subscriptionCtrl.closeSubscriptionEdit()">{{trans('base::general.cancel')}}</span>
			</div>
		</form>
	</div>
</nav>

