<div class="panel main_container">
	<div class="tab-content">
		<div class="tab-pane active" id="banner">
			<div class="tab_search clearfix">
				<div id="st-trigger-effects" class="search_upload_btn pull-right"></div>
			</div>

			<div id="table_loader" class="table_loader_container"
				data-ng-show="gridLoadingBar">
				<div class="table_loader">
					<div class="loader"></div>
				</div>
			</div>
			<table class="table"
				data-ng-init="contactusCtrl.setQuery('{{auth()->user()->id}}')">
				<thead>
					<tr>
						<th class="center">{{trans('cms::staticcontent.serial_no')}}</th>
						<th data-ng-repeat="field in heading">@{{field.name}} <span
							data-ng-if="field.sort==true" id=""
							class="th-inner sortable both"
							data-ng-class="{showGridArrow:field.sort}"
							data-ng-click="fieldOrder($event,field.value)"></span> <span
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
							data-original-title="{{trans('cms::staticcontent.type_customer_name')}}"></td>
						<td></td>
						<td class="search_product"><input type="text" class="form-control"
							data-ng-model="searchRecords.email" data-boot-tooltip="true"
							data-toggle="tooltip"
							data-original-title="{{trans('cms::staticcontent.type_customer_email')}}"></td>
						<td></td>
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
						<td>@{{record.phone}}</td>
						<td>@{{record.email}}</td>
						<td>@{{record.message}}</td>
						<td class="cs-testimonial-img">@{{record.created_at}}</td>
						<td class="action" style="min-width:105px">
                                <div class="column edit_table_icon">                                                                     
                                    <a data-boot-tooltip="true" title="View Contacts" class="table_action" href="{{url('admin/contactus/details-contact-view')}}/@{{record.id}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
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
