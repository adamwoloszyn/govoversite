<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Mobile-first responsive styles */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }
    </style>
</head>

<body>
    <table class="container" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <img src="path/to/header-logo.png" alt="Logo">
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <h2>Video Subscription</h2>
                            <p>Here are some interesting videos for you based on your subscription.</p>
                            <p>{!! $bodyContents !!}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center">
                            <p>
                                {{-- <a href="">Unsubscribe</a> from future emails. --}}
                            </p>
                            <p>
                                Government Oversight<br>
                                Address, City, State, ZIP<br>
                                Phone: (123) 456-7890<br>
                                Email: info@example.com
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
