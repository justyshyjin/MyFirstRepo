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
            <div class="col-md-6 col-sm-6 no-padding after-bg-right">
                 <div class="broadcaste-sign-right">
                   <div class="sin-in-form">
                        <h3>Sign in to your company account</h3>
                        @include('base::partials.errors')
                         <form name="loginForm" method="POST" novalidate data-base-validator enctype="multipart/form-data" data-ng-submit="login($event)">
                         <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                        <div class="sin-in-form-val">
                        <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
            <input type="email" name="email" data-ng-model="user.email"
                class="form-control input-space-full" id=""
                placeholder="{{trans('customer::customer.email')}}"
                value="{{ old('email') }}">
            <p class="form-control input-space-full" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
        </div>
        <div class="form-group" data-ng-class="{'has-error': errors.password.has}">
            <input type="password" name="password"
                data-ng-model="user.password" class="form-control input-space-full" id=""
                placeholder="{{trans('customer::customer.password')}}">
            <p class="form-control input-space-full" data-ng-show="errors.password.has">@{{
                errors.password.message }}</p>
        </div>
                              <p class="forget-password text-right"><a title="Forgot Password?" ui-sref="newpassword" data-toggle="modal" class="forgot-links">Forgot
            Password?</a></p>
                              <div class="create-subscrition">
                                  <button type="submit" class="btn btn-danger">sign in</button>
                               </div>
                               <div class="signup-border-bottom">
                                   <div class="signup-border-bottom-bottom">
                                       <span>or</span>
                                   </div>
                               </div>
                        </div>
                         </form>
                        <div class="sin-in-form-action clearfix">
                            <div class="goo-plus col-md-6 col-sm-6 col-xs-6 no-padding">
                                <a href="{{url('auth/google')}}"><i class="speard-fb hopsprite"></i>Google</a>
                            </div>
                            <div class="face-plus col-md-6 col-sm-6 col-xs-6 no-padding">
                                <a href="{{url('auth/facebook')}}"><i class="speard-plus hopsprite"></i>Facebook</a>
                            </div>
                        </div>
                   </div>
              </div>
            </div>
       </div>
    </div>