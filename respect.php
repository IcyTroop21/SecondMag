<?php

	include 'auth.php';

	//$reciever="tth_cristi@yahoo.com";
	//$subject="Haha yes 2";
	//$body="go to and thendwadwaaw , and you got it";
	//$nonhtml="???";



	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $mymail;
    $mail->Password   = $psw;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom($mymail, $myname);
    $mail->addAddress($reciever);
    $mail->addReplyTo($mymail, $myname);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $nonhtml;

    $mail->send();
    $mail_state="Mail retrimis cu succes!";
} catch (Exception $e) {
    $mail_state="Mail-ul nu a putut fi trimis";
}
?>