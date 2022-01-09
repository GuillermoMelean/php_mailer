<?php


function funSendEmail($vfArrayValues){

    date_default_timezone_set('Etc/UTC');
    require '../../config/mail/PHPMailerAutoload.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer();
    //Tell PHPMailer to use SMTP $mail->isSMTP();
    $mail->IsSMTP();
    //Enable SMTP debugging
      // 0 = off (for production use)
      // 1 = client messages
      // 2 = client and server messages
    $mail->SMTPDebug = MAIL_DEBUG;
    //$mail->DKIM_domain = '127.0.0.1';
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = MAIL_DEBUGOUTPUT;
    //Set the hostname of the mail server
    $mail->Host = MAIL_HOST;
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = MAIL_PORT;
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    $mail->SMTPOptions = array(
      'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
      )
    );
    //Username to use for SMTP authentication
    $mail->Username = MAIL_SMTP_AUTH_USER;
    //Password to use for SMTP authentication
    $mail->Password = MAIL_SMTP_AUTH_PW;
    $mail->SMTPSecure = MAIL_SMTP_SECURE;
    $mail->From= MAIL_EMAIL_FROM;
    //Set who the message is to be sent from
    //$mail->setFrom(MAIL_EMAIL_FROM, MAIL_FROM_NAME,FALSE);
    $mail->FromName = MAIL_FROM_NAME;
    $mail->CharSet = 'UTF-8';
    $BCC_USERS = explode(';',BCC_USERS);
    foreach($BCC_USERS as $email) {
        $mail->addBcc($email);
    }


    //Set an alternative reply-to address
    $mail->addReplyTo(MAIL_EMAIL_FROM, MAIL_FROM_NAME);

    $viEmailClient = $vfArrayValues['emailClient'];
    $viNameClient = $vfArrayValues['nameClient'];
    $viEmailBody = $vfArrayValues['emailBody'];
    $viEmailSub= $vfArrayValues['emailSubject'];
    //$viPathAttachment = $vfArrayValues['pathAttachment'];
    if($viNameClient != "" && $viEmailClient != "" && $viEmailBody != "" && $viEmailSub != ""){
      //Set who the message is to be sent to
      $mail->addAddress($viEmailClient, $viNameClient);
      //Set the subject line
      $mail->Subject = $viEmailSub;
      //Read an HTML message body from an external file, convert referenced images
      //to embedded,
      //convert HTML into a basic plain-text alternative body
      $mail->msgHTML($viEmailBody);
      //Replace the plain text body with one created manually
      //$mail->AltBody = 'This is a plain-text message body';
      //Attach an image file

      if(isset($vfArrayValues['attachment'])){
        $mail->addAttachment($vfArrayValues['attachment']['tmp_name'],
        $vfArrayValues['attachment']['name']);
      }
      //send the message, check for errors
      if (!$mail->send()) {
        $action = "[SEND_MAIL] - FAILED [ERROR]" . $mail->ErrorInfo;
       // funCreateLog($action, $connection);
        ////$db->commitAndClose();
        //return "false||Oppss... Fail sending email";
          return "false";
      } else {
        $action = "[SEND_MAIL] - FAILED [Success]" . $mail->ErrorInfo;
        ///funCreateLog($action, $connection);
        //$db->commitAndClose();
        return "true";//||Operation completed with sucess";
      }
    }else{
      $action = "[SEND_MAIL] - FAILED";
      //funCreateLog($action, $connection);
      //$db->commitAndClose();
      return "false";//||Oppss... Values empty";
    }
  }
?>
