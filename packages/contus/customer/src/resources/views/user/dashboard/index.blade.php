@extends('base::customer.default') @section('content')
@include('base::partials.errors')
<div id="controllerpreloader"
    ng-class="{'loader':$root.httpLoaderLocalElement}">
    <div id="status" ng-show="$root.httpLoaderLocalElement">
        <i></i>
    </div>
</div>
<toast></toast>
<ui-view></ui-view>
@endsection @section('scripts')
<script src="{{$getCustomerAssetsUrl('js/route/route.js')}}"></script>
<script src="{{$getVideoAssetsUrl('js/route/route.js')}}"></script>
<script src="{{$getnotificationAssetsUrl('js/route/route.js')}}"></script>
<script src="{{$getPaymentAssetsUrl('js/route/route.js')}}"></script>
<script src="{{$getCmsAssetsUrl('js/route/route.js')}}"></script>
@if($auth->check() && isset($auth->user()->id)) @else
<script src="{{$getCustomerAssetsUrl('js/route/loginRoute.js')}}"></script>
@endif @endsection
