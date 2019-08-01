<?php
session_start();
include 'const.php';
if(!isset($_POST['login']) && isset($_SERVER['HTTP_REFERER']))
$_SESSION['goback']=$_SERVER['HTTP_REFERER'];
?>

<?php
	//connection
	$handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
	$handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	$sql='SELECT * FROM trying WHERE ip= :ip';
	$stmt = $handler->prepare($sql);
	$stmt -> execute(['ip' => $ip]);
	$try = $stmt->fetch();
	if(!isset($try->ip))
	{
		$sql = "INSERT INTO trying (ip,attempts) VALUES (:ip,:attempts)";
		$query = $handler->prepare($sql);
		$query->execute(['ip' => $ip ,'attempts'=> '0']);

		$sql='SELECT * FROM trying WHERE ip= :ip';
		$stmt = $handler->prepare($sql);
		$stmt -> execute(['ip' => $ip]);
		$try= $stmt->fetch();
	}
	if($try->attempts<=3)
		$captcha=false;
	else
		$captcha=true;

	if(!$captcha_state)
		$captcha=false;

	//check if login requested
	if(isset($_POST['login']))
	{
		

  		if($captcha)
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

		
	  	//take the data
		$email=htmlspecialchars($_POST['email']);
	  	$password=htmlspecialchars($_POST['password']);


	  	//Verify the data
	  	if(!$captcha || ($captcha && empty($captcha_error)))
	  	{
	  		$sql='SELECT * FROM conturi WHERE email= :email';
	  		$stmt = $handler->prepare($sql);
	  		$stmt -> execute(['email' => $email]);
	  		$post = $stmt->fetch();
	  		if(isset($post->psw))
	  		{
	  			$hash = $post->psw;
		  		if(password_verify($password,$hash))
		  		{
		  			if($post->activated)
		  			{
			  			$sql="UPDATE trying SET attempts=:attempts WHERE ip=:ip";
			  			$query = $handler->prepare($sql);
			  			$query->execute(['attempts'=>'0','ip'=>$ip]);

			  			$_SESSION['username']=$post->username;
			  			$_SESSION['email']=$post->email;
			  			$_SESSION['psw']=$password;

			  			if(isset($_SESSION['goback']))
						header('Location: '.$_SESSION['goback']);
						else
						header('Location: mage.php');
						exit;
					}
					else
					{
						$_SESSION['premail']=$post->email;
						$_SESSION['prepsw']=$password;

						header('Location: verify.php');
						exit;
					}
		  		}
		  		else
		  		{
		  			$sql="UPDATE trying SET attempts=:attempts WHERE ip=:ip";
		  			$query = $handler->prepare($sql);
		  			$query->execute(['attempts'=>$try->attempts++,'ip'=>$ip]);
		  			$try->attempts++;
		  			$login_error="Email sau parola incorecta";
		  		}
	  		}
	  		else
	  		{
	  			$sql="UPDATE trying SET attempts=:attempts WHERE ip=:ip";
	  			$query = $handler->prepare($sql);
	  			$query->execute(['attempts'=>$try->attempts+1,'ip'=>$ip]);
	  			$try->attempts++;
	  			$login_error="Email sau parola incorecta";
		  	}

	  	}
	}
?>
<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Logare</title>
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
	if(isset($_SESSION['username']) && isset($_SESSION['psw']) && isset($_SESSION['email']))
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
Logare
<hr>

	<form method="post" action="login.php" style="text-align: center">
	E-mail : <input type="email" placeholder="E-mail" value="<?php if(isset($email)) echo $email?>" placeholder="E-mail" name="email"><br>
	Parola : <input type="password" placeholder="Parola" name="password"><br>
	<?php
	if(isset($login_error))
		echo '<br><a style="color:red">'.$login_error."</a>";
	?>
	<br>
	<?php
	if($captcha_state)
	if($try->attempts>3)
	echo '<br><div class="g-recaptcha" data-sitekey="6Le67ocUAAAAALuPMz9OZnqD6jEjHgxd2dADkmKR"></div><br>';
	?>
	<?php
	if(isset($captcha_error))
		echo '<a style="color:red">'.$captcha_error."<br></a>";
	?>
	<button type="submit" value="submit" name="login">Login</button>
	</form>
	<a class="button" href="register.php" style="float:left;">Inregistrare</a>

	<br>
	<br>
</div>



</body>
<?php
if($captcha_state)
if($try->attempts>3)
echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
?>
</head>
</html>