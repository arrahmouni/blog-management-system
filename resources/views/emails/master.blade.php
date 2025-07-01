<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>html,body { padding: 0; margin:0;} .h-100{height: 100px;} .w-20{width: 20px;}</style>
    </head>
    <body>
        <div style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                <tbody>
                    <tr>
                        <td align="center" valign="center" style="text-align:center; padding: 40px">
                            <a href="#" rel="noopener">
                                <img alt="Logo" class="h-100" src="{{ asset('images/default/logos/app_logo.svg') }}" />
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="center">
                            <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                <!--begin:Email content-->
                                @yield('content')
                                <!--end:Email content-->


                                <!--begin:Email footer-->
                                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 12px; color: #7b7b7b; text-align: center">
                                    <p style="margin: 0; padding: 0">
                                        {{ config('app.name') }} &copy; {{ date('Y') }}. All rights reserved
                                    </p>
                                </div>

                                <!-- Social media links -->
                                <div class="social-icons" style="display:flex; justify-content:space-evenly; padding-top: 20px;">
                                    <a href="https://facebook.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/facebook.svg') }}" alt="facebook">
                                    </a>
                                    <a href="https://twitter.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/twitter.svg') }}" alt="twitter">
                                    </a>
                                    <a href="https://linkedin.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/linkedin.svg') }}" alt="linkedin">
                                    </a>
                                    <a href="https://instagram.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/instagram.svg') }}" alt="instagram">
                                    </a>
                                    <a href="https://youtube.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/youtube.svg') }}" alt="youtube">
                                    </a>
                                    <a href="https://tiktok.com" target="_blank">
                                        <img class="w-20" src="{{ asset('images/social-logos/tiktok.svg') }}" alt="tiktok">
                                    </a>
                                </div>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
