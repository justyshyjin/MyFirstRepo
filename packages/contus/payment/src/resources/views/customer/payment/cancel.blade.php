
@extends('base::customer.default') @section('content')

<div class="container">
<div class="cancelled-container">
<input type="hidden" name="PAYMENT_CODE" value="CODE_PAYMENT_CANCELLED">
     <div class="cancelled-heading text-center">
      <i class="cancelled-tic"></i>
      <h3>Payment Cancelled</h3>
     </div>
     <div class="cancelled-id">
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