@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('header')
@include('base::layouts.headers.dashboard') @endsection

@section('content')
<div class="pageheader clearfix">
	<h2 class="pull-left">
		<i class="fa fa-tag"></i> {{trans('payment::payment.user')}} <span>{{trans('payment::payment.edit_payment')}}</span>
	</h2>
</div>
<form name="paymentForm" method="POST"
	action="{{url('admin/payments/update'.$payment->id)}}"
	enctype="multipart/form-data">
	{!! csrf_field() !!}
	<div class="contentpanel">
		@include('base::partials.errors')
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="add_form clearfix">
							<div class="form-group"
								data-ng-class="{'has-error': errors.name.has}">
								<label class="control-label">{{trans('payment::payment.name')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="name"
									data-ng-model="payCtrl.payment.name" class="form-control"
									placeholder="{{trans('payment::payment.enter_name')}}"
									value="{{old('name')}}" />
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.type.has}">
								<label class="control-label">{{trans('payment::payment.type')}}
									<span class="asterisk">*</span>
								</label> <input type="text" data-ng-model="payCtrl.payment.type"
									class="form-control"
									placeholder="{{trans('payment::payment.enter_type')}}"
									value="{{old('type')}}" />
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.description.has}">
								<label class="control-label">{{trans('payment::payment.description')}}
									<span class="asterisk">*</span>
								</label>
								<textarea type="text" name="content" class="form-control"
									data-ng-model="payCtrl.payment.content"
									placeholder="{{trans('payment::paymententer_description')}}"
									value="{{old('description')}}"></textarea>
								<p class="help-block"hide"></p>
							</div>

							<div class="form-group">
								<label class="control-label">{{trans('payment::payment.mode')}}</label>
								<select class="form-control mb10" name="is_test"
									data-ng-model="payCtrl.payment.is_test>
									<option 
									value="1">{{trans('payment::payment.test')}}
									</option>
									<option value="0">{{trans('payment::payment.live')}}</option>
								</select>
							</div>

							<div class="form-group">
								<label class="control-label">{{trans('payment::payment.status')}}</label>
								<select class="form-control mb10" name="is_active"
									data-ng-model="payCtrl.payment.is_active">
									<option value="1">{{trans('payment::payment.active')}}</option>
									<option value="0">{{trans('payment::payment.inactive')}}</option>
								</select>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="padding10">
			<div class="fixed-btm-action">
				<div class="text-right btn-invoice">
					<a class="btn btn-white mr5" href="{{url('admin/payments')}}">{{trans('base::general.cancel')}}</a>
					<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
				</div>
			</div>
		</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getPaymentAssetsUrl('js/payments/payment.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = { 
            		 paymentForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>

@endsection
