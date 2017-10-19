@extends('base::layouts.default')

@section('stylesheet')
@endsection

@section('header')
@include('base::layouts.headers.dashboard') 
@endsection

@section('content')
<div data-ng-controller="PresetGridController as pregridCtrl" >
@include('video::admin.common.subMenu') 
<div class="contentpanel clearfix preset_grid" >
                @include('base::partials.errors')  
    <div class="alert alert-success" data-ng-if="pregridCtrl.showResponseMessage">
       <button type="button" class="close" data-dismiss="alert">×</button>
        <span>@{{pregridCtrl.responseMessage}}</span>
  </div>
    <div 
        data-grid-view 
        data-rows-per-page="10"
        data-route-name="presets"
        data-template-route = "admin/presets"
        data-request-grid="presets"
        data-count = "false"
    ></div>
            </div>
</div>
@endsection

@section('scripts')
	<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
	<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
    <script src="{{$getVideoAssetsUrl('js/presets/presetGrid.js')}}"></script>
    <script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection