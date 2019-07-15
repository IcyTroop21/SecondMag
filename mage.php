<?php
session_start();
include 'const.php';
?>

<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Acasa</title>
</head>
<body>
<div class="bar">

<a href="mage.php" style="float:left;">
<img src="img/logo.png" style="height:75px; margin:3px">
</a>

<form method="GET" action="mage.php" autocomplete="off">
	<input class="search" type="text" name="search" value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>" placeholder='Cauta ce vrei tu!'>
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

<div class='specify'>

<form method="GET" action='mage.php'>

	<input type="text" name="search" value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>" placeholder='Cauta ce vrei tu!'>

	<input style='float:right;margin-left:20px;margin-top:8px;' type="image" name="submit" src="img/search.png" width="55px" height="55px">

	<select class='takeit' style="width: 150px;margin-top:20px;" name="ordonare" >
	<?php
    if(isset($_GET['ordonare']))
    echo '<option value="'.$_GET['ordonare'].'" hidden>'.$_GET['ordonare'].'</option>';
    else
    echo '<option value="Cele mai noi" hidden>Cele mai noi</option>';
    ?>
	<option value="Pret crescator">Pret crescator</option>
	<option value="Cele mai noi">Cele mai noi</option>
  <option value="Pret descrescator">Pret descrescator</option>
	</select>


	<select class='takeit' name="categorie" style="margin-top:20px">
    <?php
        if(isset($_GET['categorie']))
        echo '<option value="'.$_GET['categorie'].'" hidden>'.$_GET['categorie'].'</option>';
        else
        echo '<option value="Toate categoriile" hidden>Toate categoriile</option>';
    ?>
    <option value="Toate categoriile">Toate categoriile</option>
        <optgroup label="Vehicule">
            <option value="Autoturisme">Autoturisme</option>
            <option value="Remorcabile">Remorcabile</option>
            <option value="Motociclete-Scutere">Motociclete-Scutere</option>
            <option value="Piese-Accesori">Piese-Accesori</option>
            <option value="Ambarcatiuni">Ambarcatiuni</option>
            <option value="Alte vehicule">Alte vehicule</option>
        </optgroup>
        <optgroup label="Proprietatii">
            <option value="Terenuri">Terenuri</option>
            <option value="Case-Apartamente">Case-Apartemente</option>
            <option value="Case-Apartamente de inchiriat">Case-Apartamente de inchiriat</option>
            <option value="Spatii de inchiriat">Spatii de inchiriat</option>
            <option value="Cazari">Cazari</option>
        </optgroup>
        <optgroup label="Servicii">
            <option value="Loc de munca">Loc de munca</option>
            <option value="Alte servicii">Alte servicii</option>
        </optgroup>
        <optgroup label="Electronice">
            <option value="Telefoane">Telefoane</option>
            <option value="Leptopuri-Calculatoare-Console">Leptopuri-Calculatoare-Console</option>
            <option value="Componente-Accesorii PC ">Componente-Accesori PC</option>
            <option value="TV-Audio-Video">TV-Audio-Video</option>
            <option value="Electrocasnice">Electrocasnice</option>
            <option value="Aparate Foto-Camere Video">Aparate Foto-Camere Video</option>
            <option value="Componente-Accesorii electronice">Componente-Accesorii electronice</option>
            <option value="Alte electronice">Alte electronice</option>
        </optgroup>
        <optgroup label="Jocuri si Jucarii">
            <option value="Jocuri Video">Jocuri Video</option>
            <option value="Jucarii Copii">Jucarii Copii</option>
            <option value="Figurine si obiecte de colectie">Figurine si obiecte de colectie</option>
            <option value="Jocuri de amuzament">Jocuri de amuzament</option>
        </optgroup>
        <optgroup label="Moda">
            <option value="Incaltaminte">Incaltaminte</option>
            <option value="Haine">Haine</option>
            <option value="Accesori-Bijuterii">Accesori-Bijuterii</option>
            <option value="Parfumuri-Cosmetice">Parfumuri-Cosmetice</option>
        </optgroup>
        <optgroup label="Casa ta">
            <option value="Mobila">Mobila</option>
            <option value="Atricole Menaj">Atricole Menaj</option>
            <option value="Unelte">Unelte</option>
            <option value="Materiale">Materiale</option>
            <option value="Alte elemente pentru casa">Alte elemente pentru casa</option>
        </optgroup>
        <optgroup label="Gradina">
            <option value='Unelte Gradinarit'>Unelte Gradinarit</option>
            <option value="Plante">Plante</option>
            <option value="Utilaje">Utilaje</option>
            <option value="Elemente de Gradina">Elemente de Gradina</option>
        </optgroup>
        <optgroup label="Sport si Arta">
            <option value="Echipament sportiv">Echipament sportiv</option>
            <option value="Obiecte de arta">Obiecte de arta</option>
            <option value="Filme-Muzica-Carti">Filme-Muzica-Carti</option>
            <option value="Divertisment">Divertisment</option>
        </optgroup>
        <optgroup label="Animale">
            <option value="Animale Domestice">Animale Domestice</option>
            <option value="Animale de companie">Animale de companie</option>
            <option value="Accesori">Accesori</option>
            <option value="Hrana">Hrana</option>
        </optgroup>
        <optgroup label="Preparate">
            <option value="Mancaruri">Mancaruri</option>
        </optgroup>
        <option value="Alta categorie">Alta categorie</option>
    </select>


</form>

</div>

<div class='master'>
<br>
<?php
  $search="";
	if(isset($_GET['search']))
    $search=$_GET['search'];
	$search=preg_replace("#[^0-9a-z]#i","",$search);
	$search="%".$search."%";

  //connection
	$handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
  $handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

  

  //delete the one requested
  if(isset($_POST['delete']))
  {
    //verify account
    $gj=false;
    if(isset($_SESSION['email']))
    {
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
    }
    if($gj)
    {
      $sql='SELECT * FROM postare WHERE id= :id';
      $stmt = $handler->prepare($sql);
      $stmt -> execute(['id' => $_POST['delete']]);
      $post = $stmt->fetch();
      if($post->email==$_SESSION['email'])
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
          $stmt -> execute(['id' => $_POST['delete']]);
          $sql='DELETE FROM favorite WHERE id= :id';
          $stmt = $handler->prepare($sql);
          $stmt -> execute(['id' => $_POST['delete']]);
      }
    }
  }

	$ordonare='data DESC';
	if(isset($_GET['ordonare']))
    if($_GET['ordonare']=='Pret crescator')
      $ordonare='pret ASC';
	else
    if($_GET['ordonare']=='Pret descrescator')
      $ordonare='pret DESC';

  $categorie="";
	if(isset($_GET['categorie']))
    if($_GET['categorie']!='Toate categoriile')
      $categorie=$_GET['categorie'];

  if($categorie)
	  $sql="SELECT * FROM postare WHERE titlu LIKE :search AND categorie= :categorie ORDER BY ".$ordonare;
  else
    $sql="SELECT * FROM postare WHERE titlu LIKE :search ORDER BY ".$ordonare;
	$stmt = $handler->prepare($sql);
  if($categorie)
    $stmt -> execute(['search' => $search, 'categorie' => $categorie]);
  else
    $stmt -> execute(['search' => $search]);
	$posts = $stmt->fetchAll();
  $nr = count($posts);

  //page management
  $fire=$nritems;
  if(isset($_GET['fire']))
    $fire=$_GET['fire']*$nritems;

  if($nr>0 && $fire<$nr+$nritems && $fire>=$nritems)
   	for($i=$fire-$nritems; $i<$fire && $i<$nr; $i++)
   	{
      $mine=false;
      //check if it's mine
      if(isset($_SESSION['email']))
      if($posts[$i]->email==$_SESSION['email'])
          $mine=true;

   		echo "<div class='glide'>";

  		echo "<a style='float:left; display:block; background-color:#e6e6e6; width:200px;height:130px' href='page.php?id=".$posts[$i]->id."'>
  			<img style='position:relative; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width:200px;max-height:130px' src='";

  		if(!empty($posts[$i]->img))
  		  echo $posts[$i] ->img."'</img></a>";
  		else
  		  echo "img/nophoto.jpg'</img></a>";

  		echo "<a style='margin:10px; font-size:22px; float:right; color:#2d2d2d'>";

  	  echo round($posts[$i]->pret*$monede[$posts[$i]->moneda],2);

  		echo " ".$posts[$i]->moneda." <img style='vertical-align: middle; width:35px;' src='img/money.png'/></a>";
  		echo "<a class='clickable' href='page.php?id=".$posts[$i]->id."'>".$posts[$i]->titlu."</a>";

  		echo "<br><br><br><a style='float:left;margin:10px;margin-top:56px;font-size:15px; color:gray'><img style='vertical-align: middle;width:15px;' src='img/locatie.png'/>".$posts[$i]->localitate." &nbsp &nbsp <img style='vertical-align: middle; width:14px;' src='img/date.png'/>  ".substr($posts[$i]->data, 6, -6)."/".substr($posts[$i]->data, 4, -8)."/".substr($posts[$i]->data, 0, -10)." ".substr($posts[$i]->data, 8, -4).":".substr($posts[$i]->data, 10, -2)." &nbsp &nbsp <img style='vertical-align: middle; width:15px;' src='img/tag.png'/> ".$posts[$i]->categorie."</a><br>";

      //Delete button
      if($mine)
      {
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='delete' value='".$posts[$i]->id."'>";
        echo "<input style='float:right;margin-top:20px;margin-right:5px'";
        ?>
         onclick="return  confirm('Sunteti sigur ca doriti sa stergeti acest anunt?')"
        <?php
        echo "type='image' src='img/delete.png' width='36px' height='36px'></form>";
        echo "<br><br><br>";
      }

  		echo "</div><br>";
    }
   	else
   	  echo "<a style='font-size:25px'>Nici un anunt gasit!<br></a>";
?>
<br>
<?php
  $fire/=$nritems;

  $ending='';
  if(isset($_GET['search']))
    $ending=$ending."&search=".$_GET['search'];

  if(isset($_GET['categorie']))
    $ending=$ending."&categorie=".$_GET['categorie'];

  if(isset($_GET['ordonare']))
    $ending=$ending."&ordonare=".$_GET['ordonare'];

?>

<center>
<?php
  if($fire-1 > 0)
  {
    echo "<a class='paje' href='mage.php?fire=";
    echo $fire-1;
    echo $ending."'>";
    echo "<";
    echo "</a>";
  }

  for($i=$fire-3;$i<=$fire+3;$i++)
    if($i==$fire)
    {
      echo "<a class='pajestatic' >";
      echo $fire;
      echo "</a>";
    }
    else
    if($i>0 && ($i-1)*$nritems<$nr)
    {
      echo "<a class='paje' href='mage.php?fire=";
      echo $i;
      echo $ending."'>";
      echo $i;
      echo "</a>";
    }

  if($fire*$nritems<$nr)
  {
    echo "<a class='paje' href='mage.php?fire=";
    echo $fire+1;
    echo $ending."'>";
    echo ">";
    echo "</a>";
  }

?>
</center>

</div>
<br>
<br>

</body>
</html>