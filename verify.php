<?php
session_start();
include 'const.php';
?>
<?php
	//Connection
	$handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
	$handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

	if(!isset($_SESSION['premail']) || !isset($_SESSION['prepsw']))
	{
		header('Location: login.php');
		exit;
	}
	else
	{
		$sql='SELECT * FROM conturi WHERE email= :email';
  		$stmt = $handler->prepare($sql);
  		$stmt -> execute(['email' => $_SESSION['premail']]);
  		$post = $stmt->fetch();
  		if(isset($post->psw))
  		{
  			$hash = $post->psw;
	  		if(!password_verify($_SESSION['prepsw'],$hash))
	  		{
	  			header('Location: login.php');
	  			exit;
	  		}
		}
	}

	if(isset($_SESSION['takeit']))
	{
		$mail_state=$_SESSION['takeit'];
		unset($_SESSION['takeit']);
	}

	if($captcha_state)
	if(isset($_POST['mail']) || isset($_POST['verify']))
	{
	//reCaptcha
	$secret="6Le67ocUAAAAAM1ls3fFJDCUGUAkBKaxWRsjNrd2";
	$response = $_POST["g-recaptcha-response"];
	$url="https://www.google.com/recaptcha/api/siteverify";
	$data= array(
		'secret' => '6Le67ocUAAAAAM1ls3fFJDCUGUAkBKaxWRsjNrd2',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array(
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context = stream_context_create($options);
	$verify= file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
	$captcha_success = json_decode($verify);
	if($captcha_success -> success == false)
	$captcha_error="Captcha incorect!";
	}

	if(isset($_POST['mail']) && (!isset($captcha_error) || !$captcha_state))
	{
		$key = md5(microtime().rand());
		$multi=password_hash($key, PASSWORD_BCRYPT);
		$sql="UPDATE conturi set cheie=:cheie WHERE email=:email";
		$query = $handler->prepare($sql);
		$query->execute(['cheie'=>$multi,'email'=>$_SESSION['premail']]);

		$reciever=$_SESSION['premail'];
		$subject="Cheia de acces pentru contul tau SecondMag";
		$body="Cheia ta de acces este ".$key."<br> Daca nu ai creat nici un cont, ignora acest mesaj.";
		$nonhtml="Cheia ta de acces este ".$key." , Daca nu ai creat nici un cont, ignora acest mesaj.";

		include 'respect.php';

	}
	if(isset($_POST['verify']) && (!isset($captcha_error) || !$captcha_state))
	{
		if(password_verify($_POST['key'],$post->cheie))
		{
			$sql="UPDATE conturi set activated=:activated WHERE email=:email";
			$query = $handler->prepare($sql);
			$query->execute(['activated'=>'1','email'=>$_SESSION['premail']]);

			$_SESSION['username']=$post->username;
			$_SESSION['email']=$_SESSION['premail'];
			$_SESSION['psw']=$_SESSION['prepsw'];

			header('Location: mage.php');
		}
		else
		$verify_error="Cheie incorecta!";
	}


?>

<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Verificare</title>
</head>
<body>
<div class="bar">

<a href="mage.php" style="float:left;">
<img src="img/logo.png" style="height:75px; margin:3px">
</a>

<form method="POST" action="mage.php" autocomplete="off">
	<input class="search" type="text" name="search" value="<?php if(isset($_POST['search'])) echo $_POST['search'] ?>" placeholder='Cauta ce vrei tu!'>
	<input style='float:left;margin-top:15px;' type="image" name="submit" src="img/search.png" width="50px" height="50px">
</form>

<?php
	echo '<a class="addbutton" style="float:right;margin-top:20px;margin-left:20px" href="addpost.php">+ Adauga anunt </a>';
	if(isset($_SESSION['username']) || isset($_SESSION['psw']) || isset($_SESSION['email']))
	{
		header('Location: mage.php');
		exit;
	}
	else
	echo '<a class="addbutton" href="login.php" style="margin-top:20px;float:right;">Contul meu</a>';

?>

</div>
<br>
<br>
<div class="create">
Verificare E-mail
<hr>
	<form method="post" action="verify.php" style="text-align: center">
	<?php
	if(isset($mail_state))
		if($mail_state!="Mail-ul nu a putut fi trimis")
			echo '<a style="color:#00cc00">'.$mail_state."<br><br></a>";
		else
			echo '<a style="color:red">'.$mail_state."<br><br></a>";
	?>
	Codul Cheie : <input style="border-radius:2px;" value="<?php if(isset($_POST['key'])) echo $_POST['key']?>" type="text" name="key"><br><br>
	<?php
	if(isset($verify_error))
		echo '<a style="color:red">'.$verify_error."<br><br></a>";
	?>
	<?php
	if($captcha_state)
		echo '<div class="g-recaptcha" data-sitekey="6Le67ocUAAAAALuPMz9OZnqD6jEjHgxd2dADkmKR"></div><br>';
	?>
	<?php
	if(isset($captcha_error))
		echo '<a style="color:red">'.$captcha_error."<br></a>";
	?>
	<button type="submit" value="mail" name="mail">Retrimite mail</button>
	<button type="submit" value="verify" name="verify">Verifica</button>
	</form>

</div>
</body>
<?php
if($captcha_state)
	echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
?>
</head>
</html>