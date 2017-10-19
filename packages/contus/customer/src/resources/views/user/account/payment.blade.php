    @section('profilecontent')<div class="payment-section col-md-9">
    <form>
        <div class="clearfix">
            <form name="subscribeCustomer" novalidate
                data-base-validator enctype="multipart/form-data">
                <div class="subscription-contanier">
                    <div class="row"
                        data-ng-repeat="record in subscriptions track by $index">
                        <div class="col-md-9">
                        <div class="maxl">
					    <label class="radio custom-radio inline">
                            <input type="radio" name="subscription"
                                ng-required="!sub" data-ng-model="sub"
                                ng-init="$index==0?(sub=record.slug):''"
                                data-ng-value="record.slug" />
                            <span>Upgrade to @{{record.name}} </span>
                             </label>
                              <p>
                                <span class="text-blue">@{{record.amount}}</span>
                                @{{record.description}}
                            </p>
                             </div>

                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>Credit Card Payment</h3>
            </div>
        </div>
        <div class="">
            <p class="small-text">Your Premium Membership will begin
                when you click Start Membership. To cancel, go to 'My
                Account' and click on 'Cancel Membership'. By clicking
                Start Membership, you authorize us to continue your
                membership automatically, wherever applicable, charged
                onetime to the payment method provided.</p>
        </div>
        <div class="terms-conditions">
            <div class="checkbox">
                <label> <input type="checkbox" required>
                    I agree to the above conditions and the <a
                    ui-sref="staticContent({slug:'terms-and-condition'})">Terms&Conditions</a> of Use and <a
                    ui-sref="staticContent({slug:'privacy-policy'})">Privacy
                        Policy</a>.
                </label>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-green"
                data-ng-click="subscribeCust($event)">Start membership</button>
        </div>
        <p class="mBottom75">
            Got questions ? <a ui-sref="staticContent({slug:'contact-us'})" class="text-blue">Contact
                Us</a>
        </p>
    </form>
</div>   @endsection
@include('customer::user.account.index')