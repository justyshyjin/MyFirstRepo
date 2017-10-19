<div class="panel main_container">
	<div class="tab-content">
		<div class="tab-pane active" id="latest_video">
			<div class="tab_search clearfix">
				<div id="st-trigger-effects"
					class="search_upload_btn pull-right column">
					<button data-intialize-sidebar data-effect="st-effect-18"
						data-ng-click="colgridCtrl.addCollection($event)"
						class="btn btn-primary upload_video pull-right">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
						{{trans('video::collection.add_exam')}}
					</button>
				</div>
			</div>
			<div id="table_loader" class="table_loader_container"
				data-ng-show="gridLoadingBar">
				<div class="table_loader">
					<div class="loader"></div>
				</div>
			</div>
			<div class="table_responsive">
				<table class="table collection_table">
					<thead>
						<tr>
							<th class="center">{{trans('base::general.s_no')}}</th>
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
							<td class="search_product"><input type="text"
								class="form-control" data-ng-model="searchRecords.title"
								data-boot-tooltip="true"
								title="{{trans('video::collection.enter_collection_name')}}"></td>
							<td></td>
							<td></td>
							<td><select class="form-control mb15" data-ng-change="search()"
								data-ng-model="searchRecords.is_active" data-boot-tooltip="true"
								title="{{trans('base::general.select_status')}}">
									<option value="all">{{trans('base::general.all')}}</option>
									<option value='1'>{{trans('video::collection.banner.active')}}</option>
									<option value='0'>{{trans('video::collection.banner.inactive')}}</option>
							</select></td>
							<td></td>
							<td class="center">
								<button type="button" class="btn search"
									data-ng-click="search()" data-boot-tooltip="true"
									title="{{trans('base::general.search_filter')}}">
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
							data-ng-show="showRecords" class="list-repeat">
							<td class="center">@{{((currentPage - 1) * rowsPerPage) + $index
								+1}}</td>
							<td>@{{record.title}}</td>
							<td>@{{record.order}}</td>
							<td><span class="label label-success"
								ng-if="record.is_active == 1" style="cursor: pointer;"
								data-ng-click="colgridCtrl.updateStatus(record)"
								title="{{trans('video::collection.deactivate_collection')}}"
								data-boot-tooltip="true">{{trans('video::collection.message.active')}}</span>
								<span class="label label-danger" ng-if="record.is_active != 1"
								style="cursor: pointer;"
								data-ng-click="colgridCtrl.updateStatus(record)"
								title="{{trans('video::collection.activate_collection')}}"
								data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span>
							</td>
							<td>@{{ $root.getFormattedDate(record.created_at) }}</td>
							<td class="action center">
								<div id="st-trigger-effects" class="column edit_table_icon">
									<button data-boot-tooltip="true" data-intialize-sidebar=""
										data-effect="st-effect-18" title="View/Edit"
										data-ng-click="colgridCtrl.getCollectionEdit(record)"
										class="table_action">
										<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
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
				</table>
			</div>
			@include('base::layouts.pagination')
		</div>
	</div>
</div>
<!-- To Edit the collection  -->
<nav class="st-menu st-effect-18" id="menu-18">
	<div class="pop_over_continer">
		<form name="collectionForm" method="POST" data-base-validator
			data-ng-submit="colgridCtrl.collectionSave($event, colgridCtrl.collection.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="video_form add_form">
				<h5 data-ng-if="!colgridCtrl.collection.id">{{trans('video::collection.addexams')}}</h5>
				<h5 data-ng-if="colgridCtrl.collection.id">{{trans('video::collection.editexam')}}</h5>
				@include('base::partials.errors')
					<div class="form-group"
					data-ng-class="{'has-error': errors.title.has}">
					<label class="control-label"> {{trans('video::collection.exams_name')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="title" class="form-control"
						data-ng-model="colgridCtrl.collection.title" maxlength="255"  data-validation-name="Genre Title"
						placeholder="{{trans('video::collection.exams_name')}}"
						value="{{old('title')}}"
						 />
					<p class="help-block" data-ng-show="errors.title.has">@{{
						errors.title.message }}</p>
				</div>
				<div class="form-group"
					data-ng-class="{'has-error': errors.order.has}">
					<label class="control-label"> {{trans('video::collection.order')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="order" class="form-control"
						data-ng-model="colgridCtrl.collection.order" maxlength="3"
						placeholder="{{trans('video::collection.order')}}"
						value="{{old('order')}}"
						onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
					<p class="help-block" data-ng-show="errors.order.has">@{{
						errors.order.message }}</p>
				</div>
				<div class="form-group">
					<label class="control-label">{{ trans('video::videos.status') }} </label>
					<select class="form-control" name="is_active"
						data-ng-model="colgridCtrl.collection.is_active">
						<option value="1">{{ trans('video::videos.message.active') }}</option>
						<option value="0">{{ trans('video::videos.message.inactive') }}</option>
					</select>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
				&nbsp; <span class="btn btn-danger pull-right mr10"
					data-ng-click="colgridCtrl.closeCollectionEdit()">{{
					trans('base::general.cancel') }}</span>
			</div>
		</form>
	</div>
</nav>