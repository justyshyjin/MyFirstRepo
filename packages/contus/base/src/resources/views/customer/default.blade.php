<!DOCTYPE>
<html lang="en">
@include('base::customer.head')
<body ng-app="app" ng-cloak>
    @include('base::customer.header')
    @yield('header')
    @yield('content')
    @include('base::customer.footer')
    @include('base::customer.scripts')
    @section('scripts')
    @show
</body>
</html>