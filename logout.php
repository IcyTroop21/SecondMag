<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['psw']);

if(isset($_SERVER['HTTP_REFERER']))
	header('Location: '.$_SERVER['HTTP_REFERER']);
else
	header('Location: mage.php'); 
exit;
?>