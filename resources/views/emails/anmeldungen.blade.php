<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- Yahoo App Android will strip this -->
</head>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320, initial-scale=1" />
    <title>Lets Plan Einladung</title>

    <style type="text/css">
        /* ----- Client Fixes ----- */
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap');

        /* @import url('http://fonts.cdnfonts.com/css/nexa-bold'); */


        @font-face {
            font-family: 'Nexa';
            src: url('{{ asset('fonts/nexa') }}/Nexa-Bold.eot');
            src: url('{{ asset('fonts/nexa') }}/Nexa-Bold.eot?#iefix') format('embedded-opentype'),
                url('{{ asset('fonts/nexa') }}/Nexa-Bold.woff2') format('woff2'),
                url('{{ asset('fonts/nexa') }}/Nexa-Bold.woff') format('woff'),
                url('{{ asset('fonts/nexa') }}/Nexa-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Nexa';
            src: url('{{ asset('fonts/nexa') }}/Nexa-Regular.eot');
            src: url('{{ asset('fonts/nexa') }}/Nexa-Regular.eot?#iefix') format('embedded-opentype'),
                url('{{ asset('fonts/nexa') }}/Nexa-Regular.woff2') format('woff2'),
                url('{{ asset('fonts/nexa') }}/Nexa-Regular.woff') format('woff'),
                url('{{ asset('fonts/nexa') }}/Nexa-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }



        /* Force Outlook to provide a "view in browser" message */
        #outlook a {
            padding: 0;
        }

        /* Force Hotmail to display emails at full width */
        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        /* Force Hotmail to display normal line spacing */
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }


        /* Prevent WebKit and Windows mobile changing default text sizes */
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* ----- Reset ----- */

        /* html, */
        body,
        .body-wrap,
        .body-wrap-cell {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 14px;
            color: #464646;
            text-align: left;
            font-family: 'Nexa', sans-serif;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        td,
        th {
            text-align: left;
            font-family: 'Nexa', sans-serif;
            font-size: 14px;
            color: #464646;
            line-height: 1.5em;
        }

        b a,
        .footer a {
            text-decoration: none;
            color: #464646;
        }

        a.blue-link {
            color: blue;
            text-decoration: underline;
        }

        /* ----- General ----- */

        td.center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .body-padding {
            /* padding: 24px 40px 40px; */
            margin: 0;
            padding: 0;
        }

        .border-bottom {
            /* border-bottom: 1px solid #D8D8D8; */
        }

        table.full-width-gmail-android {
            width: 100% !important;
        }


        /* ----- Header ----- */
        .header {
            font-weight: bold;
            font-size: 16px;
            line-height: 16px;
            height: 16px;
            padding-top: 19px;
            padding-bottom: 7px;
        }

        .header a {
            color: #464646;
            text-decoration: none;
        }

        /* ----- Body ----- */

        .body .body-padded {
            padding-top: 34px;
        }

        .body-thanks-cell {
            padding: 25px 0 10px;
        }

        .body-signature-cell {
            padding: 0 0 30px;
        }

        /* ----- Footer ----- */

        .footer a {
            font-size: 12px;
        }


        /* ----- Soapbox ----- */

        .soapbox .soapbox-title {
            text-align: center;
            font-size: 30px;
            padding-bottom: 20px;
            color: #464646;
        }

        /* ----- Status ----- */

        .status {
            border-collapse: collapse;
            margin-left: 15px;
            color: #656565;
        }

        .status .status-cell {
            border: 1px solid #b3b3b3;
            height: 50px;
            text-align: center;
            font-size: 15px;
            padding: 0 40px;
        }

        .status .status-cell.success,
        .status .status-cell.active {
            height: 65px;
        }

        .status .status-cell.success {
            background: #f2ffeb;
            color: #51da42;
        }

        .status .status-cell.active {
            background: #fffde0;
            width: 135px;
        }

        .status .status-title {
            font-size: 16px;
            font-weight: bold;
            line-height: 23px;
        }

        .status .status-image {
            vertical-align: text-bottom;
        }

        main-content {
            padding-top: 60px;
            padding-bottom: 25px;
            padding-left: 40px;
            padding-right: 40px;
        }
    </style>

    <style type="text/css" media="only screen">
        @media only screen and (max-width: 505px) {

            *[class*="w320"] {
                width: 320px !important;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active {
                display: block !important;
                width: auto !important;
            }

            table[class="status-container single"] table[class="status"] {
                width: 270px !important;
                margin-left: 0;
            }

            table[class="status"] td[class*="status-cell"],
            table[class="status"] td[class*="status-cell"].active,
            table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 65px !important;
                font-size: 18px !important;
                padding: 0 15px !important;
            }

            table[class="status-container single"] table[class="status"] td[class*="status-cell"],
            table[class="status-container single"] table[class="status"] td[class*="status-cell"].active,
            table[class="status-container single"] table[class="status"] td[class*="status-cell"] [class*="status-title"] {
                line-height: 51px !important;
            }

            table[class="status"] td[class*="status-cell"].active [class*="status-title"] {
                display: inline !important;
            }

            td[class="main-content"] {
                padding-top: 20px !important;
                padding-bottom: 15px !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
            }


        }
    </style>

    <style type="text/css" media="only screen and (max-width: 650px)">
        @media only screen and (max-width: 650px) {
            * {
                font-size: 16px !important;
            }

            .header-title {
                font-size: 24px !important;
                line-height: 30px !important;
            }

            .full-width-gmail-android {
                margin-bottom: 0px !important;
            }


            table[class*="w320"] {
                width: 320px !important;
            }

            table[class*="main-table"] {
                margin: 0 auto !important;
            }

            td[class="mobile-center"],
            div[class="mobile-center"] {
                text-align: center !important;
            }

            td[class*="body-padding"] {
                padding: 20px !important;
            }

            td[class="mobile"] {
                text-align: right;
                vertical-align: top;
            }
        }
    </style>

</head>

<body style="padding:0; margin:0; display:block; background:#063F5F;; -webkit-text-size-adjust:none;">
    <table height="40px;">
        <tr>
            <td></td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0"
        style="width:500px; margin: 0 auto;border-radius:0;background:#FAFCFF;">
        <tbody>
            <tr>
                <td class="main-content" align="left" valign="top" width="100%"
                    style="padding: 40px 40px 42px 72px;">
                    <center>
                        <table style="width: 100%;" width="100">
                            <tr>
                                <td>
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                        style="width: 52px; display: inline-block; vertical-align: top;" width="30%">
                                        <tr>
                                            <td>
                                                <img src="{{ asset('images/dashboard_icon.png') }}" alt=""
                                                    height="60px" width="52px" />

                                            </td>
                                        </tr>
                                    </table>
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                        style="width: 56%; display: inline-block; vertical-align: top;" width="30%">
                                        <tbody style="display: table; width: 100%;">

                                            <tr>
                                                <td
                                                    style="text-align: center; box-sizing: content-box;padding-left: 37px;">
                                                    <span>
                                                        <img style="margin-right: auto; margin-left:auto;"
                                                            src="{{ asset('images/logo_email.png') }}" alt=""
                                                            height="100" />
                                                    </span>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                        style="width: max-content; display: inline-block; vertical-align: top;"
                                        width="30%">
                                        <tr>
                                            <td>
                                                <img src="{{ asset('images/lets_play_logo.png') }}" alt=""
                                                    height="52px" width="122px" />

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table class="w320 full-width-gmail-android" style="background-color: transparent;"
                            border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f9f8f8">
                            <tbody>
                                <tr>
                                    <td valign="top" width="100%">
                                        <!-- [if gte mso 9]>
            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:49px;">
              {{--  <v:fill type="tile" src="https://www.filepicker.io/api/file/al80sTOMSEi5bKdmCgp2" color="#ffffff" />  --}}
              <v:textbox inset="0,0,0,0">
            <![endif]-->
                                        {{-- <table class="full-width-gmail-android" border="0" width="100%" cellspacing="0"
                                            cellpadding="0" style="margin: 0px 0px 40px 0px;">
                                            <tbody>
                                                <tr>
                                                    <td class="header center" width="100%" style="margin: 0;padding:0;">
                                                        <img src="{{ asset('images/logo_email.png') }}" alt="" style="margin-bottom: 80px;" height="100" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="header" width="100%" style="margin: 0;padding:0;">
                                                        <h1 class="header-title" style="margin:0;padding:0;font-family: 'Ubuntu';font-style: normal;font-weight: 700;font-size: 35px;line-height: 40px;color: #063F5F;">Wichtige Informationen - Spielgruppe</h1>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
                                        <!-- [if gte mso 9]>
              </v:textbox>
            </v:rect>
            <![endif]-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 95.3808%;" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr style="height: 10px;">
                                    <td style="width: 100%; padding-left: 20px;" align="center">
                                        <center>
                                            <table class="w320" style="width: 420px; " width="500" cellspacing="0"
                                                cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td class="body-padding mobile-padding" style="width: 433px;">
                                                            <table class="soapbox" style="width: 100%;"
                                                                width="100%" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr style="height: 10px;">
                                                                        <td align="left"
                                                                            style="margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color:#135F84;">
                                                                            <h1 align="left" class="header-title"
                                                                                style="text-align: left; margin: 80px 0px 24px 0px ;padding:0;font-family: 'Ubuntu';font-style: normal;font-weight: 700;font-size: 35px;line-height: 40px;color: #063F5F;">
                                                                                Wichtige Informationen <br />-
                                                                                Spielgruppe
                                                                            </h1>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="soapbox" style="width: 100%;"
                                                                width="100%" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr style="height: 10px;">
                                                                        <td align="left"
                                                                            style="margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color:#135F84;">
                                                                            <p
                                                                                style="margin: 0px 0px 0px 0px padding:0;font-family: 'Nexa'; font-style: bold; font-weight: 700; font-size: 18px; line-height: 24px; color: #063F5F;">
                                                                                Bitte NICHT auf diese E-Mail antworten
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="body"
                                                                style="height: 100px;background:transparent;border:none;width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="body-padded" style="padding:0;">
                                                                            <table class="body-text" align="">
                                                                                <tbody>
                                                                                    <tr style="">
                                                                                        <td class="body-text-cell"
                                                                                            style="">
                                                                                            <p
                                                                                                style="margin:40px 0px 24px 0px;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color: #135F84;">
                                                                                                Liebe Eltern
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="body-text" align="">
                                                                                <tbody>
                                                                                    <tr style="">
                                                                                        <td class="body-text-cell"
                                                                                            style="">
                                                                                            <p
                                                                                                style="margin:0px 0px 40px 0px;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 400; font-size: 16px; line-height: 24px; color: #135F84;">
                                                                                                Anbei senden wir Ihnen
                                                                                                drei Anhänge mit
                                                                                                wichtigen
                                                                                                Spielgruppen-Informationen.
                                                                                                Bitte lesen Sie diese
                                                                                                aufmerksam durch.
                                                                                                Den Anhang “Wichtige
                                                                                                Informationen für
                                                                                                SpielgruppenleiterIn”
                                                                                                bitten wir
                                                                                                Sie auszudrucken,
                                                                                                auszufüllen und am
                                                                                                ersten
                                                                                                Spielgruppenhalbtag mit
                                                                                                in die Spielgruppe zu
                                                                                                bringen.
                                                                                                Weitere Informationen
                                                                                                erhalten Sie
                                                                                                auf dem Postweg.
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="body-text" align="">
                                                                                <tbody>
                                                                                    <tr style="">
                                                                                        <td class="body-text-cell"
                                                                                            style="">
                                                                                            <p
                                                                                                style="margin:0px 0px 40px 0px;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 400; font-size: 13px; line-height: 20px; color: #135F84;">
                                                                                                Bei Fragen können Sie
                                                                                                uns gerne über folgende
                                                                                                E-Mail Adresse
                                                                                                erreichen:
                                                                                                <a rel="nofollow"
                                                                                                    style='text-decoration:none; color:#E97D32'>spielgruppen@familienforum-rj.ch</a>
                                                                                                {{-- <span
                                                                                                    style="color:#E97D32;">spielgruppen@familienforum-rj.ch</span> --}}
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="soapbox"
                                                                                style="width: 100%;" width="100%"
                                                                                cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr style="height: 10px;">
                                                                                        <td class="soapbox-title"
                                                                                            style="margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 18px; line-height: 24px; display: flex;align-items: center;color:#135F84;">
                                                                                            <p
                                                                                                style="margin:0px 0px 0px 0px;padding:0;font-family: 'Nexa'; font-style: bold; font-weight: 700; font-size: 16px; line-height: 24px; color: #063F5F;">
                                                                                                Besten Dank und
                                                                                                freundliche Grüsse
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="soapbox"
                                                                                style="width: 100%;" width="100%"
                                                                                cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr style="height: 10px;">
                                                                                        <td class="soapbox-title"
                                                                                            style="margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 18px; line-height: 24px; display: flex;align-items: center;color:#E97D32;">
                                                                                            <p
                                                                                                style="margin:0px 0px 40px 0px;">
                                                                                                Das Familien Forum
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="body-text"
                                                                                style="margin-bottom:20px;"
                                                                                align="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td><img src="{{ asset('images/logo_pdf.png') }}"
                                                                                                style="margin-right:16px;" />
                                                                                        </td>
                                                                                        <td>
                                                                                            <a href="https://drive.google.com/file/d/19EzxEuk0Ffi6vNd0JEICHmwR2fOUyajD/view?usp=sharing"
                                                                                                style="text-decoration:none;margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color: #2C5B7D;">
                                                                                                Generelle Informationen
                                                                                                für Spielgruppeneltern
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table
                                                                                class="body-text"style="margin-bottom:20px;"
                                                                                align="">
                                                                                <tbody>
                                                                                    <tr style="">
                                                                                        <td><img src="{{ asset('images/logo_pdf.png') }}"
                                                                                                style="margin-right:16px;" />
                                                                                        </td>
                                                                                        <td>
                                                                                            <a href="https://drive.google.com/file/d/1S1YGOo2lWDXAbV_x2j-mInhXvqdsTNLe/view?usp=sharing"
                                                                                                style="text-decoration:none;margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color: #2C5B7D;">
                                                                                                Uebergangssituationen
                                                                                                bei Kindern
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <table class="body-text" align="">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td><img src="{{ asset('images/logo_pdf.png') }}"
                                                                                                style="margin-right:16px;" />
                                                                                        </td>
                                                                                        <td>

                                                                                            <a href="https://drive.google.com/file/d/1F3LtiHQ8UHuMQUfxiScF0AUDYxcEjRds/view?usp=sharing"
                                                                                                style="text-decoration:none;margin:0;padding:0;font-family: 'Nexa'; font-style: normal; font-weight: 700; font-size: 16px; line-height: 24px; color: #2C5B7D;">
                                                                                                Wichtige Informationen
                                                                                                für SpielgruppenleiterIn
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </center>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
    <table height="40px;">
        <tr>
            <td></td>
        </tr>
    </table>
</body>

</html>
