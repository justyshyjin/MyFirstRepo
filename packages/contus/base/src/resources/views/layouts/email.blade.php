<!DOCTYPE html>
<!-- saved from url=(0021)http://ibidapps.com/# -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
    <body>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family: arial, sans-serif;font-size: 14px;color:

#31353b;">
            <tbody>
                <tr>
                    <td colspan="2" style="padding: 0;line-height: 0px;">
                        <img src="{{env('EMAIL_URL')}}/contus/base/images/email/footer-image.png" style="width: 100%">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 20px;">
                        <img src="{{env('EMAIL_URL')}}/contus/base/images/email/logo.png">
                    </td>
                    <td style="text-align: right;padding: 20px;" display:="" inline-block;vertical-align:="" middle;"="">Connect with us
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.facebook_url' )}}" style="display: inline-block; vertical-align: middle;">
                            <img src="{{env('EMAIL_URL')}}/contus/base/images/email/fb.png">
                        </a>
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.twitter_url' )}}" style="display: inline-block;vertical-align: middle;">
                            <img src="{{env('EMAIL_URL')}}/contus/base/images/email/twitter.png">
                        </a>
                        <a href="{{config ()->get ( 'settings.general-settings.site-settings.googleplus_url' )}}" style="display: inline-block; vertical-align: middle;">
                            <img src="{{env('EMAIL_URL')}}/contus/base/images/email/g+.png">
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 20px;background: #EFEFEF;font-size: 15px;" colspan="2">
                        <div style="max-width: 90%; margin: auto">
                            {!!$content!!}

                            <p style="color: #f16d48;margin: 40px 0 5px;font-weight: bold;">Regards,</p>
                            <p style="color: #333;margin: 0;font-weight: bold;">{{config ()->get ( 'settings.general-settings.site-settings.site_name' )}} Team</p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td height="15"></td>
                </tr>

                <tr>
                    <td style="padding: 20px 20px;background: #fff;font-size: 16px;" colspan="2">
                        <div style="max-width: 263px;margin: auto;text-align: center;font-size: 15px;">
                            <p style=" margin: 0 0 9px; color: #000; font-size: 15px; font-weight: bold;">Learn to go</p>
                            <p style=" margin: 0 0 15px; line-height: 150%; font-size: 12px; color: #aaa;">access your courses anywhere,anytime by a single tap in the mobile</p>
                            <a href="{{config ()->get ( 'settings.general-settings.site-settings.apple_appstore_url' )}}" style=" display: inline-block; margin: 0 3px;">
                                <img src="{{env('EMAIL_URL')}}/contus/base/images/email/apple.png">
                            </a>
                            <a href="{{config ()->get ( 'settings.general-settings.site-settings.google_playstore_url' )}}" style="display: inline-block;">
                                <img src="{{env('EMAIL_URL')}}/contus/base/images/email/google.png">
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
