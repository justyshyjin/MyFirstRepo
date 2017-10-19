    @section('profilecontent')<div class="changepassword col-md-9">
    <div class="row">
        <div class="subscription-contanier">
            <div class="row">
                <div class="col-md-9">
                    <h5>Upgrade to @{{subscription.name}}</h5>
                    <p>
                        <span class="text-blue">@{{subscription.amount}}</span>
                        @{{subscription.description}}
                    </p>
                </div>
                <div class="col-md-3">
                    <a title="Subscribe now" class="btn full-btn btn-subscription"
                        ui-sref="subscribeinfo">Subscribe now</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-6 col-sm-offset-1 ">
                    <h3>Change Password</h3>
                    <div class="changepassword-form">
                        @include('base::partials.errors')
                        <form name="changePassword" novalidate
                            data-base-validator
                            enctype="multipart/form-data"
                            data-ng-submit="resetPassword($event)">
                            <input type="hidden" name="_token"
                                id="csrf-token" value="{{csrf_token()}}" />
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.old_password.has}">
                                <input type="password"
                                    name="old_password"
                                    data-ng-model="user.old_password"
                                    class="form-control" id=""
                                    data-validation-name="{{trans('customer::customer.changepassword.oldpassword')}}"
                                    placeholder="{{trans('customer::customer.changepassword.oldpassword')}}">
                                <p class="help-block"
                                    data-ng-show="errors.old_password.has">@{{
                                    errors.old_password.message }}</p>
                            </div>
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.password.has}">
                                <input type="password" name="password"
                                    data-ng-model="user.password"
                                    class="form-control" id=""
                                    data-validation-name="{{trans('customer::customer.changepassword.newpassword')}}"
                                    placeholder="{{trans('customer::customer.changepassword.newpassword')}}">
                                <p class="help-block"
                                    data-ng-show="errors.password.has">@{{
                                    errors.password.message }}</p>
                            </div>
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.password_confirmation.has}">
                                <input type="password"
                                    name="password_confirmation"
                                    data-ng-model="user.password_confirmation"
                                    class="form-control" id=""
                                    data-validation-name="{{trans('customer::customer.changepassword.confirmpassword')}}"
                                    placeholder="{{trans('customer::customer.changepassword.confirmpassword')}}">
                                <p class="help-block"
                                    data-ng-show="errors.password_confirmation.has">@{{
                                    errors.password_confirmation.message
                                    }}</p>
                            </div>
                            <div class="form-group">
                                <button type="submit" title="Change Password"
                                    class="btn btn-submit pull-right">Change
                                    Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    @endsection
@include('customer::user.account.index')