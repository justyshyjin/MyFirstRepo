    @section('profilecontent')
<div class="editprofile col-md-9 ">
    <div class="row">
          <div class="col-md-12 col-xs-12 col-sm-12">
    <div class="row">
            <div class="panel panel-default payment-actions">
  <div class="panel-body">
   <i class="actions-img"></i>
  <div class="payment-actions-content except-profile">
     <ul class="video-member-options clearfix" >
                    <li class="" data-ng-repeat="subcrp in subscriptions">
                        <span>Video / PDF / MP3</span>
                        <strong class="rate-card">@{{subcrp.name}}</strong>
                         <strong class="prices"><i class="fa fa-inr"></i> @{{subcrp.amount}}</strong>
                        <span class="video-valid-text">@{{subcrp.duration}} days</span>
                        <a ui-sref="subscribeinfo" class="action-subscription ripple">Subscribe Now</a>
                    </li>
               </ul>
   </div>
  </div>
</div></div>
        </div>
        <div class="panel panel-default cs-edit-form-cs">
            <div class="panel-body">
                <div class="col-md-6 ">
                    <h3>Edit Profile</h3>
                    <div class="changepassword-form">
                        @include('base::partials.errors')
                        <form name="editCustomer" novalidate
                            data-base-validator
                            enctype="multipart/form-data"
                            data-ng-submit="editCust($event)">
                            <input type="hidden" name="_token"
                                id="csrf-token" value="{{csrf_token()}}" />
                            <div class="form-group">
                                <input type="text" value = "@{{profile.email}}" class="form-control" ng-disabled="true" ng-readonly="true"
                                    placeholder="{{trans('customer::customer.email')}}">
                            </div>
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.name.has}">
                                <input type="text" name="name"
                                    data-ng-model="profile.name"
                                    class="form-control" id=""
                                    placeholder="{{trans('customer::customer.customername_placeholder')}}">
                                <p class="help-block"
                                    data-ng-show="errors.name.has">@{{
                                    errors.name.message }}</p>
                            </div>
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.phone.has}">
                                <input type="text" name="phone" maxlength="11"
                                    data-ng-model="profile.phone"
                                    class="form-control"
                                    placeholder="{{trans('customer::customer.phone_placeholder')}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                               <span class="notifiy-gray">Ex: 09876543210 (or) 9876543210 </span>
                                <p class="help-block"
                                    data-ng-show="errors.phone.has">@{{errors.phone.message }}</p>
                            </div>
                            <div class="form-group"
                                data-ng-class="{'has-error': errors.age.has}">
                               <input type="text" name="age" id="age" data-validation-name = "DOB" data-ng-model="profile.age" size="30" placeholder="DD-MM-YYYY" value="{{old('age')}}" class="form-control" ng-blur="dateBlur($event,profile.age)" ng-keyup="dateKeyup($event,profile.age)" />
                                <p class="help-block"
                                    data-ng-show="errors.age.has">@{{
                                    errors.age.message }}</p>
                            </div>
                            <h4>{{trans('customer::customer.exams')}}</h4>
                            <div class="input" data-ng-class="{'has-error': errors.exam.has}" ng-repeat="exam in exams">
                                            <input type="checkbox" name="exam" class="ng-pristine ng-untouched ng-valid ng-empty" value="@{{exam.slug}}" id="@{{exam.id}}" ng-click="selectexam(exam.slug)" ng-checked="examSelection.indexOf(exam.slug) > -1" class="ng-pristine ng-untouched ng-valid ng-empty" >
                                            <label for="@{{exam.id}}"> @{{exam.title}}

                                        </div>
                                        <p class="help-block " style="color: #a94442;" data-ng-show="errors.exam.has">@{{errors.exam.message }}</p>
                                        <p class="cs-notetext">Note : <em>The above preference is only for statistical purpose. All the videos can be acccessible irrespective of preference</em> </p>
                            <div flow-object="existingFlowObject" flow-init flow-file-added="!!{png:1,gif:1,jpg:1,jpeg:1}[$file.getExtension()]" flow-files-submitted="$flow.upload()">
                                <div class="">
                                   <p class="help-block" data-ng-show="errors.profile.has">@{{ errors.profile.message }}</p>
                                   <hr class="soften"/>
                               <div>
                              <div class="thumbnail" ng-hide="$flow.files.length">
                              <img   ng-src="@{{profile.profile_picture}}"   src="{{$cdnUrl('images/user.png')}}" />
                            </div>
                            <div class="thumbnail"  ng-show="$flow.files.length">
                            <img flow-img="$flow.files[0]"  />
                            </div>
                            <div class="cs-changetext-links">
                            <a href="javascript:;" class="btn" ng-hide="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Select image</a>
                            <a href="javascript:;" class="btn" ng-show="$flow.files.length" flow-btn flow-attrs="{accept:'image/*'}">Change</a>
                            <a href="javascript:;" class="btn btn-danger" ng-show="$flow.files.length" ng-click="$flow.cancel() ;clearProfilepic()"> Remove
                             </a>
                             </div>
                            <p class="cs-small-text">Only PNG,GIF,JPG files allowed.    </p>
                            </div>
                            </div>
                        </div>
                            <input type="hidden" name="profile_picture" id="profile_picture" data-ng-model="profile.profile_picture" value="{{old('profile_picture')}}"/>
                            <div class="form-group">
                                <button type="submit" title="Update"
                                    class="btn btn-submit ripple pull-right">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    @endsection
@include('customer::user.account.index')
