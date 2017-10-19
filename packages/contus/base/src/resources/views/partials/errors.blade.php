@if ($errors->any())
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <ul>
        {{!! implode('', $errors->all('<li>:message</li>')) !!}}
    </ul>
</div>
@endif
@if (session()->has('message'))
<div class="alert alert-warning">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
      <span>{{ session('message') }}</span>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
      <span>{{ session('success') }}</span>
</div>
@endif
@if (session()->has('returnSuccess'))
<div class="alert alert-success">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
      <span>{{ session('returnSuccess') }}</span>
</div>
@endif
@if (session()->has('error'))
  <div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span>{!! session('error') !!}</span>
  </div>
@endif

@if(session()->has('merchant-subscription-error'))
  <div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span>{{ session('merchant-subscription-error') }} <a href='business-info'>Pay Now</a></span>
  </div>
@endif
