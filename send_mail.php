<?php
/*
This first bit sets the email address that you want the form to be submitted to.
You will need to change this value to a valid email address that you can access.
*/
$webmaster_email = "support@ride-guardian.com";

/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
*/
$feedback_page = "contact.html";
$error_page = "error_message.html";
$thankyou_page = "thank_you2.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email_address = $_REQUEST['email_address'] ;
$comments = $_REQUEST['comments'] ;
$number = $_REQUEST['number'] ;
$zip = $_REQUEST['zip'] ;

$first_name = $_REQUEST['first_name'] ;
$msg =
"Name: " . $first_name . "\r\n" .
"Email: " . $email_address . "\r\n" .
"Comment: " . $comments . "\r\n" .
"Zip: " . $zip . "\r\n" .
"Number: " . $number;

$msg2 =
'<html>
<body>
<img  src="https://ride-guardian.com/img/log2.png" alt="" height="200" width="200" style="display:block; margin-left:auto; margin-right:auto;">
<h2 style="text-align:center; color: #004a99;">Hello ' . $first_name . '! </h2>
  <p style="line-height: 1.9rem; text-align:center;">We always look forward to go through applications of great people who would like to work with us at Guardian. Thank you for applying for a position with us, and here is a confirmation that we received your application. One of our recruiters will contact you shortly to let you know about the status of your application.</p><br>
  <p style=" text-align:center;">Kind regards,</p>
  <p style=" text-align:center;">Your Guardian team </p>
  <p style=" text-align:center;">Phone: (650) 293-0661</p>
  <p style=" text-align:center;">Website: <a href=" https://www.ride-guardian.com">ride-guardian.com</a><br></p>

</body>
</html>
';

$headers .= "From: Ride-guardian <support@ride-guardian.com> \r\n";
$headers2 .= "From: Guardian Driver application! <support@ride-guardian.com> \r\n";

$headers .= 'Content-type: text/html; charset=iso-8859-1';


/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email_address'])) {
header( "Location: $feedback_page" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($first_name) || empty($email_address)) {
header( "Location: $error_page" );
}

/*
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($email_address) || isInjected($first_name)  || isInjected($comments) || isInjected($number) || isInjected($zip) ) {
header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {

	mail( "$webmaster_email", "Driver application from Ride-guardian.com!", $msg, $headers2 );
	mail("$email_address", "Guardian, noreply!", $msg2, $headers  );

	header( "Location: $thankyou_page" );
}
?>
