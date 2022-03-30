<?php
//EMAIL PM AND RI CREATOR
  //DISTRO
  $to = "gilbert.carolino@cox.com,Kirsten.DeWitty@cox.com";
  $subject = 'Risk and Issue Created';
  $from = 'CCI-EESolutionsTeam@cox.com';

  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  // Create email headers
  $headers .= 'From: '.$from."\r\n".
      'Reply-To: '.$from."\r\n" .
      'X-Mailer: PHP/' . phpversion();

  // Compose a simple HTML email message
  $message = "<p>A new Risk and Issue has been created.  Below are the details.</p>";
  $message .="<br><b>Risk/Issue Name: </b>"; $message .= $name ; // NEED TO CONVERT CODE TO TEXT
  $message .="<br><b>Type: </b>"; $message .= $riTypeCode ; // NEED TO CONVERT CODE TO TEXT
  $message .="<br><b>Project: </b>"; $message .="" ;
  $message .="<br><b>Issue Descriptor: </b>"; $message .= $descriptor ;
  $message .="<br><b>Description: </b>"; $message .= $description ;
  $message .="<br><b>Drivers: </b>"; $message .= $drivers ;
  $message .="<br><b>Impact Area: </b>"; $message .= $impactArea ;
  $message .="<br><b>Impact Level: </b>"; $message .= $impactLevel ;
  $message .="<br><b>POC Group/Name: </b>"; $message .= $poc ;
  $message .="<br><b>Response Strategy: </b>"; $message .= $responseStrategy ;
  $message .="<br><b>Task POC Date: </b>"; $message .= $date ;
  $message .="<br><b>Associated Projects: </b>"; $message .= $assocProject ;
  $message .="<br><b>Action Plan: </b>"; $message .= $actionPlan ;
  $message .="<br><b>Date Closed: </b>"; $message .= $dateClosed ;
  
  // SEND EMAIL 
      if(mail($to, $subject, $message, $headers)){
          echo '<div align="center">An email was sent on your behalf to the Program Manager. </div>';
      } else {
          echo 'Unable to send email. Please contact EE Solutions.';
      }          
//END - EMAIL TO PM AND RI CREATOR        
?>