@extends('base::layouts.default') @section('stylesheet') @endsection @section('header') @include('base::layouts.headers.dashboard') @endsection @section('content')
<div data-ng-controller="ExamGridController as groupCtrl">
    @include('video::admin.common.subMenu')
    <div class="contentpanel clearfix collection_grid">
        @include('base::partials.errors')
        <div class="alert alert-success" data-ng-if="colgridCtrl.showResponseMessage">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span>@{{colgridCtrl.responseMessage}}</span>
        </div>
        <div data-grid-view data-rows-per-page="10" data-route-name="examgroups" data-template-route="admin/examgroups" data-request-grid="examgroups" data-count="false"></div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/ng-flow-standalone.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/groups/examGrid.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection
