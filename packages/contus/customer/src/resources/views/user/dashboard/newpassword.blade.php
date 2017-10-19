<div class="broadcaste-sign">
       <div class="container-fluid no-padding text-center height-full">
           <div class="col-md-6 col-sm-6 no-padding after-bg-left">
                  <div class="broadcaste-sign-left">
                         <div><img class="img-responsive" src="images/signin.png" alt=""></div>
                         <div class="sign-in-inner">
                             <h3>Own Your Business</h3>
                             <p>Deploy your own platform</p>
                          </div>  
                          <div class="sign-in-inner-second"> 
                             <p>NEED HELP? LET US KNOW</p>
                                <ul class="contact-support">
                                    <li><i class="hopsprite con-call"></i><span>022 61123989</span></li>
                                    <li><i class="hopsprite con-mail"></i><span>support@hopmedia.com</span></li>
                                </ul>
                           </div>
                  </div>
           </div>
            <div class="col-md-6 col-sm-6 no-padding after-bg-right forget-pwd">
                 <div class="broadcaste-sign-right">
                   <div class="sin-in-form">
                        <h3>Forgot Password?</h3>
                        <p>Enter your registered email address and we will send you instructions to reset your password.</p>
                        @include('base::partials.errors')
                        <form name="loginForm" method="POST" novalidate data-base-validator
		enctype="multipart/form-data"
		data-ng-submit="submitForgotPassowrd($event)">
                        <div class="sin-in-form-val">
                              <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
                                    <input type="email" name="email" data-ng-model="forgot.email" class="form-control" id=""
                placeholder="{{trans('customer::customer.email')}}"
                value="{{ old('email') }}">
            <p class="form-control input-space-ful" data-ng-show="errors.email.has">@{{
                errors.email.message }}</p>
        </div>
                              <p class="forget-password text-right"><a href="#login">Go back to Login</a></p>
                              <div class="create-subscrition">
                                  <button type="submit" class="btn btn-danger">reset password</button>
                               </div>                              
                        </div>
                        </form>
                   </div>
              </div>
            </div>
       </div>
    </div>