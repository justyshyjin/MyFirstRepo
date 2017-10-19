@extends('base::layouts.default') @section('stylesheet')
<link rel="stylesheet"
	href="{{$getBaseAssetsUrl('css/bootstrap-fileupload.min.css')}}" />
<link rel="stylesheet" href="{{$getBaseAssetsUrl('css/uploader.css')}}" />
@endsection @section('content')
<div class="pageheader clearfix">
	<h2 class="pull-left">
		<i class="fa fa-tag"></i> {{trans('customer::subscription.user')}} <span>{{trans('customer::subscription.add_new_subscription')}}</span>
	</h2>
</div>
<form name="subscriptionForm" method="POST"
	action="{{url('admin/subscriptions-plans/add')}}"
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
								<label class="control-label">{{trans('customer::subscription.subscription_name')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="name"
									data-unique="@{{subscriptionCtrl.uniqueRoute}}"
									data-ng-model="subscriptionCtrl.subscriptions_plans.name"
									class="form-control"
									placeholder="{{trans('customer::subscription.subscription_placeholder')}}"
									value="{{old('title')}}" />
								<p class="help-block hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.type.has}">
								<label class="control-label">{{trans('customer::subscription.type')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="type"
									data-ng-model="subscriptionCtrl.subscriptions_plans.type"
									class="form-control"
									placeholder="{{trans('customer::subscription.type_placeholder')}}"
									value="{{old('type')}}" />
								<p class="help-block hide"></p>
							</div>


							<div class="form-group"
								data-ng-class="{'has-error': errors.description.has}">
								<label class="control-label">{{trans('customer::subscription.description')}}
									<span class="asterisk">*</span>
								</label>
								<textarea type="text" name="phone" class="form-control"
									data-ng-model="subscriptionCtrl.subscriptions_plans.description"
									placeholder="{{trans('customer::subscription.description_placeholder')}}"
									value="{{old('description')}}"></textarea>
								<p class="help-block hide"></p>
							</div>

							<div class="form-group"
								data-ng-class="{'has-error': errors.amount.has}">
								<label class="control-label">{{trans('customer::subscription.amount')}}
									<span class="asterisk">*</span>
								</label> <input type="text" name="amount"
									data-ng-model="subscriptionCtrl.subscriptions_plans.amount"
									class="form-control"
									placeholder="{{trans('customer::subscription.amount_placeholder')}}"
									value="{{old('amount')}}" />
								<p class="help-block hide"></p>
							</div>


							<div class="form-group"
								data-ng-class="{'has-error': errors.duration.has}">
								<label class="control-label">{{trans('customer::subscription.duration')}}
									<span class="asterisk">*</span>
								</label>
								<textarea type="text" name="phone" class="form-control"
									data-ng-model="subscriptionCtrl.subscriptions_plans.duration"
									placeholder="{{trans('customer::subscription.duration_placeholder')}}"
									value="{{old('duration')}}"></textarea>
								<p class="help-block hide"></p>
							</div>


							<div class="form-group">
								<label class="control-label">{{trans('customer::subscription.status')}}</label>
								<select class="form-control mb10" name="is_active"
									data-ng-model="subscriptionCtrl.subscriptions_plans.is_active">
									<option value="1">{{trans('customer::subscription.active')}}</option>
									<option value="0">{{trans('customer::subscription.inactive')}}</option>
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
					<a class="btn btn-white mr5" href="{{url('admin/subscriptions-plans')}}">{{trans('base::general.cancel')}}</a>
					<button class="btn btn-primary">{{trans('base::general.submit')}}</button>
				</div>
			</div>
		</div>

</form>
@endsection @section('scripts')
<script src="{{$getBaseAssetsUrl('js/jquery-checktree.js')}}"></script>
<script src="{{$getBaseAssetsUrl('js/Validate.js')}}"></script>
<script src="{{$getCustomerAssetsUrl('js/subscription/subscription.js')}}"></script>
<script type="text/javascript">
    $('#tree').checktree();
        // <![CDATA[
             window.Mara = { 
            		 subscriptionForm : {
                    rules : {!! json_encode($rules) !!}
                },
                route : {
                    
                },
                locale : {!! json_encode(trans('validation')) !!}     
             };
        // ]]>
    </script>

@endsection
