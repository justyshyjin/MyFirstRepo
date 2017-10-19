@extends('base::layouts.default') @section('header')

@include('base::layouts.headers.dashboard') @endsection

@section('content')

<div data-ng-controller="paymentController as payCtrl">
	<div class="menu_container clearfix">
		<div class="page_menu pull-left">
			<ul class="nav">
				<li><a href="{{url('admin/transactions')}}">{{
						trans('payment::payment.transactions') }}</a></li>
				<li><a href="{{url('admin/payments')}}" class="active">{{
						trans('payment::payment.payments') }}</a></li>
			</ul>
		</div>
	</div>
	<div class="contentpanel product order_list">
		@include('base::partials.errors')
		<div class="alert alert-success"
			data-ng-if="payCtrl.showResponseMessage">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<span>@{{payCtrl.responseMessage}}</span>
		</div>
		<div data-grid-view data-rows-per-page="10"
			data-route-name="payments"
			data-template-route="admin/payments" data-count="false"></div>
	</div>
</div>

@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/classieSidebarEffects.js')}}"></script>
<script
	src="{{$getBaseAssetsUrl('js/classieSidebarEffectsDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/gridView.js')}}"></script>
<script src="{{$getPaymentAssetsUrl('js/payments/index.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/grid.js')}}"></script>
@endsection
