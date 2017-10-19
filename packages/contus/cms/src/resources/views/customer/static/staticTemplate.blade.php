<!DOCTYPE>
<html lang="en">
<head>
<title>{{config ()->get ( 'settings.general-settings.site-settings.page_title' )}}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link href="{{$getBaseAssetsUrl('css/common.css?v=')}}{{env('ASSERT_VERSION',time())}}" type="text/css" rel="stylesheet">
</head>
<body>
    <section class="contact-us">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{$data->title}}</h2>
                    <p class="static-content">{!!$data->content!!}</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
