<div class="panel main_container">
    <div class="tab-content">
        <div class="tab-pane active" id="banner">
            <div class="tab_search clearfix"></div>
            <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
                <div class="table_loader">
                    <div class="loader"></div>
                </div>
            </div>
            <table class="table" data-ng-init="bannerCtrl.setQuery('{{auth()->user()->id}}')">
                <thead>
                    <tr>
                        <th class="center">{{trans('cms::staticcontent.serial_no')}}</th>
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
                            <input type="text" class="form-control" data-ng-model="searchRecords.title" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::staticcontent.enter_title')}}">
                        </td>
                        <td class="search_product">
                            <input type="text" class="form-control" data-ng-model="searchRecords.description" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('cms::staticcontent.enter_slugs')}}">
                        </td>
                        <td>
                        <td></td>


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
                        <td>@{{record.title}}</td>
                        <td class="cs-testimonial-img">@{{record.type}}</td>
                        <td ng-if="record.type =='video'">
                        <video width="320" height="240" controls ng-src="@{{record.banner_image}}" src="@{{record.banner_image}}">
                        </td>
                        <td ng-if="record.type =='image'">
                            <img alt="" class="cs-testimonial-img" ng-src="@{{record.banner_image}}" src="@{{record.banner_image}}">
                        </td>
                        <td>@{{record.created_at}}</td>
                        <td class="table-action">
                            <div id="st-trigger-effects" class="tooltips edit_table_icon" data-boot-tooltip="true" title="Edit">
                                <button data-effect="st-effect-17" class="table_action" data-ng-click="bannerCtrl.editStaticContent(record)">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
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
        <form name="bannerForm" method="POST" data-base-validator data-ng-submit="bannerCtrl.save($event,bannerCtrl.banner.id)" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="video_form add_form">
                <h5 data-ng-if="!bannerCtrl.banner.id">{{trans('cms::staticcontent.banner_heading')}} - {{trans('cms::staticcontent.add_new_banner')}}</h5>
                <h5 data-ng-if="bannerCtrl.banner.id">{{trans('cms::staticcontent.banner_heading')}} - {{trans('cms::staticcontent.edit_new_banner')}}</h5>
                @include('base::partials.errors')
                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                    <label class="control-label">
                        {{trans('cms::staticcontent.bannername')}}
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="name" data-ng-model="bannerCtrl.banner.title" class="form-control" placeholder="{{trans('cms::staticcontent.title_placeholder_banner')}}" value="{{old('title')}}" />
                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.name.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.category.has}">
                    <label class="control-label">
                        Category title
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="category" data-ng-model="bannerCtrl.banner.category" class="form-control" placeholder="Category title" value="{{old('category')}}" />
                    <p class="help-block" data-ng-show="errors.category.has">@{{ errors.name.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.category.has}">
                    <label class="control-label">
                       Image url
                        <span class="asterisk">*</span>
                    </label>
                    <input type="text" name="image_url" data-ng-model="bannerCtrl.banner.imageUrl" class="form-control" placeholder="Image url" value="{{old('imageUrl')}}" />
                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.name.message }}</p>
                </div>
                <div class="form-group" data-ng-class="{'has-error': errors.type.has}">
                    <label class="control-label">
                        {{trans('cms::staticcontent.type')}}
                        <span class="asterisk">*</span>
                    </label><br>
                    <input type="radio" value="image" data-ng-model="bannerCtrl.banner.type"> Image <br>
                     <input type="radio" value="video" data-ng-model="bannerCtrl.banner.type"> Video <br>
                    <p class="help-block" data-ng-show="errors.type.has">@{{ errors.description.message }}</p>
                </div>
                <div class="form-group"  ng-show="bannerCtrl.banner.type=='image'">
                    <div flow-object="existingFlowObject" flow-init flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]" flow-files-submitted="$flow.upload()">
                        <div class="">
                            <p class="help-block" data-ng-show="errors.banner_image.has">@{{ errors.banner_image.message }}</p>
                            <hr class="soften" />
                            <div>
                                <div class="thumbnail" ng-hide="$flow.files.length">
                                    <img ng-if="bannerCtrl.banner.banner_image" src="@{{bannerCtrl.banner.banner_image}}" />
                                    <img ng-if="!bannerCtrl.banner.banner_image" src="{{URL::to('/')}}/@{{bannerCtrl.banner.url}}" />
                                </div>
                                <div class="thumbnail" ng-show="$flow.files.length">
                                    <img ng-if="!bannerCtrl.banner.banner_image" src="{{URL::to('/')}}/@{{bannerCtrl.banner.url}}" />
                                    <img ng-if="bannerCtrl.banner.banner_image" flow-img="$flow.files[0]" />
                                </div>
                                <div>
                                    <a href="javascript:;" class="btn btn-primary upload_video" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        Select image
                                    </a>
                                    <a href="javascript:;" class="btn btn-default" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Change</a>
                                    <a href="javascript:;" class="btn btn-danger" ng-show="bannerCtrl.banner.banner_image || $flow.files.length" ng-click="$flow.cancel();clearbannerImage();"> Remove </a>
                                    <span  class="loaders" id="loader" style="display: none">
 <img src ="{{ url('contus/base/images/admin/loader.gif') }}" alt="ImageLoader" height="100" width="100">
                      				  </span>
                                </div>
                                <p class="intimation">Only PNG,GIF,JPG files allowed.</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="banner_image" id="postImage" data-ng-model="bannerCtrl.banner.banner_image" value="{{old('banner_image')}}" />
                </div>
                 <div id="fine-uploader-gallery" ng-show="bannerCtrl.banner.type=='video'">
                <div>
                    <div id="upload_errors_wrap">
                        <h2 id="upload_error">{{ trans('video::videos.upload_error') }}</h2>
                        <h2 id="upload_staus_when_error"></h2>
                    </div>
                    <h2 id="upload_title">

                    </h2>
                    <span>{{ trans('video::videos.note') }}</span>
                    <p class="intimation">{{ trans('video::videos.accepted_banner_video_formats') }}</p>
                    <p id="video_error">{{ trans('video::videos.select_valid_file') }}</p>
                    <p id="upload_percentage"></p>
                    <div class="upload_file_input">
                        <input type="file" class="filestyle" id="video"  title="Click to Upload Banner Video" name="video" data-buttonName="btn-primary">
                        <span>{{ trans('video::videos.browse_from_computer') }}</span>
                    </div>
                    <div id="video_upload_button_wrap" class="video_upload_div_btn">
                        <button class="btn btn-primary" type="button">{{ trans('video::videos.upload') }}</button>
                    </div>
                    <div class="col-xs-12 col-sm-12 progress-container">
            <div id="progress-bar-wrap" class="progress progress-striped active">
                <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
            </div>
        </div>
                </div>
                 </div>
            </div>

           

            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right submitbutton">{{trans('base::general.submit')}}</button>
                &nbsp;
                <a class="btn btn-danger pull-right mr10"
					href="{{url('admin/banner')}}">{{trans('base::general.cancel')}}</a>
            </div>
        </form>
    </div>
</nav>