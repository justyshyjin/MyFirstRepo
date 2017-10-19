<section class="contact-us" data-ng-init="getcontactusrules()">
<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
<form name="staticcontentForm" method="POST" data-base-validator
            data-ng-submit="savecontactus($event)"
            enctype="multipart/form-data" novalidate>
            {!! csrf_field() !!}
    
    <div class="container"
        ng-if="$root.location.absUrl()!== '{{url('/')}}/#/content/contact-us'">
        <div class="row">
            <div class="col-md-12">
                <section class="broadcast-extra">
                  <div class="hopmedia-broadcaste-banner">
                      <img class="img-responsive" ng-src="@{{staticcontent.banner_image}}" alt="" height="" width="">
                  </div>
                  <div class="container no-padding">
                    <div class="broadcaste-banner-overlay">
                       
                    </div>
                  </div>
               </section>
                <h2 ng-bind-html="staticcontent.title">@{{staticcontent.title}}</h2>
                <p ng-bind-html="staticcontent.content">@{{staticcontent.content}}</p>
            </div>
        </div>
    </div>
    <div class="container"
        ng-if="$root.location.absUrl()=== '{{url('/')}}/#/content/contact-us'">
        <div class="row">
          <section class="broadcast-extra">
                  <div class="hopmedia-broadcaste-banner">
                      <img class="img-responsive" ng-src="@{{staticcontent.banner_image}}" alt="" height="" width="">
                  </div>
                  <div class="container no-padding">
                    <div class="broadcaste-banner-overlay">
                       
                    </div>
                  </div>
               </section>
            <div class="col-md-7 col-xs-12 col-sm-12">
                <div class="contact-form text-center">
                    <h3>Let's talk</h3>
                    <p>
                        <strong>Questions?Comments? We'd love to hear
                            from you</strong>please don't hesitate to
                        get in touch
                    </p>
                    <div class="enquiry row text-left">
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group"   data-ng-class="{'has-error': errors.name.has}">
                            <label class="control-label">{{trans('cms::staticcontent.name')}}
                            <span class="asterisk contact_us_asterisk">*</span>
                             </label> <input type="text" name="name"
                            data-ng-model="staticcontent.name" class="input-box"
                            placeholder="{{trans('cms::staticcontent.name_placeholder')}}"
                            />
                              <p class="help-block" data-ng-show="errors.name.has">@{{errors.name.message }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group"   data-ng-class="{'has-error': errors.email.has}">
                            <label class="control-label">{{trans('cms::staticcontent.static_email')}}
                            <span class="asterisk contact_us_asterisk">*</span>
                             </label> <input type="text" name="email"
                            data-ng-model="staticcontent.email" class="input-box"
                            placeholder="{{trans('cms::staticcontent.email_placeholder')}}"
                           />
                              <p class="help-block" data-ng-show="errors.email.has">@{{errors.email.message }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-6">
                           <div class="form-group"   data-ng-class="{'has-error': errors.phone.has}">
                            <label class="control-label">{{trans('cms::staticcontent.phone')}}
                            <span class="asterisk contact_us_asterisk">*</span>
                             </label> <input type="text" name="phone"
                            data-ng-model="staticcontent.phone" class="input-box"
                            placeholder="{{trans('cms::staticcontent.phone_placeholder')}}"
                            />
                              <p class="help-block" data-ng-show="errors.phone.has">@{{errors.phone.message }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-sm-6">
                           <div class="form-group"   data-ng-class="{'has-error': errors.country.has}">
                            <label class="control-label">{{trans('cms::staticcontent.country')}}
                            <span class="asterisk contact_us_asterisk"></span>
                             </label> <input type="text" name="country"
                            data-ng-model="staticcontent.country" class="input-box"
                            placeholder="Enter Your Country"
                            />
                              <p class="help-block" data-ng-show="errors.country.has">@{{errors.country.message }}</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 col-sm-12">
                           <div class="form-group"   data-ng-class="{'has-error': errors.message.has}">
                            <label class="control-label">{{trans('cms::staticcontent.subject')}}
                            <span class="asterisk contact_us_asterisk">*</span>
                             </label> <textarea name="message"
                            data-ng-model="staticcontent.message" class="description"
                            placeholder="{{trans('cms::staticcontent.message_placeholder')}}" row="7"
                             ></textarea>
                              <p class="help-block" data-ng-show="errors.message.has">@{{errors.message.message }}</p>
                            </div>
                        </div>
                       <!-- <div class="col-md-6 col-xs-12 col-sm-6">
                    <div class="g-recaptcha" data-sitekey="6Le1MRIUAAAAAJ651k_-_-LKB3IuJmUo0ZBoSM2n" id="recaptcha" ></div>

                        </div> -->
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <button title="Submit" title="Submit" class="submit-btn">submit
                                    your message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-xs-12 col-sm-12">
                <h2>@{{staticcontent.title}}</h2>
                <p ng-bind-html="staticcontent.content"></p>
            </div>
        </div>
    </div>
</form>
<div class="location-map" ng-if="$root.location.absUrl()=== '{{url('/')}}/#/content/contact-us' || $root.location.absUrl()=== '{{URL::to('/')}}/content/contact-us?type=mobile'" >
         <iframe id="map" width="100%" height="450"  ng-src="@{{currentMapUrl}}"> <!--content--> </iframe>
</div>
</section>
<script>
recaptcha: {
   //required: true
     }
angular.element(document).ready(function() {
var getAddress=encodeURIComponent($('#address').text());
$('#map')
.attr('src','https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3887.314926630294!2d80.1981563!3d13.0156062!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5260d084dc54cd%3A0xb3e84ab20dc3785e!2sContus!5e0!3m2!1sen!2sin!4v1501755330439'+getAddress);


});

</script>
