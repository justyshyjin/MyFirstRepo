@section('head')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-api-url" content="{{ url('hopmedia/api/v1') }}">
    <meta name="base-template-url" content="{{url('/')}}">
    <link rel="shortcut icon" href="{{$getBaseAssetsUrl('images/fav.png')}}">
    <link href="{{$getHopmediaAssetsUrl('css/common.css')}}" rel="stylesheet">
    <link href="{{$getHopmediaAssetsUrl('css/style.css')}}" rel="stylesheet">
    <link href="{{$getHopmediaAssetsUrl('css/responsive.css')}}" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/jquery.mCustomScrollbar.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/angular-socialshare.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/css/loading-bar.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/toast/ngToast-animations.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('angular/libs/toast/ngToast.min.css')}}" type="text/css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/hopmediastyles.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/hopmediaresponsive.css')}}">
    <link rel="stylesheet" href="{{$getBaseAssetsUrl('css/owl.carousel.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css">
</head>
@endsection('head')
