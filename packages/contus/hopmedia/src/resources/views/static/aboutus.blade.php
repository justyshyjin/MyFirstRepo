<section class="about-us">
    <section class="banner">
        <div class="container">
            <h2>Find the best video streaming platform</h2>
            <p>Simple pricing as it should be. Includes everything.</p>
        </div>
    </section>


    <section class="container">
        <ul class="clearfix">
            <li class="clearfix">
                <section class="content">
                    <h2 class="heading">About Hop media</h2>
                    <p>Hop media, an IBM Company, is a leading provider of cloud-based, end-to-end video solutions for media and enterprises. From internal meetings to press conferences to worldwide entertainment events, Hop media powers live and on-demand video for 80 million viewers per month. </p>

                    <p>Hop media was founded in 2007 and is located in San Francisco and Budapest. In January 2016, Hop media was acquired by IBM and is now part of IBM Cloud Video services.</p>
                </section>
                <section class="image">
                    <img src="{{$getHopmediaAssetsUrl('images/about-hop-media.jpg')}}">
                </section>
            </li>
            <li class="clearfix">
                <section class="content">
                    <h2 class="heading">Our Goal</h2>
                    <p>Hop media’s suite of products includes Hop media Align, for secure internal video communications to employees, and Hop media Pro Broadcasting, for broadcasters and marketers who need a highly scalable and reliable live video streaming platform to reach massive external audiences. Hop media customers include <span>NASA, Samsung, Facebook, Nike and 
                    <span class="block">Discovery Communications.</span></span> </p>
                </section>
                <section class="image">
                    <img src="{{$getHopmediaAssetsUrl('images/our-goal.jpg')}}">
                </section>
            </li>
        </ul>
    </section>

    <section class="learn-what-to-do">
        <section class="container">
            <h2 class="heading">Learn what we do</h2>
            <p>Some general questions with regards to Pricing</p>

            <img src="{{$getHopmediaAssetsUrl('images/learn-what-to-do.png')}}" />
        </section>
    </section>            
</section>
@include('hopmedia::dashboard.signup')
@section('signup')
@show
<script src="{{$getHopmediaAssetsUrl('js/hopmedia.animate.js')}}"></script>