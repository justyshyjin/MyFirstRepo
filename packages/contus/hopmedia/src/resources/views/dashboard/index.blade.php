@extends('hopmedia::common.partials')
@section('content')
<div id="controllerpreloader"
    ng-class="{'loader':httpLoaderLocalElement}">
    <div id="status" ng-show="httpLoaderLocalElement">
        <i></i>
    </div>
</div>
<toast></toast>
<ui-view></ui-view>
@endsection
@section('scripts')
<script src="{{$getHopmediaAssetsUrl('js/route/route.js')}}"></script>
@endsection
