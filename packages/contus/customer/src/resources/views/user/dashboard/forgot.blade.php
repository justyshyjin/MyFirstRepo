<div class="forgotform-page">
<form method="POST" action="{{url('forgotPassword/'.$random)}}" enctype="multipart/form-data">
                {{csrf_field()}}
        <h4>Please Reset your password here</h4>
       			<input type="hidden" value="$random">
                <div class="form-group"
                    data-ng-class="{'has-error': errors.password.has}">
                     <label class="sr-only" for="">New Password</label>
                    <input type="password" name="password"
                        data-ng-model="user.password"
                        class="form-control"
                        placeholder="{{trans('customer::customer.password')}}" required>
                   
                </div>
                <div class="form-group"
                    data-ng-class="{'has-error': errors.password_confirmation.has}">
                    <label class="sr-only" for="">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        data-ng-model="user.password_confirmation"
                        class="form-control"
                        placeholder="{{trans('customer::customer.password_confirm')}}" required>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-green full-btn">Submit</button>
                </div>
            </form>

</div>