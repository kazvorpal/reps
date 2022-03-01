<?php
//$to      = 'CCI-EPMSolutionsTeam@cox.com';
//$subject = 'MLM Daily Change Log';
//$message = 'This is the MLM Daily automated checkup process email.  This is and automated email for testing only and will end on 2/17/2020/ \r\n \r\n RePS Administration';
//$headers = 'From: CCI-EPMSolutionsTeam@cox.com' . "\r\n" .
//    'Reply-To: CCI-EPMSolutionsTeam@cox.com' . "\r\n" .
//    'X-Mailer: PHP/' . phpversion();
//
//mail($to, $subject, $message, $headers);
//?>

<?php
$to = 'gilbert.carolino@cox.com';
$subject = 'MLM Changes Need Approval';
$from = 'CCI-EPMSolutionsTeam@cox.com';
 
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
 
// Compose a simple HTML email message
$message = "<html>
<head>
  <meta name='viewport' content='width=device-width'>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  <title>Simple Transactional Email</title>
  <style>
  /* -------------------------------------
      INLINED WITH htmlemail.io/inline
  ------------------------------------- */
  /* -------------------------------------
      RESPONSIVE AND MOBILE FRIENDLY STYLES
  ------------------------------------- */
  @media only screen and (max-width: 620px) {
    table[class=body] h1 {
      font-size: 28px !important;
      margin-bottom: 10px !important;
    }
    table[class=body] p,
          table[class=body] ul,
          table[class=body] ol,
          table[class=body] td,
          table[class=body] span,
          table[class=body] a {
      font-size: 16px !important;
    }
    table[class=body] .wrapper,
          table[class=body] .article {
      padding: 10px !important;
    }
    table[class=body] .content {
      padding: 0 !important;
    }
    table[class=body] .container {
      padding: 0 !important;
      width: 100% !important;
    }
    table[class=body] .main {
      border-left-width: 0 !important;
      border-radius: 0 !important;
      border-right-width: 0 !important;
    }
    table[class=body] .btn table {
      width: 100% !important;
    }
    table[class=body] .btn a {
      width: 100% !important;
    }
    table[class=body] .img-responsive {
      height: auto !important;
      max-width: 100% !important;
      width: auto !important;
    }
  }

  /* -------------------------------------
      PRESERVE THESE STYLES IN THE HEAD
  ------------------------------------- */
  @media all {
    .ExternalClass {
      width: 100%;
    }
    .ExternalClass,
          .ExternalClass p,
          .ExternalClass span,
          .ExternalClass font,
          .ExternalClass td,
          .ExternalClass div {
      line-height: 100%;
    }
    .apple-link a {
      color: inherit !important;
      font-family: inherit !important;
      font-size: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
      text-decoration: none !important;
    }
    #MessageViewBody a {
      color: inherit;
      text-decoration: none;
      font-size: inherit;
      font-family: inherit;
      font-weight: inherit;
      line-height: inherit;
    }
    .btn-primary table td:hover {
      background-color: #34495e !important;
    }
    .btn-primary a:hover {
      background-color: #34495e !important;
      border-color: #34495e !important;
    }
  }
  </style>
</head>
<body class='' style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>
  <table border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;'>
    <tr>
      <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
      <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;'>
        <div class='content' style='box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;'>

          <!-- START CENTERED WHITE CONTAINER -->
          <span class='preheader' style='color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;'>Master List Updates</span>
          <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>
                <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                  <tr>
                    <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'><img src='http://catl0pwas10385.corp.cox.com/images/reps-clear.png' width='120' height='53' alt=''/></td>
                  </tr>
                  <tr>
                    <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>
                      <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>Master List Updates,</p>
                      <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>This is a list of changes to the existing Master List on RePS. Please make the appropriate changes to your tools and when completed please acknowledge the changes using the button below.</p>
                      <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'><strong>Cox Facilities Changes</strong></p>
                      <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>[List of Updates] </p>
                      <table border='0' cellpadding='0' cellspacing='0' class='btn btn-primary' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;'>
                        <tbody>
                          <tr>
                            <td align='left' style='font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;'>
                              <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;'>
                                <tbody>
                                  <tr>
                                    <td style='font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;'> <a href='#' target='_blank' style='display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;'>Acknowledge Changes</a> </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <p style='font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;'>This email was sent during Developement & QA testing. Please disregard the contents of this email.</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          <!-- END MAIN CONTENT AREA -->
          </table>

          <!-- START FOOTER -->
          <div class='footer' style='clear: both; Margin-top: 10px; text-align: center; width: 100%;'>
            <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
              <tr>
                <td class='content-block' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;'>
                  <span class='apple-link' style='color: #999999; font-size: 12px; text-align: center;'>Cox Communications, 6305 Peachtree Dunwoody Rd Sandy Springs, GA 30328</span>
                </td>
              </tr>
            </table>
          </div>
          <!-- END FOOTER -->

        <!-- END CENTERED WHITE CONTAINER -->
        </div>
      </td>
      <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
    </tr>
  </table>
</body>
</html>
";
 
// Sending email
if(mail($to, $subject, $message, $headers)){
    echo 'Your mail has been sent successfully.';
} else{
    echo 'Unable to send email. Please try again.';
}
?>