@extends('base::layouts.default')

@section('stylesheet')
<link href="http://vjs.zencdn.net/5.0.2/video-js.min.css" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard') 
@endsection
@section('content')
<style type="text/css">
  .custom-color {
      color: #a94442;
  }
  .kewwords_tag .tagBox { height: auto; padding:0; }
  .kewwords_tag .edit_keywords{    float: left; padding: 0px 10px; background: #fff;}
  .kewwords_tag .result_tag{display: inline-block;    background-color: #e4e4e4;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    padding: 0 5px;}
  .kewwords_tag span.removetag { border: none; margin-left: 5px; padding: 1px; cursor: pointer; }
  .tagOuterBox:after {content:'';display:block;clear:both;background-color: #D0D0D0;}
  .tagBox {float:left;}
  .tagOuterBox .contentEditable {float:left;}
  div[contentEditable] {    cursor: pointer; background-color: #D0D0D0;}
</style>

@include('payment::admin.common.subMenu')

<div class="contentpanel product order_list">
  <div class="panel main_container clearfix" style="border: 1px solid transparent;">
    <div class=" add_form detail_video_form">
      <h4 style="padding:0 0 20px 0;">
       Video
      </h4>
    <div class="" data-base-validator data-ng-controller="VideoDetailController as vgridCtrl">
      <form name="videoEditForm" method="POST" data-ng-init="vgridCtrl.fetchData('{{$id}}')" data-base-validator data-ng-submit="vgridCtrl.saveVideoEdit($event)" enctype="multipart/form-data">
      <div>
        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
          <label class="control-label">{{ trans('video::videos.title') }} <span class="asterisk">*</span>
          </label>
          <input type="text" name="title" class="form-control" placeholder="{{ trans('video::videos.title_placeholder') }}" data-ng-model="vgridCtrl.editVideo.title">
          <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.category_ids.has}">
          <label class="control-label">{{ trans('video::videos.categories') }} <span class="asterisk">*</span>
          </label>
          <div>
          <div class="admin_category_sub clearfix">
          <input type="text" class="form-control" data-ng-model="vgridCtrl.categoryField" placeholder="{{ trans('video::videos.categories_place_holder') }}" data-ng-keyup="vgridCtrl.showCategoriesSuggestions($event)">
          <ul data-ng-if="vgridCtrl.categorySuggestions.length > 0" class="list_category">
          <li data-ng-repeat="suggestion in vgridCtrl.categorySuggestions" data-ng-click="vgridCtrl.addCategoriesToVideos(suggestion.id,suggestion.name)">@{{suggestion.name}}</li>
          </ul>
          <ul class="select_list_category">
          <li data-ng-repeat="category in vgridCtrl.multipleCategories" data-ng-click="vgridCtrl.removeCategoriesFromVideos($index)"> <span> <i class="fa fa-minus-circle"></i></span>@{{category.name}}</li>
          </ul>
          </div>
          </div>
          <p class="help-block" data-ng-show="errors.category_ids.has">@{{ errors.category_ids.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.short_description.has}">
          <label class="control-label">{{ trans('video::videos.short_description') }} <span class="asterisk">*</span>
          </label>
          <input type="text" name="short_description" class="form-control" placeholder="{{ trans('video::videos.short_description_placeholder') }}" data-ng-model="vgridCtrl.editVideo.short_description">
          <p class="help-block" data-ng-show="errors.short_description.has">@{{ errors.short_description.message }}</p>
        </div>
        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.description') }}</label>
          <textarea name="description" data-ng-model="vgridCtrl.editVideo.description" class="form-control" rows="5" placeholder="{{ trans('video::videos.description_placeholder') }}"></textarea>
        </div>
        <div class="form-group" data-ng-class="{'has-error': errors.is_featured.has}">
          <label class="control-label">{{ trans('video::videos.is_featured') }} <span class="asterisk">*</span>
          </label>
          <select class="form-control" name="is_featured" data-ng-model="vgridCtrl.editVideo.is_featured">
          <option value="">{{ trans('video::videos.select_featured_status') }}</option>
          <option value="1">{{ trans('video::videos.yes') }}</option>
          <option value="0">{{ trans('video::videos.no') }}</option>
          </select>
          <p class="help-block" data-ng-show="errors.is_featured.has">@{{ errors.is_featured.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.trailer.has}">
          <label class="control-label">{{ trans('video::videos.trialer_url') }}</label>
          <input type="text" name="trailer" class="form-control" placeholder="{{ trans('video::videos.trialer_url') }}" data-ng-model="vgridCtrl.editVideo.trailer">
          <p class="help-block" data-ng-show="errors.trailer.has">@{{ errors.trailer.message }}</p>
        </div>

        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.disclaimer') }}</label>
          <textarea name="disclaimer" data-ng-model="vgridCtrl.editVideo.disclaimer" class="form-control" rows="5" placeholder="{{ trans('video::videos.disclaimer') }}"></textarea>
        </div>

        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.under_age') }}</label>
          <input type="number" step="1" min="1"  name="age" class="form-control" placeholder="{{ trans('video::videos.under_age') }}" data-ng-model="vgridCtrl.editVideo.age">
        </div>

        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.allowed_countries') }}</label>
          <select class="form-control video_select_country" multiple data-ng-model="vgridCtrl.editVideo.country_id" data-ng-options="k as v for (k, v) in vgridCtrl.allCountries" name="country_id" >
          </select>
        </div>
        
        <div class="form-group" >
          <label class="control-label">{{ trans('video::videos.tags') }}</label>
          <div data-select-Two style="height: inherit !important;" class="form-control tagOuterBox kewwords_tag clearfix">
            <div class="tagBox">
              <span data-ng-repeat="tag in keywords" class="result_tag">@{{tag}}<span class="removetag fa fa-times" data-ng-click="removeKeyword($index)"></span></span>
            </div>
            <div contentEditable="true" data-keyword-editable class="edit_keywords" data-ng-model="vgridCtrl.searchKeywords.search_tags" title="Click to edit"></div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.subscription') }}</label>
          <select class="form-control" name="subscription" data-ng-model="vgridCtrl.editVideo.subscription">
            <option value="">{{ trans('video::videos.select_subscription') }}</option>
            <option value="free">{{ trans('video::videos.free') }}</option>
            <option value="transactional">{{ trans('video::videos.transactional') }}</option>
            <option value="period">{{ trans('video::videos.period') }}</option>
          </select>
        </div>


        <div class="form-group" data-ng-class="{'has-error': errors.is_active.has}">
          <label class="control-label">{{ trans('video::videos.status') }} <span class="asterisk">*</span>
          </label>
          <select class="form-control" name="is_active" data-ng-model="vgridCtrl.editVideo.is_active" data-validation-name="status">
          <option value="">{{ trans('video::videos.select_status') }}</option>
          <option value="1">{{ trans('video::videos.message.active') }}</option>
          <option value="0">{{ trans('video::videos.message.inactive') }}</option>
          </select>
          <p class="help-block" data-ng-show="errors.is_active.has">@{{ errors.is_active.message }}</p>
        </div>

        <div class="profile_image_upload">
          <div class="form-group">  
            <label class="control-label">{{ trans('video::videos.subtitle') }}</label>  
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="input-append">
                <div class="uneditable-input">
                  <i class="glyphicon glyphicon-file fileupload-exists"></i> <span
                  class="fileupload-preview"></span>
                </div>
                <span class="btn btn-default btn-file"> 
                  <span class="fileupload-new">{{ trans('video::videos.subtitle') }}</span> 
                  <span class="fileupload-exists">{{trans('video::videos.change')}}</span> 
                  <input type="file" 
                  id ="subtitle"   
                  name="subtitle"
                  data-action="{{url('api/admin/videos/subtitle')}}" />
                </span> 
                <a href="#" class="btn btn-default fileupload-exists video-subtitle-remove"
                data-dismiss="fileupload" data-ng-click="vgridCtrl.removeSubtitleProperty()">{{trans('video::videos.remove')}}</a>
                <p class="help-block hide"></p>
              </div>

            </div>
            <div class="form-group">
              <div class="clsFileUpload">
              <span id="subtitle-delete" data-ng-click="vgridCtrl.deleteSubtitle()" data-ng-show="vgridCtrl.editVideo.subtitleName" data-boot-tooltip="true" title="{{trans('video::videos.delete_subtitle')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
                            <div id="subtitle-preview" class="file-doc-logo" data-ng-show="vgridCtrl.editVideo.subtitleName"></div>

              <div id="file-progress" class="hide clsProgressbar">
                  
                 
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="profile_image_upload">
          <div class="form-group" data-ng-class="{'has-error': errors.thumbnail.has}">   
            <label class="control-label">{{ trans('video::videos.thumnail') }}</label>                                           
            <div class="fileupload fileupload-new" data-provides="fileupload">
              <div class="input-append">
                <div class="uneditable-input">
                <i class="glyphicon glyphicon-file fileupload-exists"></i> <span
                class="fileupload-preview"></span>
                </div>
                <span class="btn btn-default btn-file"> 
                <span
                class="fileupload-new">{{trans('video::videos.select_image')}}</span> <span
                class="fileupload-exists">{{trans('video::videos.change')}}</span> 
                <input type="file" 
                id ="thumb-image"   
                name="image"
                data-action="{{url('api/admin/videos/thumbnail')}}" /></span> <a href="#" class="btn btn-default fileupload-exists video-thumb-remove"
                data-dismiss="fileupload" data-ng-click="vgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                <p class="help-block hide"></p>
              </div>
            </div>
            <p class="help-block" data-ng-show="errors.thumbnail.has">@{{ errors.thumbnail.message }}</p>
            <div class="form-group">
              <div class="clsFileUpload">
                <span id="thumb-delete" data-ng-click="vgridCtrl.deleteThumbnail()" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-boot-tooltip="true" title="{{trans('video::videos.delete_thumbnail')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
                <img id="thumb-preview" data-ng-show="vgridCtrl.editVideo.thumbnail_image" data-ng-src="@{{vgridCtrl.editVideo.thumbnail_image}}" width="180px" height="180px"> 
                <div id="image-progress" class="hide clsProgressbar"></div>
                <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">Cast</label>
          <div class="multiple_input">
          <table width="90%" align="center" class="clone_table">
            <tr>                  
              <th><lable class="control-label" style="font-size: 14px;font-weight: bold;">Name</lable></th>
              <th><lable class="control-label" style="font-size: 14px;font-weight: bold;">Role</lable></th>
              <th width="5%"><a  data-ng-click="vgridCtrl.addOption()">+</a></th>
            </tr>                 
            <tr data-ng-repeat="cast in vgridCtrl.editVideo.cast">
              <td>                                                 
                  <div class="form-group" style="margin-bottom:0;">
                      <input data-ng-model="vgridCtrl.editVideo.cast[$index].cast_name" type="text" name="option" placeholder="Name" class="form-control" value="" />
                  </div>                                                    
              </td>
              <td>                                                 
                  <div class="form-group" style="margin-bottom:0;">
                      <input data-ng-model="vgridCtrl.editVideo.cast[$index].cast_role" placeholder="Role" type="text" name="option" class="form-control" value="" />
                  </div>                                                    
              </td>
              <td class="text-left">
                  <button class="btn cast_image_upload_btn" type="button" data-ng-click="vgridCtrl.showCastModel(cast, $index)" data-boot-tooltip="true" title="{{ trans('video::videos.upload_image_for_cast_member') }}"><i class="fa fa-upload"></i></button>
                  <button class="btn attributes_remove_btn" type="button" data-ng-click="vgridCtrl.removeOption($index)" data-boot-tooltip="true" title="{{ trans('video::videos.delete_cast_member') }}"><i class="fa fa-trash-o"></i></button>
                  <div class="cast_image_edit_wrap" data-ng-if="cast.image_url || cast.file">
                      <div data-ng-bind-html="vgridCtrl.getImageElementByCast(vgridCtrl.editVideo.cast[$index])"></div>
                      <span data-ng-click="vgridCtrl.deleteCastImage(cast, $index)" data-boot-tooltip="true" title="{{trans('video::videos.delete_cast_image')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
                  </div>
              </td>
            </tr>
          </table>
          </div>
        </div>    
        
        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.posters') }}</label>
          <button data-ng-click="vgridCtrl.showPosterModel()" type="button" class="btn btn-primary add-form-upload" >Upload Posters</button>
          
          <div class="clearfix">
          <div class="upload-list-images" data-ng-repeat="poster in vgridCtrl.editVideo.videoposter track by $index">
              <span id="poster-delete-@{{ poster.id }}" data-ng-click="vgridCtrl.deletePoster(poster, $index)" data-boot-tooltip="true" title="{{trans('video::videos.delete_poster')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
              <img id="poster-preview-@{{ poster.id }}" data-ng-src="@{{poster.image_url}}" width="180px" height="180px">
          </div>
          <div class="upload-list-images" data-ng-repeat="newPoster in vgridCtrl.editVideo.newPosters track by $index">
              <span data-ng-click="vgridCtrl.deleteNewPoster($index)" data-boot-tooltip="true" title="{{trans('video::videos.delete_poster')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
              <img data-ng-src="@{{newPoster.file.previewSrc}}" width="180px" height="180px">
          </div>
          </div>
          
        </div>
              
      </div>
      <div class="fixed-btm-action">
        <button class="btn btn-primary pull-right">{{ trans('video::videos.submit') }}</button>
        &nbsp;
        <a href="{{url('admin/videos')}}" class="btn btn-danger pull-right mr10" >{{ trans('video::videos.cancel') }}</a>
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
        <h5 class="modal-title">{{trans('video::videos.upload_posters')}}</h5>
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
        <h5 class="modal-title">{{trans('video::videos.upload_image_for_cast_member')}}</h5>
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
@endsection
@section('scripts')
  <script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
  <script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
  <script src="{{$getVideoAssetsUrl('js/videos/videoDetail.js')}}"></script>
@endsection