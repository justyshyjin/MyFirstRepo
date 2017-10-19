<section class="features">
    <section class="banner">
        <div class="container">
            <h2>Simple enough for beginners, powerful enough for professionals.</h2>
        </div>
        <img src="{{$getHopmediaAssetsUrl('images/features-banner.png')}}">
    </section>


    <section class="sit-relax">
        <section class="container">
            <h3>Just sit back relax and with 1-click, watch us cast a spell !</h3>
            <p>My process starts by first indentifying your company’s key strengths and developing a plan around them.</p>

            <ul class="advantages-lists">
                <li>
                    <i class="sprite own-domain-icon"></i>
                    <h3>Use Your Own Domain</h3>
                    <p>Your domain stands only for your brand. Ours doesn’t tailgate your brand name in the domain.</p>
                </li>
                <li>
                    <i class="sprite pay-per-video-icon"></i>
                    <h3>Pay Per Video</h3>
                    <p>Set up lucrative ‘Pay Per Video’ SVOD plans and monetize through every video for every view.</p>
                </li>
                <li>
                    <i class="sprite video-report-icon"></i>
                    <h3>Video Report</h3>
                    <p>Analyze analytical reports on viewership, avg time on video and crucial user-behavioral data.</p>
                </li>
                <li>
                    <i class="sprite launch-instantly-icon"></i>
                    <h3>Launch Instantly</h3>
                    <p>You are just 3 steps away from launching your own VOD website when you choose Hopmedia.</p>
                </li>
                <li>
                    <i class="sprite cloud-hosting-cdn-icon"></i>
                    <h3>Cloud hosting & CDN</h3>
                    <p>Distribute contents globally and make it available to requests from different parts of the world.</p>
                </li>
                <li>
                    <i class="sprite integration-icon"></i>
                    <h3>Integration</h3>
                    <p>Leverage on integrated tools available for purposes like reporting, transactions, accounting etc.,</p>
                </li>
            </ul>
        </section>
    </section>    

    <section class="broadcast-stream">
        <section class="container clearfix">
           <section class="broadcast-stream-section">
                <section class="content">
                <i class="sprite broadcast-icon"></i>
                    <h4>BROADCAST TO ANYONE</h4>
                    <p>Choose your target audience and place video assets in the market where they thrive the most.</p>
                    <a href="javascript:void(0)">Read More</a>
                </section>
           </section>

           <section class="broadcast-stream-section">
                <section class="content">
                <i class="sprite stream-icon"></i>
                    <h4>STREAM & SHARE SECURELY</h4>
                    <p>Your contents are immunized from hacks and tamperings with advanced encryption.</p>
                    <a href="javascript:void(0)">Read More</a>
                </section>
           </section>
        </section>
    </section> 

    <section class="responsive-design">
        <section class="container clearfix">

            <section class="sec-left">
                <h2>Responsive Design</h2>
                <p>
                    Hop Media offers a fully featured website with pre built elegant and
                    responsive designs/templates that are easily accessible and viewable
                    through any <span>Desktops, Laptops, Tablets, Mobiles</span> as well
                    as any other devices running a HTML5 compati ble browser.. <a href="#">Read More</a>
                </p>
                <ul>
                    <li><i class="sprite list-icon"></i>Use your own Domain</li>
                    <li><i class="sprite list-icon"></i>Pre-loaded Templates</li>
                    <li><i class="sprite list-icon"></i>Complete White Labelled</li>
                    <li><i class="sprite list-icon"></i>Easy Customization</li>
                    <li><i class="sprite list-icon"></i>Bring Your Own Design</li>
                </ul>
            </section>

            <section class="sec-right">
                <img src="{{$getHopmediaAssetsUrl('images/responsive-design.png')}}" alt="Smiley face">
            </section>
        </section>
    </section>
    @include('hopmedia::dashboard.testimonial')
    @section('testimonial') 
    @show
    @include('hopmedia::dashboard.signup')
    @section('signup')
    @show
    <script src="{{$getHopmediaAssetsUrl('js/hopmedia.animate.js')}}"></script>
