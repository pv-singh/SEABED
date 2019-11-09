<?php
//echo '<br> welcome to admain mail page <br>';
// This file is used to notify the admin about a submitted case.

$to='paramvir.ghotra@gmail.com, deeptiameta9@gmail.com';
$subject='Case Submitted';

$link="http://seabed.in/upload/".$file_name;

$body= ' Dear Admin,
       
 A case has been submitted by :'.$mailLoop.' on title : "'.$titleBeforeEscape.'" for category: "'.$category.'". 
 
 Please click here to view the case '.$link.'
       
 Regards:
 Team Seabed. ';

$headers ='From: SEABED <admin@seabed.in>';

// Sending email using mail() function
if(mail($to, $subject, $body, $headers))
{
	//echo 'mail has been sent to '.$to;	
}
//else
//echo 'mail can not be sent';
?>