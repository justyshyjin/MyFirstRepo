<div class="panel main_container">
	<div class="tab-content">
		<div class="tab-pane active" id="email">
			<div class="tab_search clearfix">
				<div id="st-trigger-effects" class="search_upload_btn pull-right">
				</div>
			</div>

			<div id="table_loader" class="table_loader_container"
				data-ng-show="gridLoadingBar">
				<div class="table_loader">
					<div class="loader"></div>
				</div>
			</div>
			<table class="table"
				data-ng-init="emailCtrl.setQuery('{{auth()->user()->id}}')">
				<thead>
					<tr>
						<th class="center">{{trans('cms::emailtemplate.serial_no')}}</th>
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
							data-original-title="{{trans('cms::emailtemplate.enter_name')}}"></td>

						<td class="search_product"><input type="text" class="form-control"
							data-ng-model="searchRecords.subject" data-boot-tooltip="true"
							data-toggle="tooltip"
							data-original-title="{{trans('cms::emailtemplate.enter_subject')}}"></td>
						<td>
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
						<td class="center"  >@{{((currentPage - 1) * rowsPerPage) + $index+1}}</td>
						<td>@{{record.name}}</td>
						<td>@{{record.subject}}</td>
						<td class="cs-testimonial-img">@{{record.created_at}}</td>
						 <td class="action">
                                <div class="column edit_table_icon">                                                                     
                                    <a data-boot-tooltip="true" title="{{trans('cms::emailtemplate.edit_email')}}" class="table_action" href="{{url('admin/emails/details-email-edit')}}/@{{record.id}}">
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
		<form name="emailtemplateForm" method="POST" data-base-validator
			data-ng-submit="emailCtrl.save($event,emailCtrl.email.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="video_form add_form">

				<h5 data-ng-if="!emailCtrl.email.id">{{trans('cms::emailtemplate.email_heading')}}
					- {{trans('cms::emailtemplate.add_new_email')}}</h5>
				<h5 data-ng-if="emailCtrl.email.id">{{trans('cms::emailtemplate.email_heading')}}
					- {{trans('cms::emailtemplate.edit_new_email')}}</h5>
				@include('base::partials.errors')

				<div class="form-group"
					data-ng-class="{'has-error': errors.name.has}">
					<label class="control-label">{{trans('cms::emailtemplate.name')}} <span
						class="asterisk">*</span></label> <input type="text" name="name"
						data-ng-model="emailCtrl.email.name" class="form-control"
						placeholder="{{trans('cms::emailtemplate.name_placeholder')}}"
						value="{{old('name')}}" />
					<p class="help-block" data-ng-show="errors.name.has">@{{
						errors.name.message }}</p>
				</div>

				<div class="form-group"
					data-ng-class="{'has-error': errors.subject.has}">
					<label class="control-label">{{trans('cms::emailtemplate.subject')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="name"
						data-ng-model="emailCtrl.email.subject" class="form-control"
						placeholder="{{trans('cms::emailtemplate.subject_placeholder')}}"
						value="{{old('subject')}}" />
					<p class="help-block" data-ng-show="errors.subject.has">@{{
						errors.subject.message }}</p>
				</div>

				<div class="form-group"
					data-ng-class="{'has-error': errors.content.has}">
					<label class="control-label">{{trans('cms::emailtemplate.content')}}
						<span class="asterisk">*</span>
					</label>
					<textarea type="text" name="content" class="form-control"
						data-ng-model="emailCtrl.email.content"
						placeholder="{{trans('cms::emailtemplate.content_placeholder')}}"
						value="{{old('content')}}" rows="5" cols="50"></textarea>
					<p class="help-block" data-ng-show="errors.content.has">@{{
						errors.content.message }}</p>
				</div>

				<div class="form-group">
					<label class="control-label">{{trans('cms::emailtemplate.status')}}</label>
					<select class="form-control mb10" name="is_active"
						data-ng-model="emailCtrl.email.is_active">
						<option value="1">{{trans('cms::emailtemplate.active')}}</option>
						<option value="0">{{trans('cms::emailtemplate.inactive')}}</option>
					</select>
				</div>

			</div>
			<div class="panel-footer clearfix">
				<button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
				&nbsp; <span class="btn btn-danger pull-right mr10"
					data-ng-click="emailCtrl.closeEmailEdit()">{{trans('base::general.cancel')}}</span>
			</div>
		</form>
	</div>
</nav>

