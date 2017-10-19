<div class="modal signin-signup">
	<div class="overlay"></div>
	<div class="modal-content content mCustomScrollbar light animated" data-mcs-theme="minimal-dark">
		<div class="left-side">
			<img src="{{$getHopmediaAssetsUrl('images/signup-image.png')}}" alt="signup">
			<h3>HOP MEDIA</h3>
			<p>Create your own VOD Platform</p>
		</div>
		<div class="right-side">
			<div class="signin-content">
				<h3>Sign Up <span class="close sprite"></h3>
				<form name="userForm" method="POST" data-base-validator data-ng-submit="save($event)" enctype="multipart/form-data">
   				{!! csrf_field() !!}
   				<div class="alert alert-success" data-ng-if="showResponseMessage">
			        <button type="button" class="close" data-dismiss="alert">×</button>
			        <span>@{{responseMessage}}</span>
			    </div>
					<div class="group" data-ng-class="{'has-error': errors.name.has}">
		    			<input type="text" name="name" data-ng-model="user.name" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Name</span>
		    			<span class="person sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.company.has}"> 
		    			<input type="text" name="company" data-ng-model="user.company" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.company.has">@{{ errors.company.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Company Name</span>
		    			<span class="company-name sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.phone.has}">
		    			<input type="text" name="phone" data-ng-model="user.phone" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Phone Number</span>
		    			<span class="phone sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.email.has}">
		    			<input type="email" name="email" data-ng-model="user.email" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Email Id</span>
		    			<span class="email-id sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.password.has}">
		    			<input type="password" name="password" data-ng-model="user.password" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.password.has">@{{ errors.password.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Password</span>
		    			<span class="lock sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.confirm_password.has}">
		    			<input type="password" name="confirm_password" data-ng-model="user.confirm_password" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.confirm_password.has">@{{ errors.confirm_password.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Confirm Password</span>
		    			<span class="lock sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.domain.has}">
		    			<input type="text" name="domain" data-ng-model="user.domain" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.domain.has">@{{ errors.domain.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Domain Name</span>
		    			<span class="at-symbol sprite left-sprite"></span>
		    			<span class="correct-symbol sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>

	    			<div class="ckbox" data-ng-class="{'has-error': validationmsg}">
	    				<input type="checkbox" name="Signupterms" data-ng-model="user.Signupterms">
	    				<label>Accept terms and conditions</label>
	    				<p class="form-control input-space-full" data-ng-show="validationmsg">Accept the Terms and condition</p>
	    			</div>

	    			<button type="submit" class="button">Sign Up</button>

	    			<span class="link">Have an account? <a href="javascipt:void(0)" class="signin-link">Sign In</a></span>
				</form>
			</div>

			<div class="signup-content" style="display: none">
				<h3>Sign In <span class="close sprite"></h3>

				<form name="loginForm" method="POST" novalidate data-base-validator enctype="multipart/form-data" data-ng-submit="login($event)" >
					<input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
					<div class="group" data-ng-class="{'has-error': errors.email.has}">
		    			<input type="text" name="email" ng-model="user.email" autocomplete="off" value="{{ old('email') }}">
		    			<p class="form-control input-space-full" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>

		    			<span class="bar"></span>
		    			<span class="floating-label">Username</span>
		    			<span class="person sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>
	    			<div class="group" data-ng-class="{'has-error': errors.password.has}">
		    			<input type="password" name="password" ng-model="user.password" autocomplete="off">
		    			<p class="form-control input-space-full" data-ng-show="errors.password.has">@{{
                errors.password.message }}</p>
		    			<span class="bar"></span>
		    			<span class="floating-label">Password</span>
		    			<span class="lock sprite left-sprite"></span>
		    			<a href="javascipt:void(0)" class="forgot-link">Forgot ?</a>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>

	    			<div class="ckbox" data-ng-class="{'has-error': validationmsg}">
	    				<input type="checkbox" value="1" name="Signinterms" >
	    				<label>Accept terms and conditions</label><br>
	    				<p class="form-control input-space-full" data-ng-show="validationmsg">Accept the Terms and condition</p>
	    			</div>

	    			<button class="button" type="submit">Sign In</button>


	    			<div class="or sprite"><span>or</span></div>

	    			<div class="social-buttons-list">
	    				<button class="social-buttons facebook">
	    					<span class="sprite"></span> <span>Facebook</span>
	    				</button>
	    				<button class="google-plus social-buttons">
	    					<span class="sprite"></span> <span>Google</span>
	    				</button>
	    			</div>

	    			<span class="link">Don’t have an account? <a href="javascipt:void(0)" class="signup-link">Sign Up</a></span>
				</form>
			</div>

			<div class="forgot-content" style="display: none">
				<h3>Forgot Password <span class="close sprite"></h3>
				<form>
					<p>Enter your registered email address and we will send you instructions to reset your password.</p>
					<div class="group">
		    			<input type="email" required="" autocomplete="off">
		    			<span class="bar"></span>
		    			<span class="floating-label">Email Id</span>
		    			<span class="email-id sprite left-sprite"></span>
		    			<!-- <span class="error">nfsfnksdfklsfks</span> -->
	    			</div>

	    			<button class="button">Reset password</button>

	    			<span class="link">Don’t have an account? <a href="javascipt:void(0)" class="signin-link">Sign Up</a></span>
				</form>
			</div>
		</div>
	</div>
</div>