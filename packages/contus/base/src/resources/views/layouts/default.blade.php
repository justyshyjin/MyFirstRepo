<!DOCTYPE>
<html lang="en">
   @include('base::layouts.head')
    <body>
        <div id="preloader">
            <div id="status"><i></i></div>
        </div>
        <section>
        	<div id="st-container" class="st-container">
	            <!-- content push wrapper -->
	            <div class="st-pusher">
		            @include('base::layouts.sidebar')
		            <div class="mainpanel">
		                @yield('header')

		                @yield('content')
		            </div>
	            </div>
            </div>
        </section>
        @include('base::layouts.scripts')
        @section('scripts')
        @show
    </body>
</html>