
<!-- Login -->
<div class="modal-header login-title">
    <a href="#" class="close close-btn"  data-dismiss="modal"  aria-label="Close" ng-click="cancel()"> </a>
    <h2 class="modal-title" id="myModalLabel">Sign up</h2>
</div>
<div class="modal-body form-content text-left">
    <div class="clearfix">
        <div class="col-md-7 borderRight">
            <form method="POST" novalidate data-base-validator name="signupForm"
                enctype="multipart/form-data"
                data-ng-submit="signup($event)">
                <input type="hidden" name="_token" id="csrf-token"
            value="{{csrf_token()}}" />
                <div class="form-group"
                    data-ng-class="{'has-error': errors.name.has}">
                    <input type="text" name="@{{(setname)?'name':''}}"
                        data-ng-model="user.name" class="form-control"
                        placeholder="{{trans('customer::customer.name')}}">
                    <p class="help-block" data-ng-show="errors.name.has">@{{
                        errors.name.message }}</p>
                </div>
                <div class="form-group"
                    data-ng-class="{'has-error': errors.email.has}">
                    <input type="email" name="email"
                        data-ng-model="user.email" class="form-control"
                        placeholder="{{trans('customer::customer.email')}}">
                    <p class="help-block"
                        data-ng-show="errors.email.has">@{{
                        errors.email.message }}</p>
                </div>
                <div class="form-group"
                    data-ng-class="{'has-error': errors.phone.has}">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button"
                                class="btn btn-default dropdown-toggle countrycode-select"
                                data-toggle="dropdown"
                                aria-expanded="false" id="formdropdown">
                                IND <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a ng-click = "phonecode('+91','IND')">IND</a></li>
                            </ul>
                        </div>
                        <input type="text" name="@{{(setname)?'phone':''}}"
                            data-ng-model="user.phone" value="+91"
                            class="form-control" placeholder="+91">
                    </div>
                    <p class="help-block"
                        data-ng-show="errors.phone.has">@{{
                        errors.phone.message }}</p>
                </div>
                <div class="form-group"
                    data-ng-class="{'has-error': errors.password.has}">
                    <input type="password" name="password"
                        data-ng-model="user.password"
                        class="form-control"
                        placeholder="{{trans('customer::customer.password')}}">
                    <p class="help-block"
                        data-ng-show="errors.password.has">@{{
                        errors.password.message }}</p>
                </div>
                <div class="form-group"
                    data-ng-class="{'has-error': errors.password_confirmation.has}">
                    <label class="sr-only" for="">Confirm Password</label>
                    <input type="password" name="@{{(setname)?'password_confirmation':''}}"
                        data-ng-model="user.password_confirmation"
                        class="form-control"
                        placeholder="{{trans('customer::customer.password_confirm')}}">
                    <p class="help-block"
                        data-ng-show="errors.password_confirmation.has">{{trans('customer::customer.customer_confirm_passowrd')}}</p>
                </div>
                <div class="form-group">
                    <button  title="Create an account" type="submit" class="btn btn-green full-btn">Create
                        an account</button>
                </div>
            </form>
            <p class="terms-privacy" >
                By Signing up you agree to our <a ui-sref="staticContent({slug:'terms-and-condition'})"><span>T&amp;C</span></a>
                &amp;  <a  title="privacy policy" ui-sref="staticContent({slug:'privacy-policy'})">Privacy
                        Policy</a>.
            </p></a>
        </div>
        <div class="col-md-5">
            <div class="signup-logins">
                <a title="Signup with google" href="{{url('auth/google')}}" class="loginwith-google full-btn" title=""><span
                    class="gplus-icon"></span> Signup with google</a> <a
                    href="{{url('auth/facebook')}}" title="Signup with Facebook" class="loginwith-facebook full-btn"
                    title=""><span class="fb-icon"></span> Signup with
                    Facebook</a>
            </div>
        </div>
    </div>
</div>
<div class="sign-link">
    <span>Already a member? <a title="Login" ui-sref="login">Login</a></span>
</div>

