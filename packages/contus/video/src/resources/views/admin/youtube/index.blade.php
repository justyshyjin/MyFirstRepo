@extends('base::layouts.default') @section('stylesheet') @endsection
@section('header') @include('base::layouts.headers.dashboard')
@endsection @section('content')
<div data-ng-app="youTube" data-ng-controller="YoutubeImportController as vgridCtrl">
    @include('video::admin.common.subMenu')
    <div class="contentpanel clearfix video_grid">
        @include('base::partials.errors')
        <div class="alert alert-success"
            data-ng-if="vgridCtrl.showResponseMessage">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span>@{{vgridCtrl.responseMessage}}</span>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script
    src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/youtube/youtube.js')}}"></script>
@endsection
