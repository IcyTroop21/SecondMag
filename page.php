<?php
session_start();
include 'const.php';
?>

<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Anunt</title>
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
		echo '<div class="drop">';
    	echo '<a class="thisone" href="profil.php?e='.$_SESSION['email'].'">'.$_SESSION['username'].'</a>';
    	echo '<div class="dropcont">';
    	echo '<img style=" vertical-align: middle; width:17px;" src="img/prof.png"/> <a class="last" href="profil.php?e='.$_SESSION['email'].'">Profilul meu&nbsp&nbsp</a><br>';
    	echo '<img style=" vertical-align: middle; width:17px;" src="img/fav.png"/> <a class="last" href="profil.php?e='.$_SESSION['email'].'&tab=favorite'.'">Favorite&nbsp&nbsp</a><br>';
    	echo '<img style=" vertical-align: middle; width:17px;" src="img/logout.png"/> <a class="last" href="logout.php">Logout&nbsp&nbsp</a><br>';
    	echo '</div>';
   		echo '</div>';
	}
	else
		echo '<a class="addbutton" href="login.php" style="margin-top:20px;float:right;">Contul meu</a>';

?>
</div>
<?php
	//if no id present go home
	if(!isset($_GET["id"]))
	{
		header('Location: mage.php');
		exit;
	}
	//connection
	$handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
    $handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

    $favved=false;
	$gj=false;
	if(isset($_SESSION['email']))
	{
		//Check if account is valid
		$sql='SELECT * FROM conturi WHERE email= :email';
	    $stmt = $handler->prepare($sql);
	    $stmt -> execute(['email' => $_SESSION['email']]);
	    $post = $stmt->fetch();
	    if(isset($post->psw))
	    {
	        $hash = $post->psw;
	        if(password_verify($_SESSION['psw'],$hash))
	            $gj=true;
	        else
	        {
	            header('Location: login.php');
	            exit;
	        }
	    }
	    else
	    {
	        header('Location: login.php');
	        exit;
	    }
	    //Check if the ad is favved
	    $sql='SELECT * FROM favorite WHERE email= :email AND id= :id';
	    $stmt = $handler->prepare($sql);
	    $stmt -> execute(['email' => $_SESSION['email'],'id'=> $_GET['id']]);
	    $post = $stmt->fetch();
	    if(isset($post->id))
	    $favved=true;
	}


?>
<br>
<div class="post" style="width: 600px; overflow-wrap: break-word; overflow: hidden;">
<?php

	//Look for the post with the id
    $sql='SELECT * FROM postare WHERE id= :id';
    $stmt = $handler->prepare($sql);
    $stmt -> execute(['id' => $_GET['id']]);
    $post = $stmt->fetch();
    if(isset($post->titlu))
    {
    	$mine=false;
	   	//check if it's mine
		if($gj && $post->email==$_SESSION['email'])
			$mine=true;

		//Delete if requested
		if(isset($_POST['delete']) && $_POST['delete']==$_GET['id'] && $mine)
		{
			if(!empty($post->img))
				unlink($post->img);
			if(!empty($post->img2))
				unlink($post->img2);
			if(!empty($post->img3))
				unlink($post->img3);
			if(!empty($post->img4))
				unlink($post->img4);
			if(!empty($post->img5))
				unlink($post->img5);
			$sql='DELETE FROM postare WHERE id= :id';
	    	$stmt = $handler->prepare($sql);
	    	$stmt -> execute(['id' => $_GET['id']]);
	    	$sql='DELETE FROM favorite WHERE id= :id';
	    	$stmt = $handler->prepare($sql);
	    	$stmt -> execute(['id' => $_GET['id']]);
	    	header('Location: mage.php');
	    	exit;
		}

	    //if he wants to add to fav and he's not logged go to login page
		if(!$gj && isset($_POST['go']))
			header('Location: login.php');

		//if requested to change fav state and logged
		if(isset($_POST['go']) && $gj)
			if($_POST['go']=='add')
			{
			  	if($favved==false)
			  	{
				$sql = "INSERT INTO favorite (email,id) VALUES (:email,:id)";
			  	$query = $handler->prepare($sql);
			  	$query->execute(['email' => $_SESSION['email'], 'id'=> $_GET['id']]);
			  	header('Location: '.$_SERVER['HTTP_REFERER']);
			  	}
			}
			else
			if($_POST['go']=='take')
			{
				if($favved==true)
			  	{
				$sql = "DELETE FROM favorite WHERE email = :email AND id = :id";
			  	$query = $handler->prepare($sql);
			  	$query->execute(['email' => $_SESSION['email'], 'id'=> $_GET['id']]);
			  	header('Location: '.$_SERVER['HTTP_REFERER']);
			  	}
			}

    	//add or take from favorite
    	echo "<form method='POST' action='page.php?id=".$post->id."'>";
    	echo "<input type='hidden' name='go' value='";
    	if($favved)
    		echo "take'";
    	else
    		echo "add'";
    	echo ">";
    	echo "<input style='position:absolute;' type='image' src='";
    	if($favved)
    		echo "img/yesfav.png'";
    	else
    		echo "img/nofav.png'";
    	echo " width='55px' height='55px'></form>";

    	// show add

    	//if ad has primary image show it
    	if(!empty($post->img))
    		echo "<img style='max-width:575px; max-height:500px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img."'onclick='openModal();currentSlide(1)'/><br>";
    	else
    		echo "<img style='width:350px;display: block; margin-left: auto; margin-right: auto;' src='img/nophoto.jpg'/><br>";

    	//Delete button
    	if($mine)
    	{
    		echo "<form method='POST' action='page.php?id=".$post->id."'>";
    		echo "<input type='hidden' name='delete' value='".$post->id."'>";
    		echo "<input style='float:right;'";
    		?>
    		 onclick="return  confirm('Sunteti sigur ca doriti sa stergeti acest anunt?')"
    		<?php
    		echo "type='image' src='img/delete.png' width='45px' height='45px'></form>";
    		echo "<br><br><br>";
    	}

    	echo "<img style='vertical-align: middle;float:left; width:39px; display: block;' src='img/money.png'/><div class='price'>";
    	echo round($post->pret*$monede[$post->moneda],2);
    	echo " ".$post->moneda."</div>";

    	echo "<img style='vertical-align: middle;float:right; width:39px; display: block;' src='img/phone.png'/><div class='phone'>".$post->telefon."</div><br><br><br><br>";
    	echo "<a style='font-size:23px'> ".$post->titlu."</a><br><br>";

    	echo "<a style='font-size:15px; color:gray'><img style='vertical-align: middle;width:15px;' src='img/locatie.png'/>".$post->localitate." &nbsp &nbsp <img style='vertical-align: middle; width:14px;' src='img/date.png'/>  ".substr($post->data, 6, -6)."/".substr($post->data, 4, -8)."/".substr($post->data, 0, -10)." ".substr($post->data, 8, -4).":".substr($post->data, 10, -2)." &nbsp &nbsp <img style='vertical-align: middle; width:15px;' src='img/tag.png'/> ".$post->categorie."<br><br>";
    	echo "Postat de </a>"."<a class='me' href='profil.php?e=".$post->email."'>".$post->username."</a>";
    	echo "<br><br>Descriere<hr><br>";
    	echo "<a style='color:#4d4d4d; font-size:15px;white-space:pre-wrap;'>".$post->descriere."</a><br><br><br>";

    	//if there's any image show them + show them in modal
    	if(!empty($post->img))
    	{
	    	echo "Imagini<hr><br>";
	    	if(!empty($post->img))
	    	echo "<img style='max-width:500px; max-height:250px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img."' onclick='openModal();currentSlide(1)'/><br>";
	    	if(!empty($post->img2))
	    	echo "<img style='max-width:500px; max-height:250px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img2."'onclick='openModal();currentSlide(2)'/><br>";
	    	if(!empty($post->img3))
	    	echo "<img style='max-width:500px; max-height:250px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img3."'onclick='openModal();currentSlide(3)'/><br>";
	    	if(!empty($post->img4))
	    	echo "<img style='max-width:500px; max-height:250px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img4."'onclick='openModal();currentSlide(4)'/><br>";
	    	if(!empty($post->img5))
	    	echo "<img style='max-width:500px; max-height:250px;display: block; margin-left: auto; margin-right: auto;' src='".$post->img5."'onclick='openModal();currentSlide(5)'/><br>";

	    	echo '<div id="myModal" class="modal">
					  <span class="close cursor" onclick="closeModal()">&times;</span>
					  
					  <div class="modal-content">';
			if(!empty($post->img))
			echo	    '<div class="mySlides">
					    <a href="'.$post->img.'" target="new">
					      <img src="'.$post->img.'" style="max-width:100%;max-height:100%;position:relative;top: 50%;left: 50%;transform: translate(-50%, -50%);">
						</a>
					    </div>';
			if(!empty($post->img2))		
			echo		'<div class="mySlides">
					    <a href="'.$post->img2.'" target="new">
					      <img src="'.$post->img2.'" style="max-width:100%;max-height:100%;position:relative;top: 50%;left: 50%;transform: translate(-50%, -50%);">
					    </a>
					    </div>';
			if(!empty($post->img3))		
			echo	    '<div class="mySlides">
					    <a href="'.$post->img3.'" target="new">
					      <img src="'.$post->img3.'" style="max-width:100%;max-height:100%;position:relative;top: 50%;left: 50%;transform: translate(-50%, -50%);">
					    </a>
					    </div>';
			if(!empty($post->img4))		    
			echo	    '<div class="mySlides">
					    <a href="'.$post->img4.'" target="new">
					      <img src="'.$post->img4.'" style="max-width:100%;max-height:100%;position:relative;top: 50%;left: 50%;transform: translate(-50%, -50%);">
					    </a>
					    </div>';
			if(!empty($post->img5))		    
			echo	    '<div class="mySlides">
					    <a href="'.$post->img5.'" target="new">
					      <img src="'.$post->img5.'" style="max-width:100%;max-height:100%;position:relative;top: 50%;left: 50%;transform: translate(-50%, -50%);">
					    </a>
					    </div>';	    
			echo		'<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
					    <a class="next" onclick="plusSlides(1)">&#10095;</a>
					
					    <div class="caption-container">
					      <p id="caption"></p>
					    </div>';
			if(!empty($post->img))		
			echo	   '<div class="boop">
	     				<img class="demo cursor" src="'.$post->img.'" style="max-width:100%;max-height:120px" onclick="currentSlide(1)">
					    </div>';
			if(!empty($post->img2))
			echo	    '<div class="boop">
					    <img class="demo cursor" src="'.$post->img2.'" style="max-width:100%;max-height:120px" onclick="currentSlide(2)">
					    </div>';
			if(!empty($post->img3))
			echo	    '<div class="boop">
	      				<img class="demo cursor" src="'.$post->img3.'" style="max-width:100%;max-height:120px" onclick="currentSlide(3)">
					    </div>';
			if(!empty($post->img4))
			echo	    '<div class="boop">
					    <img class="demo cursor" src="'.$post->img4.'" style="max-width:100%;max-height:120px" onclick="currentSlide(4)">
					    </div>';
			if(!empty($post->img5))
			echo	    '<div class="boop">
					    <img class="demo cursor" src="'.$post->img5.'" style="max-width:100%;max-height:120px" onclick="currentSlide(5)">
					    </div>';
					    
			echo	'</div>
				  </div>';
    	}
    }
    else
    	echo "<a style='font-size:20px'>Acest anunt a fost sters sau nu exista</a><br>";

?>
</div>

<script>
function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}


</script>




</body>
</html>