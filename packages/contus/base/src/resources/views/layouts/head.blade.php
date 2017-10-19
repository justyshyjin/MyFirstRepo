<head>
    <title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="{{config ()->get ( 'settings.general-settings.site-settings.page_description' )}}">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-api-url" content="{{ url('api/admin') }}">
    <meta name="base-template-url" content="{{URL::to('/')}}">
    <meta name="public-access-token" content="8YZKroRBFPV0aX0Hz9YTydI6gZq5pu">
    @if($auth->check() && $authUser = $auth->user())
        <meta name="access-token" content="{{$authUser->access_token}}">
        <meta name="user-id" content="{{$authUser->id}}">
    @endif
    <link rel="shortcut icon" href="{{asset('assets/images').'/'.config( 'settings.general-settings.site-settings.favicon' )}}">
    <!--[if lt IE 9]>
	<!--<script src="{{$getBaseAssetsUrl('js/html5shiv.js')}}"></script>-->
	<![endif]-->
    <!-- style-->
    <link href="{{$getBaseAssetsUrl('css/bootstrap.min.css')}}" type="text/css" rel="stylesheet">
    <link href="{{$getBaseAssetsUrl('css/admin/style.css?v=')}}{{env('ASSERT_VERSION',time())}}" type="text/css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    @section('stylesheet')
    @show
</head>
