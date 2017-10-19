@extends('base::layouts.default') @section('stylesheet')
<link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/angularjs-datetime-picker.css')}}" />
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
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
@include('video::admin.common.subMenu')
<div class="contentpanel product order_list">
    <div class="panel main_container clearfix" style="border: 1px solid __parent;">
        <div class=" add_form detail_video_form">
            <h4 style="padding: 0 0 20px 0;">Video</h4>
            <div class="" data-base-validator data-ng-controller="VideoDetailController as vgridCtrl">
                <form name="videoEditForm" method="POST" data-ng-init="vgridCtrl.fetchData('{{$id}}')" data-base-validator data-ng-submit="vgridCtrl.saveVideoEdit($event,'{{URL::previous()}}')" enctype="multipart/form-data">
                    <div>
                        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
                            <label class="control-label">
                                {{ __('video::videos.title') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="title" class="form-control" placeholder="{{ __('video::videos.title_placeholder') }}" data-ng-model="vgridCtrl.editVideo.title">
                            <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}" data-ng-hide="editVideo.youtube_live">
                            <label class="control-label">
                                {{ __('video::videos.sections') }}
                                <span class="asterisk">*</span>
                            </label>
                            <div>
                                <div class="admin_category_sub clearfix">
                                    <input type="text" class="form-control" data-ng-model="vgridCtrl.categoryField" placeholder="{{ __('video::videos.sections_place_holder') }}" data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
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
                            <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.exam_ids.has}"  data-ng-hide="editVideo.youtube_live">
                            <label class="control-label">{{ __('video::videos.exams_groups') }} </label>
                            <div>
                                <div class="admin_category_sub clearfix">
                                    <input type="text" class="form-control" data-ng-model="vgridCtrl.examField" placeholder="{{ __('video::videos.exam_place_holder') }}" data-ng-keyup="vgridCtrl.showExamsSuggestions($event)">
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
                        <div class="form-group" data-ng-class="{'has-error': errors.short_description.has}">
                            <label class="control-label">
                                {{ __('video::videos.short_description') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="short_description" class="form-control" placeholder="{{ __('video::videos.short_description_placeholder') }}" data-ng-model="vgridCtrl.editVideo.short_description" data-validation-name="Short Description">
                            <p class="help-block" data-ng-show="errors.short_description.has">@{{ errors.short_description.message }}</p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('video::videos.description') }}</label>
                            <textarea name="description" data-ng-model="vgridCtrl.editVideo.description" class="form-control" rows="5" placeholder="{{ __('video::videos.description_placeholder') }}"></textarea>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.presenter.has}">
                            <label class="control-label">
                                {{ __('video::videos.presenter') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="presenter" class="form-control" placeholder="{{ __('video::videos.presenter_placeholder') }}" data-ng-model="vgridCtrl.editVideo.presenter">
                            <p class="help-block" data-ng-show="errors.presenter.has">@{{ errors.presenter.message }}</p>
                        </div>
                         <div class="form-group">
                            <label class="control-label">{{ __('video::videos.video_order') }}</label>
                            <input type="text" name="video_order" class="form-control"   data-ng-model="vgridCtrl.editVideo.video_order" placeholder="{{ __('video::videos.video_order') }}">
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.published_on.has}">
                            <label class="control-label">
                                Published on
                            </label>
                             <input type="text"   name="published_on" id="published_on"   ng-model="vgridCtrl.editVideo.published_on"   datetime-picker  size="30"  placeholder="YYYY-MM-DD" data-validation-name = "Published on" value="{{old('published_on')}}" class="form-control" ng-blur="dateBlur($event,vgridCtrl.editVideo.published_on)" ng-keyup="dateKeyup($event,vgridCtrl.editVideo.published_on)"/>
                            <p class="help-block" data-ng-show="errors.published_on.has">@{{ errors.published_on.message }}</p>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.is_featured.has}" ng-hide="true">
                            <label class="control-label">
                                {{ __('video::videos.is_featured') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="is_featured" data-ng-model="vgridCtrl.editVideo.is_featured">
                                <option value="">{{ __('video::videos.select_featured_status') }}</option>
                                <option value="1">{{ __('video::videos.yes') }}</option>
                                <option value="0">{{ __('video::videos.no') }}</option>
                            </select>
                          <p class="help-block" data-ng-show="errors.is_featured.has">@{{ errors.is_featured.message }}</p>
                          </div>
                          <div class="form-group"  ng-hide="true" data-ng-if="vgridCtrl.editVideo.is_featured == 1" data-ng-class="{'has-error': errors.is_feature_time.has}">
                            <label class="control-label">
                                {{ __('video::videos.is_feature_time') }}
                                <span class="asterisk">*</span>
                            </label>
                            <input type="text" name="is_feature_time" class="form-control" placeholder="{{ __('video::videos.is_feature_time_placeholder') }}" data-ng-model="vgridCtrl.editVideo.is_feature_time">
                            <p class="help-block" data-ng-show="errors.is_feature_time.has">@{{ errors.is_feature_time.message }}</p>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="control-label">{{ __('video::videos.tags') }}</label>
                            <div data-select-Two style="height: inherit !important;" class="form-control tagOuterBox kewwords_tag clearfix">
                                <div class="tagBox">
                                    <span data-ng-repeat="tag in keywords" class="result_tag">
                                        @{{tag}}
                                        <span class="removetag fa fa-times" data-ng-click="removeKeyword($index)"></span>
                                    </span>
                                </div>
                                <div contentEditable="true" data-keyword-editable class="edit_keywords" data-ng-model="vgridCtrl.searchKeywords.search_tags" title="Click to edit"></div>
                            </div>
                        </div>
                        <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}" >
                            <label class="control-label">
                                {{ __('video::videos.status') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="is_active" data-ng-model="vgridCtrl.editVideo.is_active" data-validation-name="status">
                                <option value="">{{ __('video::videos.select_status') }}</option>
                                <option value="1">{{ __('video::videos.message.active') }}</option>
                                <option value="0">{{ __('video::videos.message.inactive') }}</option>
                            </select>
                            <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
                        </div>
                         <div class="form-group" data-ng-class="{'has-error': errors.trailer_status.has}">
                            <label class="control-label">
                                {{ __('video::videos.trailer') }}
                                <span class="asterisk">*</span>
                            </label>
                            <select class="form-control" name="trailer_status" data-ng-model="vgridCtrl.editVideo.trailer_status">
                                <option value="0">{{ __('video::videos.no') }}</option>
                                <option value="1">{{ __('video::videos.yes') }}</option>
                            </select>
                          <p class="help-block" data-ng-show="errors.trailer_status.has">@{{ errors.trailer_status.message }}</p>
                          </div>
                        <div class="profile_image_upload">
                            <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">
                                <label class="control-label">{{ __('video::videos.thumnail') }}</label>
                                <div class="form-group scrolling">
                                    <input type="hidden" name="selected_thumb" data-ng-model="vgridCtrl.editVideo.selected_thumb" id="selected_thumb">
                                    <div class="Thumbpreview-container uploaded-images">
                                        <div class="clsFileUpload Thumbpreview" data-ng-class="{'active':(vgridCtrl.editVideo.thumbnail_image==vgridCtrl.editVideo.selected_thumb || vgridCtrl.editVideo.selected_thumb=='thumbnail_image')}">
                                            <span id="thumb-delete" data-ng-click="vgridCtrl.deleteThumbnail();vgridCtrl.editVideo.selected_thumb =vgridCtrl.__codedvideos[0].thumb_url" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-boot-tooltip="true" title="{{__('video::videos.delete_thumbnail')}}">
                                                <i class="fa fa-remove" aria-hidden="true"></i>
                                            </span>
                                            <img id="thumb-preview" class="img-thumbnail" data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-ng-src="@{{vgridCtrl.editVideo.thumbnail_image}}" width="180px" height="180px">
                                            <div id="image-progress" class="hide clsProgressbar" data-ng-click="vgridCtrl.editVideo.selected_thumb = 'thumbnail_image'"></div>
                                            <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                                        </div>
                                        <div title="Set as thumbnail" class="Thumbpreview" data-ng-class="{'active':__.thumb_url==vgridCtrl.editVideo.selected_thumb}" ng-repeat="__ in vgridCtrl.__codedvideos">
                                            <img id="thumb-preview" data-ng-click="vgridCtrl.editVideo.selected_thumb = __.thumb_url" data-ng-show="__.thumb_url" data-ng-src="@{{__.thumb_url}}" width="180px" height="180px" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                                <div ng-hide="vgridCtrl.editVideo.thumbnail_image" class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input">
                                            <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{__('video::videos.select_image')}}</span>
                                            <span class="fileupload-exists">{{__('video::videos.change')}}</span>
                                            <input type="file" id="thumb-image" name="image" data-action="{{url('api/admin/videos/thumbnail')}}" />
                                        </span>
                                        <a href="javascript:;" class="btn btn-default fileupload-exists video-thumb-remove" data-dismiss="fileupload" data-ng-click="vgridCtrl.removeThumbnailProperty()">{{__('video::videos.remove')}}</a>
                                        <p class="help-block hide"></p>
                                    </div>
                                    <p class="intimation">Only jpeg,png files allowed.</p>
                                </div>
                                <p class="help-block" data-ng-show="errors.thumbnail.has">@{{ errors.thumbnail.message }}</p>
                            </div>
                        </div>
                        <div class=""  ng-hide="true">
                            <div class="form-group" data-ng-class="{'has-error': errors.audio.has}">
                                <label class="control-label">{{ __('video::videos.audio') }}</label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input">
                                            <i class="glyphicon glyphicon-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">{{ __('video::videos.audio') }}</span>
                                            <span class="fileupload-exists">{{__('video::videos.change')}}</span>
                                            <input type="file" id="subtitle" name="subtitle" data-action="{{url('api/admin/videos/subtitle')}}" accept=".mp3,mpeg3,audio/*"/>
                                        </span>
                                            <p class="intimation">Only .mp3,mpeg3,audio formats are allowed.</p>
                                        <a href="javascript:;" class="btn btn-default fileupload-exists video-subtitle-remove" data-dismiss="fileupload" data-ng-click="vgridCtrl.removeSubtitleProperty()">{{__('video::videos.remove')}}</a>
                                        <p class="help-block hide"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="clsFileUpload">
                                        <span id="subtitle-delete" data-ng-click="vgridCtrl.deleteSubtitle()" data-ng-show="editVideo.mp3" data-boot-tooltip="true" title="{{__('video::videos.delete_subtitle')}}">
                                            <i class="fa fa-remove" aria-hidden="true"></i>
                                        </span>
                                        <div id="subtitle-preview" class="file-doc-logo" data-ng-show="editVideo.mp3"></div>
                                        <div id="file-progress" class="hide clsProgressbar"></div>
                                    </div>
                                </div>
                                <p class="help-block" data-ng-show="errors.audio.has">@{{ errors.audio.message }}</p>
                            </div>
                        </div>
                        <div class=""  ng-hide="true">
                            <div class="form-group" data-ng-class="{'has-error': errors.pdf.has}">
                                <label class="control-label">{{ __('video::videos.pdf') }}</label>
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
                        <div class=""  ng-hide="true">
                            <div class="form-group" data-ng-class="{'has-error': errors.word.has}">
                                <label class="control-label">{{ __('video::videos.word') }}</label>
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
                    <div class="fixed-btm-action">
                        <button class="btn btn-primary pull-right submitbutton">{{ __('video::videos.submit') }}</button>
                        &nbsp;
                        <a href="{{URL::previous()}}" class="btn btn-danger pull-right mr10">{{ __('video::videos.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="postersModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{__('video::videos.upload_posters')}}</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 upload_image_box">
                    <div class="form-group">
                        <input type="file" id="poster-images" multiple="multiple" name="image" data-action="{{url('api/admin/videos/posters')}}" class="form-control">
                    </div>
                </div>
                <div class="upload_btn">
                    <div class="form-group">
                        <input type="button" class="btn  add_new_product" value="Upload Image" id="poster-image-upload-proceed">
                    </div>
                    <div class="clsFileUpload">
                        <div id="poster-progress" class="clsProgressbar clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="castImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{__('video::videos.upload_image_for_cast_member')}}</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 upload_image_box">
                    <div class="form-group">
                        <input type="file" id="cast-images" name="image" data-action="{{url('api/admin/videos/cast-images')}}" class="form-control">
                    </div>
                </div>
                <div class="upload_btn">
                    <div class="form-group">
                        <input type="button" class="btn  add_new_product" value="Upload Image" id="cast-image-upload-proceed">
                    </div>
                    <div class="clsFileUpload">
                        <div id="cast-progress" class="clsProgressbar clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/angularjs-datetime-picker.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/fine-uploader.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/videos/videoDetail.js')}}"></script>

@endsection
