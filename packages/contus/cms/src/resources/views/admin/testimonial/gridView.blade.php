<div class="panel main_container">
	<div class="tab-content">
		<div class="tab-pane active" id="testimonial">
			<div class="tab_search clearfix">
				<div id="st-trigger-effects" class="search_upload_btn pull-right">
					<button data-effect="st-effect-17" data-intialize-sidebar
						data-ng-click="testCtrl.addStaticContent($event)"
						class="btn btn-primary upload_video pull-right">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>{{trans('cms::staticcontent.create_testimonial')}}
					</button>
				</div>
			</div>

			<div id="table_loader" class="table_loader_container"
				data-ng-show="gridLoadingBar">
				<div class="table_loader">
					<div class="loader"></div>
				</div>
			</div>
			<table class="table"
				data-ng-init="testCtrl.setQuery('{{auth()->user()->id}}')">
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
							data-original-title="{{trans('cms::staticcontent.enter_title')}}"></td>
						<td></td>
						<td></td>
						<td><select class="form-control mb15" data-boot-tooltip="true"
							data-ng-model="searchRecords.is_active" data-ng-change="search()"
							data-toggle="tooltip"
							data-original-title="{{trans('base::general.select_status')}}">
								<option value="all">{{trans('base::general.all')}}</option>
								<option value='1'>{{trans('cms::staticcontent.active')}}</option>
								<option value='0'>{{trans('cms::staticcontent.inactive')}}</option>
						</select></td>
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
						<td>
                        <img alt="" class="cs-testimonial-img" style="" ng-if="record.image" ng-src="@{{record.image}}" >
                        <img alt="" class="cs-testimonial-img" style="" ng-if="(record.image)?false:true"  src="{{url('contus/base/images/admin/no_image_available.jpg')}}">
                        </td>
						<td>@{{record.created_at}}</td>
						<td><span class="label label-success"
							ng-if="record.is_active == 1" style="cursor: pointer;"
							data-ng-click="updateStatus(record)"
							title="{{trans('cms::testimonial.deactivate_content')}}"
							data-boot-tooltip="true">{{trans('cms::testimonial.active')}}</span>
							<span class="label label-danger" ng-if="record.is_active != 1"
							style="cursor: pointer;" data-ng-click="updateStatus(record)"
							title="{{trans('cms::testimonial.activate_content')}}"
							data-boot-tooltip="true">{{trans('cms::testimonial.inactive')}}</span>
						</td>
						<td class="table-action">
							<div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit">
								<button data-effect="st-effect-17" class="table_action" title="Edit"
									data-ng-click="testCtrl.editStaticContent(record)">
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
			</table>

			@include('base::layouts.pagination')
		</div>
	</div>
</div>

<!-- To add or edit the lastest news  -->
<nav class="st-menu st-effect-17" id="menu-17">
	<div class="pop_over_continer">
		<form name="testimonialForm" method="POST" data-base-validator
			data-ng-submit="testCtrl.save($event,testCtrl.testimonial.id)"
			enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="video_form add_form">

				<h5 data-ng-if="!testCtrl.testimonial.id">{{trans('cms::testimonial.content_headings')}}
					- {{trans('cms::testimonial.add_new_contents')}}</h5>
				<h5 data-ng-if="testCtrl.testimonial.id">{{trans('cms::testimonial.content_headings')}}
					- {{trans('cms::testimonial.edit_new_contents')}}</h5>
				@include('base::partials.errors')

				<div class="form-group"
					data-ng-class="{'has-error': errors.name.has}">
					<label class="control-label">{{trans('cms::testimonial.title')}}
						<span class="asterisk">*</span>
					</label> <input type="text" name="name"
						data-ng-model="testCtrl.testimonial.name"
						class="form-control"
						placeholder="{{trans('cms::testimonial.title_placeholder')}}"
						value="{{old('title')}}" />
					<p class="help-block" data-ng-show="errors.name.has">@{{
						errors.name.message }}</p>
				</div>

				<div class="form-group"
					data-ng-class="{'has-error': errors.description.has}">
					<label class="control-label">{{trans('cms::staticcontent.contents')}}
						<span class="asterisk">*</span>
					</label>
					<textarea type="text" name="description" class="form-control"
						data-ng-model="testCtrl.testimonial.description"
						placeholder="{{trans('cms::staticcontent.description_placeholder')}}"
						value="{{old('content')}}" rows="5" cols="50"></textarea>
					<p class="help-block" data-ng-show="errors.description.has">@{{
						errors.description.message }}</p>
				</div>

				<div class="form-group">
					<label class="control-label">{{trans('cms::staticcontent.status')}}</label>
					<select class="form-control mb10" name="is_active"
						data-ng-model="testCtrl.testimonial.is_active">
						<option value="1">{{trans('cms::staticcontent.active')}}</option>
						<option value="0">{{trans('cms::staticcontent.inactive')}}</option>
					</select>
				</div>
				<div class="form-group">
        					<div flow-object="existingFlowObject" flow-init
              flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]"
              flow-files-submitted="$flow.upload()">
              <div class="">
          <p class="help-block" data-ng-show="errors.image.has">@{{ errors.image.message }}</p>
          <hr class="soften"/>

          <div>
            <div class="thumbnail" ng-hide="$flow.files.length">
             <img ng-if ="testCtrl.testimonial.image"  src="@{{testCtrl.testimonial.image}}" />
      		<img ng-if ="!testCtrl.testimonial.image"  src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
   				 </div>
            <div class="thumbnail" ng-show="$flow.files.length">
            <img ng-if ="!testCtrl.testimonial.image"  src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />
            <img ng-if ="testCtrl.testimonial.image"  flow-img="$flow.files[0]" />
            </div>
            <div>
              <a href="javascript:;" class="btn btn-primary upload_video" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Select image</a>
              <a href="javascript:;" class="btn btn-default" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Change</a>
              <a href="javascript:;" class="btn btn-danger" ng-show="testCtrl.testimonial.image || $flow.files.length" ng-click="$flow.cancel();testCtrl.testimonial.image='';"> Remove
              </a>
              <span  class="loaders" id="loader" style="display: none">
 <img src ="{{ url('contus/base/images/admin/loader.gif') }}" alt="ImageLoader" height="100" width="100">
                      				  </span>
            </div>
            <p class="intimation">
              Only PNG,GIF,JPG files allowed.
            </p>
              </div>
        </div>
      </div>
       <input type="hidden" name="image" id="image" data-ng-model="testCtrl.testimonial.image" value="{{old('image')}}"/>
				</div>
			</div>
			<div class="panel-footer clearfix">
				<button class="btn btn-primary pull-right ">{{trans('base::general.submit')}}</button>
				&nbsp; <span class="btn btn-danger pull-right mr10"
					data-ng-click="testCtrl.closeStaticContentEdit()">{{trans('base::general.cancel')}}</span>
			</div>
		</form>
	</div>
</nav>

