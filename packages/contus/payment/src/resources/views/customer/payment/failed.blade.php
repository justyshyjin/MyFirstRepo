@extends('base::customer.default') @section('content') 
<div class="container">
<div class="failed-container">
<input type="hidden" name="PAYMENT_CODE" value="CODE_ERR_PAYMENT_FAILED">
 <div class="failed-heading text-center">
  <i class="failed-tic"></i>
<h3>Payment Failed</h3>
</div>
<div class="failed-id">
        <p><span>Transaction ID</span><strong>:</strong>{{$getTransactiondetails->transaction_id}}</p>
        <p><span>Name</span><strong>:</strong>{{$getTransactiondetails->name}}</p>
        <p><span>Email</span><strong>:</strong>{{$getTransactiondetails->email}}</p>
        <p><span>Phone</span><strong>:</strong>{{$getTransactiondetails->phone}}</p>
        <p><span>Status</span><strong>:</strong>{{$getTransactiondetails->status}}</p>
    </div>
</div> 
  
<?php 
	die;
?>
</div>
@endsection