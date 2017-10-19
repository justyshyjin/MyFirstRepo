@extends('base::customer.default') @section('content')
<div class="container">
<div class="success-container">
<input type="hidden" name="PAYMENT_CODE" value="CODE_PAYMENT_SUCCESS">
    <div class="success-heading text-center">
        <i class="success-tic"></i>
<h3>Payment Success</h3>
    </div> 
	<div class="success-id">
		<p><span>Transaction ID</span><strong>:</strong>{{$getTransactiondetails->transaction_id}}</p>
		<p><span>Name</span><strong>:</strong>{{$getTransactiondetails->name}}</p>
		<p><span>Email</span><strong>:</strong>{{$getTransactiondetails->email}}</p>
		<p><span>Phone</span><strong>:</strong>{{$getTransactiondetails->phone}}</p>
		<p><span>Status</span><strong>:</strong>{{$getTransactiondetails->status}}</p>
	</div></div> 
<?php 
	die;
?>
 </div> 
@endsection
