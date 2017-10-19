<div class="payment-section col-md-9">
	<div class="row">
	<form name="subscribeCustomer" novalidate
	data-base-validator enctype="multipart/form-data">
	<div class="subscription-contanier" data-ng-repeat = "record in subscription track by $index">
			<div class="row">
				<div class="col-md-9" >
				<input type="checkbox" name="subscription" id="subscription" />
				<input type="hidden" data-ng-model= "subscription.id[$index]" value=@{{record.amount}} name="checkvalue"/>
					<h5>Upgrade to @{{record.name}}</h5>
					<p>
						<span class="text-blue">@{{record.amount}}</span> @{{record.description}}
					</p></div>
					<div class="col-md-3">
					<a class="btn full-btn btn-subscription" title="Subscribe now" ui-sref="subscribeinfos" >Subscribe now</a>
				   </div>
				
				
			</div>
		</div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-body">
		   	<h3>Credit Card Payment</h3>
		    
		  </div>
		</div>
		<div class="">
			<p class="small-text">Your Premium Membership will begin when you click Start Membership. To cancel, go to 'My Account' and click on 'Cancel Membership'. 
				By clicking Start Membership, you authorize us to continue your membership automatically, wherever applicable, charged onetime to the payment method provided.</p>
		</div>
		<div class="terms-conditions">
			 <div class="checkbox">
			    <label>
			      <input type="checkbox"> I agree to the above conditions and the  <a href="#!">Terms </a> of Use and <a href="">Privacy Policy</a>. 
			    </label>
			  </div>
		</div>
	    <div class="form-group">
		<button type="submit" class="btn btn-green" title="Start membership" data-ng-click="subscribeCustomer($event)">Start membership</button>
		</div>
		<p class="mBottom75">Got questions? <a title="Contact us" href="javascript:void();" class="text-blue">Contact Us</a></p> 
					
	    </form>
</div>