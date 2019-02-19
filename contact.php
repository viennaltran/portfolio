<?php 
$emailTo = 'viennaltran@gmail.com';
$siteTitle = 'Vienna Tran Portfolio';

error_reporting(E_ALL ^ E_NOTICE); // hide all basic notices from PHP

//If the form is submitted
if(isset($_POST['submitted'])) {
	
	// require a name from user
	if(trim($_POST['contactName']) === '') {
		$nameError =  'Forgot your name!'; 
		$hasError = true;
	} else {
		$name = trim($_POST['contactName']);
	}
	
	// need valid email
	if(trim($_POST['email']) === '')  {
		$emailError = 'Forgot to enter in your e-mail address.';
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
		$emailError = 'You entered an invalid email address.';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}
		
	// we need at least some content
	if(trim($_POST['comments']) === '') {
		$commentError = 'You forgot to enter a message!';
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['comments']));
		} else {
			$comments = trim($_POST['comments']);
		}
	}
		
	// upon no failure errors let's email now!
	if(!isset($hasError)) {
		
		$subject = 'New message to '.$siteTitle.' from '.$name;
		$sendCopy = trim($_POST['sendCopy']);
		$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments";
		$headers = 'From: ' .' <'.$email.'>' . "\r\n" . 'Reply-To: ' . $email;

		mail($emailTo, $subject, $body, $headers);
		
        //Autorespond
		$respondSubject = 'Thank you for contacting '.$siteTitle;
		$respondBody = "Your message to $siteTitle has been delivered! \n\nWe will answer back as soon as possible.";
		$respondHeaders = 'From: ' .' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
		
		mail($email, $respondSubject, $respondBody, $respondHeaders);
		
        // set our boolean completion value to TRUE
		$emailSent = true;
	}
// from mail_hander.php
		header('Access-Control-Allow-Origin: *');
		require_once('email_config.php');
		require_once('phpmailer/PHPMailer/src/Exception.php');
		require_once('phpmailer/PHPMailer/src/PHPMailer.php');
		require_once('phpmailer/PHPMailer/src/SMTP.php');
		foreach($_POST as $key=>$value){
			$_POST[$key] = htmlentities( addslashes($value));
		}

		$mail = new PHPMailer\PHPMailer\PHPMailer;
		$mail->SMTPDebug = 0;           // Enable verbose debug output. Change to 0 to disable debugging output.

		$mail->isSMTP();                // Set mailer to use SMTP.
		$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
		$mail->SMTPAuth = true;         // Enable SMTP authentication


		$mail->Username = EMAIL_USER;   // SMTP username
		$mail->Password = EMAIL_PASS;   // SMTP password
		$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
		$mail->Port = 587;              // TCP port to connect to
		$options = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->smtpConnect($options);
		$mail->From = 'vtranserver@gmail.com';  // sender's email address (shows in "From" field)
		$mail->FromName = 'mailer';   // sender's name (shows in "From" field)
		$mail->addAddress('viennaltran@gmail.com', 'Vienna Tran');  // Add a recipient (name is optional)
		//$mail->addAddress('ellen@example.com');                        // Add a second recipient
		$mail->addReplyTo($_POST['email']);                          // Add a reply-to address
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'mailer message from '.$_POST['email'];
		$mail->Body    = " 
			time: ".date('Y-m-d H:is:s')."<br>
			from: {$_SERVER['REMOTE_ADDR']}<br>
			name: {$_POST['name']}<br>
			email: {$_POST['email']}<br>
			subject: {$_POST['subject']}<br>
			message: {$_POST['body']}<br>
		";
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
}
?>