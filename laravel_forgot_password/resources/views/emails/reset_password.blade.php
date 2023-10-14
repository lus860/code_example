<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta property="og:title" content="Zaim">
    <meta property="fb:page_id" content="43929265776">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="referrer" content="origin">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
        }

        h1, h2, h3, h4, h5, h6 {
            display: block;
            margin: 0;
            padding: 0;
        }

        a {
            color: inherit;
            font-family: inherit;
            text-decoration: none;
        }

        img, a img {
            border: 0;
            height: auto;
            outline: none;
            text-decoration: none;
        }

        body, #bodyTable, #bodyCell {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        #outlook a {
            padding: 0;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        p, a, li, td, blockquote {
            mso-line-height-rule: exactly;
        }

        a[href^=tel], a[href^=sms] {
            color: inherit;
            cursor: default;
            text-decoration: none;
        }

        p, a, li, td, body, table, blockquote {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .ExternalClass, .ExternalClass p, .ExternalClass td, .ExternalClass div, .ExternalClass span, .ExternalClass font {
            line-height: 100%;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .TextContent img {
            height: auto !important;
        }

        body, #bodyTable {
            background-color: #ffffff;
        }

        h1 {
            color: #202020;
            font-family: Helvetica;
            font-size: 26px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }

        h2 {
            color: #202020;
            font-family: Helvetica;
            font-size: 22px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }

        h3 {
            color: #202020;
            font-family: Helvetica;
            font-size: 20px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }

        h4 {
            color: #202020;
            font-family: Helvetica;
            font-size: 18px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }

        @media only screen and (max-width: 600px) {
            .img-width {
                max-width: 100%;
            }
        }

        @media only screen and (max-width: 480px) {
            body, table, td, p, a, li, blockquote {
                -webkit-text-size-adjust: none !important;
            }

            body {
                width: 100% !important;
                min-width: 100% !important;
            }
        }

        @media only screen and (max-width: 575px) {
            .email-template {
                padding: 95.56px 5px 105px !important;
            }
        }
    </style>
</head>
<body>
<center>
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable"
           style="font-family: Century Gothic,Calibri,Helvetica,Arial,sans-serif; border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;height: 100%;padding: 0;width: 100%; max-width: 600px !important; background-color: #FFFFFF; border: solid 1px #EAE4F4; border-radius: 5px; box-shadow: 0 0 15px #CEB9E829;">
        <tbody>
        <tr>
            <td class="email-template" style="padding: 30px 50px 30px;">
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <td style="padding-bottom: 30px;">
                            <img src="{{ $message->embed(public_path('/image').'/logo.svg') }}" alt="dear friend"
                                 width="86" height="28">
                        </td>
                    </tr>
                    <tr style="display: block;">
                        <td>
                            <h2 style="font-size: 30px; font-weight: 500; color: #100841">
                                Hello!
                            </h2>
                        </td>
                    </tr>
                    <tr style="display: block; border-top: solid 1px #EAE4F4;">
                        <td style="padding: 20px 0 35px;">
                            <p style="font-size: 16px; color: #100841; width: 100%">
                                {{ __('You are receiving this email because we received a password reset request for your account.') }}
                            </p>
                        </td>
                    </tr>

                    <tr style="display: block; border-top: solid 1px #EAE4F4; border-bottom: solid 1px #EAE4F4; border-bottom: solid 1px #EAE4F4;">
                        <td style="padding: 15px 0; display: block;">
                            <p style="font-size: 14px; color: #655D96; text-align: center;">
                                <button style="background-color: #6255FF; border-radius: 4px; color: #fff; font-weight: 600; font-size: 15px; width: 153px; height: 38px; cursor: pointer; box-sizing: border-box;
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; border: none">
                                    <a style="text-decoration: underline;" href="{{ $url }}">{{ __('Reset Password') }}</a>
                                </button>
                            </p>
                        </td>
                    </tr>
                    <tr style="display: block;">
                        <td style="padding: 35px 0 35px;">
                            <p style="font-size: 16px; color: #100841">
                                {{ __('This password reset link will expire in') }} {{ $expire }} {{ __('minutes.') }}
                            </p>
                            <p style="font-size: 16px; color: #100841; padding: 35px 0 0;">
                                {{ __('If you did not request a password reset, no further action is required.') }}
                            </p>
                        </td>
                    </tr>
                    <tr style="display: block;">
                        <td style="display: block;">
                            <p style="color: #343350; font-size: 16px;">
                                {{ __('If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:') }}
                            </p>
                            <br>
                            <p style="font-size: 14px; color: #655D96; line-break: anywhere;">
                                {{ $url }}
                            </p>
                            <br>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="border-top: solid 1px #EAE4F4;">
            <td style="background-color: rgba(234, 228, 244, 0.15); padding: 36px 0 38px;">
                <p style="color: #655D96; font-size: 12px; text-align: center;">
                    If you’ve received this email in error, please forward it immediately
                </p>
                <p style="color: #655D96; font-size: 12px; text-align: center;">
                    Sent: <?php $EasternTimeStamp = mktime(date('H')-6,date('i'),date('s'),date("m"),date("d"),date("Y")); echo date("F j, Y H:i", $EasternTimeStamp) ?> EDT
                </p>
            </td>
        </tr>
        </tbody>
    </table>
</center>
</body>
</html>
