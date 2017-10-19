@extends('base::layouts.default') @section('stylesheet')
<link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}" />
@endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<style type="text/css">
.custom-color {
    color: #a94442;
}

.kewwords_tag .tagBox {
    height: auto; padding: 0;
}

.kewwords_tag .edit_keywords {
    float: left; padding: 0px 10px; background: #fff;
}

.kewwords_tag .result_tag {
    display: inline-block; background-color: #e4e4e4;
    border: 1px solid #aaa; border-radius: 4px; cursor: default;
    float: left; margin-right: 5px; padding: 0 5px;
}

.kewwords_tag span.removetag {
    border: none; margin-left: 5px; padding: 1px; cursor: pointer;
}

.tagOuterBox:after {
    content: ''; display: block; clear: both; background-color: #D0D0D0;
}

.tagBox {
    float: left;
}

.tagOuterBox .contentEditable {
    float: left;
}

div[contentEditable] {
    cursor: pointer; background-color: #D0D0D0;
}
</style>
<div data-ng-controller="VideoGridController as vgridCtrl">
    @include('video::admin.common.subMenu')
    <div class="contentpanel clearfix video_grid">
        @include('base::partials.errors')
        <div class="alert alert-success" data-ng-if="vgridCtrl.showResponseMessage">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span>@{{vgridCtrl.responseMessage}}</span>
        </div>
        <div data-grid-view data-rows-per-page="10" data-route-name="livevideos" data-template-route="admin/livevideos" data-request-grid="livevideos" data-count="false" data-ng-init="selectTab('live_videos')"></div>
    </div>
    <div class="contentpanel clearfix add_video_container" id="video_frame">
        <i class="fa fa-times" aria-hidden="true" data-ng-click="vgridCtrl.hideUploadOption()"></i>
        <form name="videoForm" enctype="multipart/form-data">
            <div id="file_drop_area" class="upload_video_container">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                <h2 id="preset_error" data-ng-show="vgridCtrl.numberOfActivePresets == 0">{{ trans('video::videos.preset_error') }}</h2>
                <div data-ng-show="vgridCtrl.numberOfActivePresets > 0">
                    <div id="upload_errors_wrap">
                        <h2 id="upload_error">{{ trans('video::videos.upload_error') }}</h2>
                        <h2 id="upload_staus_when_error"></h2>
                    </div>
                    <h2 id="upload_title">
                        <span>{{ trans('video::videos.drag_and_drop') }}</span>
                        {{ trans('video::videos.your_video_file') }}
                    </h2>
                    <p>{{ trans('video::videos.accepted_video_formats') }}</p>
                    <p id="video_error">{{ trans('video::videos.select_valid_file') }}</p>
                    <p id="upload_percentage"></p>
                    <div class="upload_file_input">
                        <input type="file" class="filestyle" id="video" name="video" data-buttonName="btn-primary" multiple>
                        <span>{{ trans('video::videos.browse_from_computer') }}</span>
                    </div>
                    <div id="video_upload_button_wrap" class="video_upload_div_btn">
                        <button class="btn btn-primary" type="button" title="{{ trans('video::videos.upload') }}">{{ trans('video::videos.upload') }}</button>
                    </div>
                </div>
            </div>
            <div data-ng-show="vgridCtrl.numberOfActivePresets > 0 && false" style=" text-align: center; padding-bottom: 20px">
                <button id="google_drive_upload_button" style="padding: 10px" data-ng-click="vgridCtrl.onApiLoad()" type="submit" value="Submit">
                    <img src="{{$getBaseAssetsUrl('images/admin/google_drive.png')}}">
                </button>
                <!-- The Google API Loader script. -->
                <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
                <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>
            </div>
        </form>
        <div class="col-xs-12 col-sm-12 progress-container">
            <div id="progress-bar-wrap" class="progress progress-striped active">
                <div id="progress-bar" class="progress-bar progress-bar-success" style="width: 0%"></div>
            </div>
        </div>
    </div>
    <!-- Video form fields start --> 
	<div id="form_field_div">		
	</div>
	<div id="dynamic_content1" style="display:none">
    <div class="contentpanel clearfix video-upload" id="video_thumb_container1">
            <div class="upload_video_container">            
                 <img src="{{$getBaseAssetsUrl('images/no-preview.png')}}">	
				<span id="upload_title1"></span>
            </div>
			<div class="loading">
				<p id="upload_percentage1"></p>
				<div class="col-xs-12 col-sm-12 progress-container">
					<div id="progress-bar-wrap1" class="progress progress-striped active">
						<div id="progress-bar1" class="progress-bar progress-bar-success" style="width: 0%"></div>
					</div>
				</div>
			</div>
    </div>
    <div id="video_forms1" class="video-container" data-base-validator>
                <form name="videoEditForm" method="POST" data-base-validator data-ng-submit="vgridCtrl.saveVideoEdit($event)" enctype="multipart/form-data">
                    <div>
						<input type="hidden" id="video_id1" name="video_id" data-ng-model="vgridCtrl.editVideo.id"/>
                        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                            <label class="control-label">
                                {{ trans('video::videos.title') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="title" class="form-control" placeholder="{{ trans('video::videos.title_placeholder') }}" data-ng-model="vgridCtrl.editVideo.title">
                            <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                        </div>
					    <div class="clearfix">
                        <div class="form-group wid-50 p-right" data-ng-class="{'has-error': errors.category_ids.has}" data-ng-hide="editVideo.youtube_live">
                            <label class="control-label">
                                {{ trans('video::videos.sections') }}
                                <span class="asterisk">*</span>
                            </label>
                            <div>
                                <div class="admin_category_sub clearfix">
                                    <input type="text" class="form-control" data-ng-model="vgridCtrl.categoryField" placeholder="{{ trans('video::videos.sections_place_holder') }}" data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
                                    <ul data-ng-if="vgridCtrl.categorySuggestions.length > 0" class="list_category">
                                        <li data-ng-repeat="suggestion in vgridCtrl.categorySuggestions" data-ng-click="vgridCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">@{{suggestion.name}}</li>
                                    </ul>
                                    <ul class="select_list_category">
                                        <li data-ng-repeat="category in vgridCtrl.multipleCategories" data-ng-click="vgridCtrl.removeCategoriesFromVideos($index)">
                                            <span>
                                            </span>
                                            @{{vgridCtrl.allCategories[category.id]}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <p class="help-block category_error_message" id="category_error_message1">The Section field is required.</p>
                        </div>
                        <div class="form-group wid-50" data-ng-class="{'has-error': errors.exam_ids.has}"  data-ng-hide="editVideo.youtube_live">
                            <label class="control-label">{{ trans('video::videos.exams_groups') }} </label>
                            <div>
                                <div class="admin_category_sub clearfix">
                                    <input type="text" class="form-control" data-ng-model="vgridCtrl.examField" placeholder="{{ trans('video::videos.exam_place_holder') }}" data-ng-keyup="vgridCtrl.showExamsSuggestions($event)">
                                    <ul data-ng-if="vgridCtrl.examSuggestions.length > 0" class="list_category">
                                        <li data-ng-repeat="exam in vgridCtrl.examSuggestions" data-ng-click="vgridCtrl.addExamToVideos(exam.id,exam.title)">@{{exam.title}}</li>
                                    </ul>
                                    <ul class="select_list_category">
                                        <li data-ng-repeat="exams in vgridCtrl.multipleExams" data-ng-click="vgridCtrl.removeExamsFromVideos($index)">
                                            <span>
                                                <i class="fa fa-minus-circle"></i>
                                            </span>
                                            @{{vgridCtrl.allExams[exams.id]}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.exam_ids.message }}</p>
                        </div>
						</div>
                        <div class="form-group" data-ng-class="{'has-error': errors.short_description.has}">
                            <label class="control-label">
                                {{ trans('video::videos.short_description') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="short_description" class="form-control" placeholder="{{ trans('video::videos.short_description_placeholder') }}" data-ng-model="vgridCtrl.editVideo.short_description" data-validation-name="Short Description">
                            <p class="help-block" data-ng-show="errors.short_description.has">@{{ errors.short_description.message }}</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans('video::videos.description') }}</label>
                            <textarea name="description" data-ng-model="vgridCtrl.editVideo.description" class="form-control" rows="5" placeholder="{{ trans('video::videos.description_placeholder') }}"></textarea>
                        </div>
						<div class="clearfix">
                        <div class="form-group wid-50 p-right" data-ng-class="{'has-error': errors.presenter.has}">
                            <label class="control-label">
                                {{ trans('video::videos.presenter') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="presenter" class="form-control" placeholder="{{ trans('video::videos.presenter_placeholder') }}" data-ng-model="vgridCtrl.editVideo.presenter">
                            <p class="help-block" data-ng-show="errors.presenter.has">@{{ errors.presenter.message }}</p>
                        </div>
                         <div class="form-group wid-50">
                            <label class="control-label">{{ trans('video::videos.video_order') }}</label>
                            <input type="text" name="video_order" class="form-control"   data-ng-model="vgridCtrl.editVideo.video_order" placeholder="{{ trans('video::videos.video_order') }}">
                        </div>
						</div>
                        <div class="form-group wid-50 p-right" data-ng-class="{'has-error': errors.published_on.has}">
                            <label class="control-label">
                                Published on
                            </label>
                             <input type="text"   name="published_on" id="published_on1"   ng-model="vgridCtrl.editVideo.published_on"   datetime-picker  size="30"  placeholder="YYYY-MM-DD" data-validation-name = "Published on" value="{{old('published_on')}}" class="form-control" ng-blur="dateBlur($event,vgridCtrl.editVideo.published_on)" ng-keyup="dateKeyup($event,vgridCtrl.editVideo.published_on)"/>
                            <p class="help-block" data-ng-show="errors.published_on.has">@{{ errors.published_on.message }}</p>
                        </div>
                        <div class="form-group wid-50" data-ng-class="{'has-error': errors.is_featured.has}" ng-hide="true">
                            <label class="control-label">
                                {{ trans('video::videos.is_featured') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="is_featured" data-ng-model="vgridCtrl.editVideo.is_featured">
                                <option value="">{{ trans('video::videos.select_featured_status') }}</option>
                                <option value="1">{{ trans('video::videos.yes') }}</option>
                                <option value="0">{{ trans('video::videos.no') }}</option>
                            </select>
                          <p class="help-block" data-ng-show="errors.is_featured.has">@{{ errors.is_featured.message }}</p>
                          </div>
                          <div class="form-group"  ng-hide="true" data-ng-if="vgridCtrl.editVideo.is_featured == 1" data-ng-class="{'has-error': errors.is_feature_time.has}">
                            <label class="control-label">
                                {{ trans('video::videos.is_feature_time') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="is_feature_time" class="form-control" placeholder="{{ trans('video::videos.is_feature_time_placeholder') }}" data-ng-model="vgridCtrl.editVideo.is_feature_time">
                            <p class="help-block" data-ng-show="errors.is_feature_time.has">@{{ errors.is_feature_time.message }}</p>
                        </div>
                        
                        
                        <div class="form-group wid-50">
                            <label class="control-label">{{ trans('video::videos.tags') }}</label>
                            <div data-select-Two class="form-control tagOuterBox kewwords_tag clearfix">
                                <div class="tagBox">
                                    <span data-ng-repeat="tag in keywords" class="result_tag">
                                        @{{tag}}
                                        <span class="removetag fa fa-times" data-ng-click="removeKeyword($index)"></span>
                                    </span>
                                </div>
                                <div contentEditable="true" data-keyword-editable class="edit_keywords" data-ng-model="vgridCtrl.searchKeywords.search_tags" title="Click to edit"></div>
                            </div>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}" ng-hide="true">
                            <label class="control-label">
                                {{ trans('video::videos.status') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="is_active" data-ng-model="vgridCtrl.editVideo.is_active" data-validation-name="status">
                                <option value="">{{ trans('video::videos.select_status') }}</option>
                                <option value="1">{{ trans('video::videos.message.active') }}</option>
                                <option value="0">{{ trans('video::videos.message.inactive') }}</option>
                            </select>
                            <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
                        </div>
                         <div class="form-group" data-ng-class="{'has-error': errors.trailer_status.has}">
                            <label class="control-label">
                                {{ trans('video::videos.trailer') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="trailer_status" data-ng-model="vgridCtrl.editVideo.trailer_status">
                                <option value="0">{{ trans('video::videos.no') }}</option>
                                <option value="1">{{ trans('video::videos.yes') }}</option>
                            </select>
                          <p class="help-block" data-ng-show="errors.trailer_status.has">@{{ errors.trailer_status.message }}</p>
                          </div>
						<div class="clearfix">
                        <div class="profile_image_upload wid-50 p-right">
                            <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
                                <label class="control-label">{{ trans('video::videos.thumnail') }}</label>
                                <div ng-hide="vgridCtrl.editVideo.thumbnail_image" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input">
                                            <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{trans('video::videos.select_image')}}</span>
                                            <span class="fileupload-exists">{{trans('video::videos.change')}}</span>
                                            <input type="file" id="thumb-image" name="image" data-action="{{url('api/admin/videos/thumbnail')}}" />
                                        </span>
<p class="intimation">Only jpeg,png files allowed.</p>
                                        <a href="javascript:;" class="btn btn-default fileupload-exists video-thumb-remove" data-dismiss="fileupload" data-ng-click="vgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                                        <p class="help-block hide"></p>
                                    </div>
                                    
                                </div>
								<div class="form-group scrolling">
                                    <input type="hidden" name="selected_thumb" data-ng-model="vgridCtrl.editVideo.selected_thumb" id="selected_thumb">
                                    <div class="Thumbpreview-container uploaded-images">
                                        <div class="clsFileUpload Thumbpreview" data-ng-class="{'active':(vgridCtrl.editVideo.thumbnail_image==vgridCtrl.editVideo.selected_thumb || vgridCtrl.editVideo.selected_thumb=='thumbnail_image')}">
                                            <span id="thumb-delete" data-ng-click="vgridCtrl.deleteThumbnail();vgridCtrl.editVideo.selected_thumb =vgridCtrl.transcodedvideos[0].thumb_url" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-boot-tooltip="true" title="{{trans('video::videos.delete_thumbnail')}}">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </span>
                                            <img id="thumb-preview" class="img-thumbnail" data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-ng-src="@{{vgridCtrl.editVideo.thumbnail_image}}" width="180px" height="180px">
                                            <div id="image-progress" class="hide clsProgressbar" data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'"></div>
                                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                                        </div>
                                        <div title="Set as thumbnail" class="Thumbpreview" data-ng-class="{'active':trans.thumb_url==vgridCtrl.editVideo.selected_thumb}" ng-repeat="trans in vgridCtrl.transcodedvideos">
                                            <img id="thumb-preview" data-ng-click="vgridCtrl.editVideo.selected_thumb = trans.thumb_url" data-ng-show="trans.thumb_url" data-ng-src="@{{trans.thumb_url}}" width="180px" height="180px" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.thumbnail.has">@{{ errors.thumbnail.message }}</p>
                            </div>
                        </div>
                        <div class="form-group wid-50">
                            <div class="form-group" data-ng-class="{'has-error': errors.audio.has}">
                                <label class="control-label">{{ trans('video::videos.audio') }}</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input">
                                            <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ trans('video::videos.audio') }}</span>
                                            <span class="fileupload-exists">{{trans('video::videos.change')}}</span>
                                            <input type="file" id="subtitle" name="subtitle" data-action="{{url('api/admin/videos/subtitle')}}" accept=".mp3,mpeg3,audio/*"/>
                                        </span>
                                            <p class="intimation">Only .mp3,mpeg3,audio formats are allowed.</p>
                                        <a href="javascript:;" class="btn btn-default fileupload-exists video-subtitle-remove" data-dismiss="fileupload" data-ng-click="vgridCtrl.removeSubtitleProperty()">{{trans('video::videos.remove')}}</a>
                                        <p class="help-block hide"></p>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none;">
                                    <div class="clsFileUpload">
                                        <span id="subtitle-delete" data-ng-click="vgridCtrl.deleteSubtitle()" data-ng-show="editVideo.mp3" data-boot-tooltip="true" title="{{trans('video::videos.delete_subtitle')}}">
                                            <i class="fa fa-remove" aria-hidden="true"></i>
                                        </span>
                                        <div id="subtitle-preview" class="file-doc-logo" data-ng-show="editVideo.mp3"></div>
                                        <div id="file-progress" class="hide clsProgressbar"></div>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.audio.has">@{{ errors.audio.message }}</p>
                            </div>
                        </div>
</div>
                        <div class="">
                            <div class="form-group" data-ng-class="{'has-error': errors.pdf.has}">
                                <label class="control-label">{{ trans('video::videos.pdf') }}</label>
                                <div flow-object="existingFlowObject" flow-init flow-file-added="!!{pdf:1}[$file.getExtension()]" flow-files-submitted="$flow.upload()">
                                    <div>
                                        <hr class="soften" />
                                        <div>
                                            <div class="" ng-hide="$flow.files.length" ng-if="vgridCtrl.editVideo.pdf">
                                                <a target="_blank" class="intimationdownload" href="@{{vgridCtrl.editVideo.pdf}}">Download PDF</a>
                                            </div>
                                            <div class="" ng-show="$flow.files.length">
                                                <p>@{{vgridCtrl.editVideo.pdf}}</p>
                                            </div>
                                            <div>
                                                <a href="javascript:;" class="btn btn-primary upload_video" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'.pdf'}"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Select pdf</a>
                                                <a href="javascript:;" class="btn btn-default" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'.pdf'}">Change</a>
                                                <a href="javascript:;" class="btn btn-danger" ng-show="vgridCtrl.editVideo.pdf || $flow.files.length" ng-click="$flow.cancel();vgridCtrl.editVideo.pdf = '';"> Remove </a>
                                                <span  class="loaders" id="loaderspdf" style="display: none">
 <img src ="{{ url('contus/base/images/admin/loader.gif') }}" alt="ImageLoader" height="100" width="100">
                      				  </span>
                                            </div>
                                            <p class="intimation">Only PDF files allowed.</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.pdf.has">@{{ errors.pdf.message }}</p>
                            </div>
                        </div>
                        <div class="">
                            <div class="form-group" data-ng-class="{'has-error': errors.word.has}">
                                <label class="control-label">{{ trans('video::videos.word') }}</label>
                                <div flow-object="existingFlowObjectword" flow-init flow-file-added="!!{doc:1,docx:1}[$file.getExtension()]" flow-files-submitted="$flow.upload()">
                                    <div class="">
                                        <hr class="soften" />
                                        <div>
                                            <div class="" ng-hide="$flow.files.length" ng-if="vgridCtrl.editVideo.word">
                                                <a target="_blank" class="intimationdownload" href="@{{vgridCtrl.editVideo.word}}">Download word</a>
                                            </div>
                                            <div class="" ng-show="$flow.files.length">
                                                <p>@{{vgridCtrl.editVideo.word}}</p>
                                            </div>
                                            <div>
                                                <a href="javascript:;" class="btn btn-primary upload_video" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'.doc,.docx'}"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Select document</a>
                                                <a href="javascript:;" class="btn btn-default" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'.doc,.docx'}">Change</a>
                                                <a href="javascript:;" class="btn btn-danger" ng-show="vgridCtrl.editVideo.word || $flow.files.length" ng-click="$flow.cancel();vgridCtrl.editVideo.word = '';"> Remove </a>
                                                <span  class="loaders" id="loadersword" style="display: none">
 <img src ="{{ url('contus/base/images/admin/loader.gif') }}" alt="ImageLoader" height="100" width="100">
                      				  </span>
                                            </div>
                                            <p class="intimation">Only doc,docx files allowed.</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.word.has">@{{ errors.word.message }}</p>
                            </div>
                        </div>
                    </div>
					<button class="btn btn-primary pull-right submitbutton">{{ trans('video::videos.submit') }}</button>
                </form>
            </div>
</div>
   <!-- video form field end  -->
    <nav class="st-menu st-effect-7" id="menu-7">
        <div class="embed">
            <video id="video_player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="none" width="460" height="300" poster="" data-setup="{}">
                <p class="vjs-no-js">{{ trans('video::videos.video_not_supported') }}</p>
            </video>
            <div id="transcode_message" data-ng-show="transcodeMessage">{{ trans('video::videos.transcode_in_progress') }}</div>
        </div>
        <div class="pop_over_continer">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#details" data-ng-click="vgridCtrl.setVideoEditRules()" data-toggle="tab">{{ trans('video::videos.details') }}</a>
                </li>
                <li>
                    <a href="#video_presets" data-toggle="tab">{{ trans('video::videos.presets') }}</a>
                </li>
                <li>
                    <a href="#video_thumbnails" data-ng-click="vgridCtrl.setThumbUploadRules()" data-toggle="tab">{{ trans('video::videos.thumbnails') }}</a>
                </li>
            </ul>
            <div class="pop_over_inner">
                <div class="tab-content">
                    <div class="tab-pane active" id="details">
                        <form name="videoEditForm" method="POST" data-base-validator data-ng-submit="vgridCtrl.saveVideoEdit($event)" enctype="multipart/form-data">
                            <div class="video_form">
                                <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                                    <label class="control-label">
                                        {{ trans('video::videos.title') }}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <input type="text" name="title" class="form-control" placeholder="{{ trans('video::videos.title_placeholder') }}" data-ng-model="vgridCtrl.editVideo.title">
                                    <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                                </div>
                                <div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}">
                                    <label class="control-label">
                                        {{ trans('video::videos.categories') }}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <div>
                                        <div class="admin_category_sub clearfix">
                                            <input type="text" class="form-control" data-ng-model="vgridCtrl.categoryField" placeholder="{{ trans('video::videos.categories_place_holder') }}" data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
                                            <ul data-ng-if="vgridCtrl.categorySuggestions.length > 0" class="list_category">
                                                <li data-ng-repeat="suggestion in vgridCtrl.categorySuggestions" data-ng-click="vgridCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">@{{suggestion.name}}</li>
                                            </ul>
                                            <ul class="select_list_category">
                                                <li data-ng-repeat="category in vgridCtrl.multipleCategories" data-ng-click="vgridCtrl.removeCategoriesFromVideos($index)">
                                                    <span>
                                                        <i class="fa fa-minus-circle"></i>
                                                    </span>
                                                    @{{category.name}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
                                </div>
                                <div class="form-group" data-ng-class="{'has-error': errors.short_description.has}">
                                    <label class="control-label">
                                        {{ trans('video::videos.short_description') }}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <input type="text" name="short_description" class="form-control" placeholder="{{ trans('video::videos.short_description_placeholder') }}" data-ng-model="vgridCtrl.editVideo.short_description">
                                    <p class="help-block" data-ng-show="errors.short_description.has">@{{ errors.short_description.message }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ trans('video::videos.description') }}</label>
                                    <textarea name="description" data-ng-model="vgridCtrl.editVideo.description" class="form-control" rows="5" placeholder="{{ trans('video::videos.description_placeholder') }}"></textarea>
                                </div>
                                <div class="form-group" data-ng-class="{'has-error': errors.is_featured.has}">
                                    <label class="control-label">
                                        {{ trans('video::videos.is_featured') }}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <select class="form-control" name="is_featured" data-ng-model="vgridCtrl.editVideo.is_featured">
                                        <option value="">{{ trans('video::videos.select_featured_status') }}</option>
                                        <option value="1">{{ trans('video::videos.yes') }}</option>
                                        <option value="0">{{ trans('video::videos.no') }}</option>
                                    </select>
                                    <p class="help-block" data-ng-show="errors.is_featured.has">@{{ errors.is_featured.message }}</p>
                                </div>
                                <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
                                    <label class="control-label">
                                        {{ trans('video::videos.status') }}
                                        <span class="asterisk">*</span>
                                    </label>
                                    <select class="form-control" name="is_active" data-ng-model="vgridCtrl.editVideo.is_active" data-validation-name="status">
                                        <option value="">{{ trans('video::videos.select_status') }}</option>
                                        <option value="1">{{ trans('video::videos.message.active') }}</option>
                                        <option value="0">{{ trans('video::videos.message.inactive') }}</option>
                                    </select>
                                    <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
                                </div>
                            </div>
                            <div class="panel-footer clearfix">
                                <button class="btn btn-primary pull-right">{{ trans('video::videos.submit') }}</button>
                                &nbsp;
                                <a href="javascript:void(0)" class="btn btn-danger pull-right mr10" data-ng-click="vgridCtrl.closeVideoEdit()">{{ trans('video::videos.cancel') }}</a>
                            </div>
                        </form>
                    </div>
                    <div id="video_presets" class="tab-pane">
                        <div class="presets_wrap">
                            <div class="preset_wrap" data-ng-repeat="preset in vgridCtrl.editVideo.videoPresets track by $index">@{{ $index+1 }}. @{{ preset }}</div>
                        </div>
                    </div>
                    <div id="video_thumbnails" class="tab-pane">
                        <form name="thumbnailUploadForm" method="POST" data-base-validator data-ng-submit="vgridCtrl.thumbnailUpload($event)" enctype="multipart/form-data">
                            <div class="video_form">
                                <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="input-append">
                                            <div class="uneditable-input">
                                                <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                                <span class="fileupload-preview"></span>
                                            </div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileupload-new">{{trans('video::videos.select_image')}}</span>
                                                <span class="fileupload-exists">{{trans('video::videos.change')}}</span>
                                                <input type="file" id="thumb-image" name="image" data-action="{{url('api/admin/videos/thumbnail')}}" />
                                            </span>
                                            <a href="#" class="btn btn-default fileupload-exists video-thumb-remove" data-dismiss="fileupload" data-ng-click="vgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                                            <p class="help-block hide"></p>
                                        </div>
                                    </div>
                                    <p class="help-block" data-ng-show="errors.thumbnail.has">@{{ errors.thumbnail.message }}</p>
                                    <div class="form-group">
                                        <div class="clsFileUpload">
                                            <span id="thumb-delete" data-ng-click="vgridCtrl.deleteThumbnail();vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-boot-tooltip="true" title="{{trans('video::videos.delete_thumbnail')}}">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </span>
                                            <img id="thumb-preview" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-ng-src="@{{vgridCtrl.editVideo.thumbnail_image}}" width="180px" height="180px">
                                            <div id="thumb-progress" class="hide clsProgressbar" data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'"></div>
                                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer clearfix">
                                <button class="btn btn-primary pull-right">{{ trans('video::videos.submit') }}</button>
                                &nbsp;
                                <a href="javascript:void(0)" class="btn btn-danger pull-right mr10" data-ng-click="vgridCtrl.closeVideoEdit()">{{ trans('video::videos.cancel') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="videoDeleteModal" data-role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{trans('base::gridlist.delete_record')}}</h5>
                </div>
                <div class="modal-body">
                    <div data-ng-show="videoConfirmationDeleteBox">
                        <p>{{trans('base::gridlist.delete_confirm')}}</p>
                    </div>
                </div>
                <div class="clearfix modal-footer video_delete_footer">
                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vgridCtrl.confirmDeleteVideos('single-video')" class="btn btn-primary pull-right mr10" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="videoBulkDeleteModal" data-role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">{{trans('base::gridlist.bulk_action')}}</h5>
                </div>
                <div class="modal-body" data-ng-show="vgridCtrl.isDeleteBulkRecord ">
                    <div>
                        <p>{{trans('base::gridlist.bulk_delete_confirm')}}</p>
                    </div>
                </div>
                <div class="modal-body" data-ng-show="vgridCtrl.isActivateBulkRecord">
                    <div>
                        <p>{{trans('base::gridlist.bulk_activate_confirm')}}</p>
                    </div>
                </div>
                <div class="modal-body" data-ng-show="vgridCtrl.isDeactivateBulkRecord">
                    <div>
                        <p>{{trans('base::gridlist.bulk_deactivate_confirm')}}</p>
                    </div>
                </div>
                <div class="clearfix modal-footer video_delete_footer" data-ng-show="vgridCtrl.isDeleteBulkRecord ">
                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vgridCtrl.confirmDeleteVideos('bulk-video')" class="btn btn-primary pull-right mr10" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
                </div>
                <div class="clearfix modal-footer video_delete_footer" data-ng-show="vgridCtrl.isActivateBulkRecord">
                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(1)" class="btn btn-primary pull-right mr10" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
                </div>
                <div class="clearfix modal-footer video_delete_footer" data-ng-show="vgridCtrl.isDeactivateBulkRecord">
                    <span data-ng-click="vgridCtrl.cancelDeleteVideos()" class="btn btn-danger pull-right" data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                    <span data-ng-click="vgridCtrl.confirmActivateOrDeactivateVideos(0)" class="btn btn-primary pull-right mr10" data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
<script src="http://vjs.zencdn.net/ie8/1.1.0/videojs-ie8.min.js"></script>
<script src="http://vjs.zencdn.net/5.0.2/video.min.js"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/videoGrid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
<script type="text/javascript">
        // <![CDATA[
             window.VPlay = {
                route      : {
                    siteUrl : "{{url('/')}}",
                },
                developerKey   : "{{ $developer_key }}",
                clientId   : "{{ $client_id }}",
             };
        // ]]>
    </script>
@endsection
