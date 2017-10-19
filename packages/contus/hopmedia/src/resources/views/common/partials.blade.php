<!DOCTYPE html>
<html lang="en">
    @include('hopmedia::common.head')
    @yield('head')
    <body ng-app="app" ng-cloak>
        @include('hopmedia::common.header')
        @yield('header')
    
        @yield('content')
 
        @include('hopmedia::common.footer')
        @yield('footer')

        @include('base::customer.scripts')
        @section('scripts')
        @show
    </body>
</html>