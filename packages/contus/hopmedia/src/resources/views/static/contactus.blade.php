<section class="contact-us">
    <section class="banner">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Please choose the appropriate department to get in touch with:</p>
        </div>
    </section>


    <section class="container clearfix">
        <div class="form">
            <form>
                <div class="row clearfix">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email ID</label>
                        <input type="email" class="form-control">
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="form-group full-flex">
                        <label>Description</label>
                        <textarea class="form-control"></textarea>
                    </div>
                </div>

                <p>Enter <strong>the word</strong> below, <strong>Separated by a space.</strong> Can’t read the words below? <span>Try different words</span></p>

                <img src="{{$getHopmediaAssetsUrl('images/captcha.png')}}" class="captcha" />
                <div class="row clearfix">
                    <div class="form-group">
                        <label>Text in the box</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="submit" class="button">
                    </div>
                </div>
            </form>
        </div>

        <ul class="contact-info">
            <li class="mail-address">
                <i class="sprite"></i>
                <h5>Mailing Address</h5>
                <p>410 Townsend Street, Suite 400 
                San Francisco, CA 94107</p>
            </li>
            <li class="contact-number">
                <i class="sprite"></i>
                <h5>Contact Number</h5>
                <p>USA - +410 21365489 </p>
                <p>CA - +410 21365489</p>
            </li>
        </ul>
    </section>       
</section>
@include('hopmedia::dashboard.signup')
@section('signup')
@show
<script src="{{$getHopmediaAssetsUrl('js/hopmedia.animate.js')}}"></script>