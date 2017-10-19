@extends('base::customer.default') @section('content')
@include('base::partials.errors')
<ui-view></ui-view>
@endsection @section('scripts')
    @if($auth->check() && isset($auth->user()->id))
    <script src="{{$getCustomerAssetsUrl('js/myaccount/subscriptionroute.js')}}"></script>
    @endif
@endsection