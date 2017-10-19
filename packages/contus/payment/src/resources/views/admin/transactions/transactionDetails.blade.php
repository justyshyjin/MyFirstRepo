@extends('base::layouts.default') @section('stylesheet')
<link href="http://vjs.zencdn.net/5.0.2/video-js.min.css"
	rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}"
	rel="stylesheet">
<link href="{{$getBaseAssetsUrl('css/uploader.css')}}" rel="stylesheet">
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection
@section('content')

<style type="text/css">
.custom-color {
	color: #a94442;
}
</style>

<div class="product order_list"
	data-ng-controller="viewTransactionController as transactionCtrl"
	data-ng-init=transactionCtrl.fetchData('{{$id}}')>
	@include('payment::admin.common.subMenu')


	<div class="contentpanel clearfix video-detail"
		data-ng-if="!transactionCtrl.notFoundFlag">
		<div class="video-detail-right-panel">
			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.transaction_id != ''">
				<label>{{trans('payment::transaction.transaction_id')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.transaction_id"
					class="form-control" value="{{old('transaction_id')}}" readonly/>
			</div>

			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.name != ''">
				<label>{{trans('payment::transaction.customer_name')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.customer"
					class="form-control" value="{{old('customer')}}" readonly />
			</div>

			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.payment_name != ''">
				<label>{{trans('payment::transaction.payment_name')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.payment_method"
					class="form-control" value="{{old('payment_name')}}" readonly />

			</div>

			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.status != ''">
				<label>{{trans('payment::transaction.status')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.status"
					class="form-control" value="{{old('status')}}" readonly />
			</div>

			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.message != ''">
				<label>{{trans('payment::transaction.message')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.message"
					class="form-control" value="{{old('message')}}" readonly/>
			</div>

			<div class="video-description"
				data-ng-if="transactionCtrl.transaction.created_at != ''">
				<label>{{trans('payment::transaction.created_at')}}</label> <input
					type="text" name="type"
					data-ng-model="transactionCtrl.transaction.created_at"
					class="form-control" value="{{old('created_at')}}" readonly/>
			</div>
			<div class="video-description"></div>
			<div class="text-right btn-invoice">
				<a class="btn btn-danger pull-right mr10"
					href="{{url('admin/transactions')}}">{{trans('base::general.back')}}</a>
			</div>

		</div>
	</div>
</div>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/bootstrap-fileupload.min.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Uploader.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/validatorDirective.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/requestFactory.js')}}"></script>
<script
	src="{{$getPaymentAssetsUrl('js/transactions/viewTransaction.js')}}"></script>
@endsection
