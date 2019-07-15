<?php
session_start();
?>
<?php



  		// $sql='SELECT * FROM conturi WHERE username = :uau && phone = :phone';
  		// $stmt = $handler->prepare($sql);
  		// $stmt -> execute(['uau' => $uau, 'phone'=>'0725323501' ]);
  		// $posts = $stmt->fetchAll();
  		// foreach ($posts as $post)
  		// 	echo $post ->phone,'<br>';


  		// $sql='SELECT * FROM conturi WHERE username = :uau && phone = :phone';
  		// $stmt = $handler->prepare($sql);
  		// $stmt -> execute(['uau' => $uau, 'phone'=>'0725323501' ]);
  		// $post = $stmt->fetch();
  		// echo $post ->username;





if(isset($_POST['register']))
{
	//connectiom
	$handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
  	$handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

  	//take the data
  	$username=htmlspecialchars($_POST['username']);
  	$email=htmlspecialchars($_POST['email']);
  	$password=htmlspecialchars($_POST['password']);
  	$password2=htmlspecialchars($_POST['password2']);

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

	//Username
	if(empty($username))
		$username_error="Nu ati introdus numele!";
	else
	if(strlen($_POST['username']) > 40)
		$username_error="Numele este prea lung";
	else
	if(strlen($_POST['username']) <= 3)
		$username_error="Numele este prea scurt";

  	//Password
  	if($password2!=$password)
  		$password_error="Parolele nu corespund";
  	else
  	if(empty($password))
  		$password_error="Nu ati introdus parola!";
  	else
  	if(strlen($_POST['password'])<6)
  		$password_error="Parola este prea scurta";
  	else
  	if(strlen($_POST['password']) >40 )
  		$password_error="Parola este prea lunga";
  	else
  		$psw=password_hash($password, PASSWORD_BCRYPT);

  	date_default_timezone_set("Europe/Bucharest");
    $time= date("Y").date("m").date("d").date("H").date("i").date("s");

    //Email
	if(empty($email))
	$email_error="Nu ati introdus Emailul!";
	else
	if(strlen($_POST['email']) > 40)
	$email_error="Mail prea lung";
	else
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  	$email_error = "Email invalid";
  	else
  	{
  		$sql='SELECT * FROM conturi WHERE email = :email';
   		$stmt = $handler -> prepare($sql);
  		$stmt -> execute(['email' => $email]);
  		$post = $stmt->fetch();
  		if(isset($post ->email))
  			$email_error="Exista deja un cont cu acest Email";
  	}

  	//Store the data
  	if(empty($username_error) && empty($email_error) && empty($password_error) && empty($captcha_error))
  	{
  		$sql = "INSERT INTO conturi (username,psw,email,lastpost,space) VALUES (:username,:psw,:email,:lastpost,:space)";
  		$query = $handler->prepare($sql);

  		$query->execute(['username' => $username ,'psw'=> $psw,'email'=> $email,'lastpost'=> $time,'space'=> $time]);

  		header('Location: mage.php');
  		exit;
  	}


}

?>
<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Inregistrare</title>
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
Inregistrare
<hr>
	<form method="post" action="register.php" style="text-align: right; margin-right: 75px">
	
	Nume Utilizator : <input maxlength="40" type="text" value="<?php if(isset($username)) echo $username?>" placeholder="Nume de utilizator" name="username" required><br>
	<?php
	if(isset($username_error))
		echo '<a style="color:red">'.$username_error."<br></a>";
	?>
	E-mail : <input maxlength="40" type="email" value="<?php if(isset($email)) echo $email?>" placeholder="E-mail" name="email" required><br>
	<?php
	if(isset($email_error))
		echo '<a style="color:red">'.$email_error."<br></a>";
	?>
	Parola : <input maxlength="30" type="password" placeholder="Parola" name="password" required><br>
	<input type="password" placeholder="Reintroduceti parola" name="password2" required><br>
	<?php
	if(isset($password_error))
		echo '<a style="color:red">'.$password_error."<br></a>";
	?>
	<br>
	<div class="g-recaptcha" data-sitekey="6Le67ocUAAAAALuPMz9OZnqD6jEjHgxd2dADkmKR"></div>
	<br>
	<br>
	<?php
	if(isset($captcha_error))
		echo '<a style="color:red">'.$captcha_error."<br></a>";
	?>
	<button type="submit" value="submit" name="register">Creare cont</button>
	</form>
	<br>
	<br>
</div>



</body>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
</html>