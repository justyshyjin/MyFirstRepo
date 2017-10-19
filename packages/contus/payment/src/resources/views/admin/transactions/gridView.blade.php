<div class="panel main_container">
	<div class="tab-content">

		<div id="table_loader" class="table_loader_container"
			data-ng-show="gridLoadingBar">
			<div class="table_loader">
				<div class="loader"></div>
			</div>
		</div>
		<table class="table"
			data-ng-init="transCtrl.setQuery('{{auth()->user()->id}}')">
			<thead>
				<tr>
					<th class="center">{{trans('payment::transaction.serial_no')}}</th>
					<th data-ng-repeat="field in heading">@{{field.name}} <span
						data-ng-if="field.sort==true" id="" class="th-inner sortable both"
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
						data-ng-model="searchRecords.transaction_id" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::transaction.enter_transaction_id')}}"></td>
					<td class="search_product"><input type="text" class="form-control"
						data-ng-model="searchRecords.slug" data-boot-tooltip="true"
						data-toggle="tooltip"
						data-original-title="{{trans('payment::transaction.enter_customer')}}"></td>
					<td>

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
						
					<td>@{{record.transaction_id}}</td>
					<td>@{{record.get_transaction_user.name}}</td>
					<td>@{{record.status}}</td>
					<td>@{{record.created_at}}</td>
					<td class="table-action">
						<div id="st-trigger-effects" class="tooltips edit_table_icon">
							<a data-boot-tooltip="true"
								title="{{trans('payment::transaction.view_transactions')}}"
								class="table_action"
								href="{{url('admin/transactions/transaction-details')}}/@{{record.id}}"><i
								class="fa fa-eye" aria-hidden="true"></i></a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		@include('base::layouts.pagination')
	</div>
</div>
</div>