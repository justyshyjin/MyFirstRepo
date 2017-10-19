@extends('base::layouts.default')

@section('stylesheet')
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection

@section('header')
@include('base::layouts.headers.dashboard')
@endsection

@section('content')
<div data-ng-controller="CategoryGridController as catgridCtrl" >
@include('video::admin.common.subMenu')
<div class="contentpanel clearfix category_grid" >
                @include('base::partials.errors')
    <div class="alert alert-success" data-ng-if="catgridCtrl.showResponseMessage">
       <button type="button" class="close" data-dismiss="alert">×</button>
        <span>@{{catgridCtrl.responseMessage}}</span>
  </div>
    <div
        data-grid-view
        data-rows-per-page="10"
        data-route-name="categories"
        data-template-route = "admin/categories"
        data-request-grid="categories"
        data-count = "false"
    ></div>
            </div>

<nav class="st-menu st-effect-17" id="menu-17">
  <div class="pop_over_continer">
   <form name="categoriesForm" method="POST" data-base-validator data-ng-submit="catgridCtrl.categorySave($event, catgridCtrl.category.id)" enctype="multipart/form-data">
   {!! csrf_field() !!}
    <div class="video_form add_form">

        <h5 data-ng-if="!catgridCtrl.category.id">Add New category</h5>
        <h5 data-ng-if="catgridCtrl.category.id">Edit category</h5>
        @include('base::partials.errors')
        <div class="form-group" data-ng-class="{'has-error': errors.title.has}">
          <label class="control-label">{{trans('video::categories.title')}} <span class="asterisk">*</span></label>
          <input type="text" name="title" maxlength="255" class="form-control" data-unique="@{{catgridCtrl.categoriesUniqueRoute}}" data-ng-model="catgridCtrl.category.title" placeholder="{{trans('video::categories.title')}}" value="{{old('title')}}" />
          <p class="help-block" data-ng-show="errors.title.has">@{{ errors.title.message }}</p>
        </div>
        <div class="form-group">
          <label class="control-label">{{ trans('video::categories.parent_category') }} </label>
          <div class="category_tree">
              <div class="categoryList" data-ng-bind-html="catgridCtrl.allCategoriesHTML"></div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label">{{ trans('video::videos.status') }} </label>
          <select class="form-control" name="is_active" data-ng-model="catgridCtrl.category.is_active">
            <option value="1">{{ trans('video::videos.message.active') }}</option>
            <option value="0">{{ trans('video::videos.message.inactive') }}</option>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label">{{ trans('video::categories.order') }} </label>
          <input type="text" name="is_leaf_category" maxlength="10" class="form-control" data-ng-model="catgridCtrl.category.is_leaf_category" placeholder="{{trans('video::categories.order')}}" value="{{old('is_leaf_category')}}" />
        </div>

        <div class="form-group" data-ng-if="catgridCtrl.categoryFull.level=='1'">
          <label class="control-label">Preference Default Order (Mobile App)</label>
          <select class="form-control" data-ng-model="catgridCtrl.pref" >
            <option value="1">{{ trans('video::videos.message.active') }}</option>
            <option value="0">{{ trans('video::videos.message.inactive') }}</option>
          </select>
          <input type="text" data-ng-if="catgridCtrl.pref==1" name="preference_order" maxlength="10" class="form-control" data-ng-model="catgridCtrl.category.preference_order" placeholder="{{trans('video::categories.order')}}" value="{{old('preference_order')}}" />
        </div>


        <div class="form-group" data-ng-class="{'has-error': errors.category_image.has}">
        <label class="control-label">{{ trans('video::categories.category_image') }} </label>
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
                                                                    id ="category-image"
                                                                    name="image"
                                                                    data-action="{{url('api/admin/categories/category-image')}}" /></span> <a href="#" class="btn btn-default fileupload-exists category-image-remove"
                                                            data-dismiss="fileupload" data-ng-click="catgridCtrl.removeThumbnailProperty()">{{trans('video::videos.remove')}}</a>
                                                                  <p class="help-block hide"></p>
                                                    </div>
                                            </div>
                                        	<p class="help-block" data-ng-show="errors.category_image.has">@{{ errors.category_image.message }}</p>
                                            <div class="form-group">
                                                <div class="clsFileUpload">
                                                    <span id="category-image-delete" data-ng-click="catgridCtrl.deleteCategoryImage()" data-ng-show="catgridCtrl.category.image_url" data-boot-tooltip="true" title="{{trans('video::videos.delete_category_image')}}"><i class="fa fa-remove" aria-hidden="true"></i></span>
                                                    <img id="category-image-preview" data-ng-show="catgridCtrl.category.image_url" data-ng-src="@{{catgridCtrl.category.image_url}}" width="180px" height="180px">
                                                    <div id="category-image-progress" class="hide clsProgressbar"></div>
                                                    <input type="hidden" name="uploadedImage" value="" id="uploadedImage">
                                                </div>
                                            </div>
                                        </div>

    </div>
    <div class="panel-footer clearfix">
      <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
      &nbsp;
      <span class="btn btn-danger pull-right mr10" data-ng-click="catgridCtrl.closeCategoryEdit()" >{{ trans('base::general.cancel') }}</span>
    </div>
    </form>
  </div>
</nav>

</div>
@endsection

@section('scripts')
    <script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/categories/categoryGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection