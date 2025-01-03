<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"  "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <title>AA - Backoffice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="generator" content="BgreenTechnology">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <style>
      /* === EMAIL CLIENT STYLES === */
      #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
      .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
      .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
      body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
      table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
      img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
      /* == EMAIL STYLES == */
      body,table,[style*="Ubuntu"] {font-family: 'Ubuntu',Verdana,'Helvetica Neue',Arial,sans-serif!important;}
      hr {margin-top:30px;margin-bottom:30px;color:#ced4da;border-style:solid;border-width:1px;border-bottom-width:0;}
      a:link {text-decoration:none;}
      /* == MOBILE STYLES == */
      @media only screen and (max-width: 480px){
        body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:none !important;} /* Prevent Webkit platforms from changing default text sizes */
        body{width:100% !important; min-width:100% !important;margin:0!important; padding:0!important;} /* Prevent iOS Mail from adding padding to the body */
      }
      /* == DARK MODE == */
      @media (prefers-color-scheme: dark) {
        #emailHeader {background-color: #fff!important;border-bottom: 2px solid #e9ecef;}
        [bgColor="#fff"]{background-color: #fff!important;}
      }
    </style>
  </head>
  <body style="width:100%!important;min-width:100%!important;margin:0!important;padding:0!important;background-color:#e9ecef!important;">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="font-family: 'Ubuntu', Verdana, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #212529;table-layout: fixed;">
      <tbody>
        <tr>
          <td valign="top" align="center" id="bodyCell" style="height: 100% !important; margin: 0; padding: 0; width: 100% !important;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;margin-right:auto;margin-left:auto;">
              <tbody>
                <!-- HEADER -->
                <tr>
                  <td id="emailHeader" style="padding-top: 20px; padding-bottom: 20px; text-align: center;">
                    <div style="padding: 20px 0; background: #fff; margin: 20px 0">
                      <a href="https://dominio.com" title="Dominio.com"><img src="https://dominio.com/imgs/logo.png" alt="Dominio.com" width="200" height="78" /></a>
                    </div>
                  </td>
                </tr>
                <!-- END: HEADER -->

                <tr>
                  <td align="center">
                    <!-- LAYOUT -->
                    {{ content() }}
                    <!-- END: LAYOUT -->
                  </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                  <td style="font-size: 14px; line-height: 19px; text-align: center; padding-top: 20px; color: #6c757d;padding-right:15px;padding-left:15px;">
                    <a href="https://dominio.com" title="Dominio.com" style="color: #6c757d;"><u>https://dominio.com</u></a>
                    <span style="margin: 10px; display: inline-block;">|</span>
                    <a href="mailto:geral@dominio.com" title="geral@dominio.com" style="color: #6c757d;"><u>geral@dominio.com</u></a>
                  </td>
                </tr>
                
				<tr>
                  <td style="font-size: 12px; line-height: 15px; color: #6c757d; text-align: center">
				  	Para garantir que continua a receber e-mails, aconselhamos que adicione o <a href="mailto:geral@dominio.com" style="color: #6c757d">geral@dominio.com</a> ao seu livro de endereços. Consulte os termos de 
					<a href="https://dominio.com/politica-de-privacidade" title="Politica Privacidade" style="text-decoration: underline; color: #6c757d;">privacidade aqui</a>.
                  </td>
                </tr> 
				
                <!-- END: FOOTER -->
              </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ced4da" style="font-size: 11px; line-height: 30px; color: #6c757d; text-align: center; margin-top: 15px;padding-right:15px;padding-left:15px;">
              <tbody>
                <tr>
                  <td>E-mail automático, por favor não responder..</td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>