<head>
    <title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="{{config ()->get ('settings.general-settings.site-settings.page_description')}}">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-api-url" content="{{ url('api/v1') }}">
    <meta name="base-template-url" content="{{url('/')}}">
    <meta name="public-access-token" content="8YZKroRBFPV0aX0Hz9YTydI6gZq5pu">
    @if($auth->check() && $authUser = $auth->user())
    <meta name="access-token" content="{{$authUser->access_token}}">
    <meta name="user-id" content="{{$authUser->id}}">
    @endif
      <link rel="shortcut icon" href="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.favicon' )}}">
    <!--[if lt IE 9]>
    <script src="{{$getBaseAssetsUrl('js/html5shiv.js')}}"></script>
    <![endif]-->
    <!-- style-->
    <link href="{{$getBaseAssetsUrl('css/normalize.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/owl.carousel.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/countdown.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/customize.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/jquery.mCustomScrollbar.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/angular-socialshare.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/css/loading-bar.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/toast/ngToast-animations.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/toast/ngToast.min.css')}}" type="text/css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/hopmediastyles.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/hopmediacustom.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/hopmediaresponsive.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/owl.carousel.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css">
    @section('stylesheet')
    @show
    </head>