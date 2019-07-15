<?php
session_start();
include 'const.php';
?>

<!DOCTYPE html>
<head>
  <link rel = "stylesheet" href = "css/fine.css"/>
  <title>SecondMag - Profilul Meu</title>
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

<div class='profil'>
<?php
  if(isset($_GET['e']))
  {
    //connection
    $handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
    $handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

    //look for email
    $sql='SELECT * FROM conturi WHERE email = :email';
    $stmt = $handler -> prepare($sql);
    $stmt -> execute(['email' => $_GET['e']]);
    $post = $stmt->fetch();
    //if mail exists
    if(isset($post ->email))
    {
      //verify account
      $myaccount=false;
      $gj=false;
      if(isset($_SESSION['email']))
      {
        $sql='SELECT * FROM conturi WHERE email= :email';
        $stmt = $handler->prepare($sql);
        $stmt -> execute(['email' => $_SESSION['email']]);
        $lol = $stmt->fetch();
        if(isset($lol->psw))
        {
            $hash = $lol->psw;
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

        //verify if it's my account
        if($lol->email==$_GET['e'])
          $myaccount=true;

        //delete the one requested
        if($gj && isset($_POST['delete']))
        {
          $sql='SELECT * FROM postare WHERE id= :id';
          $stmt = $handler->prepare($sql);
          $stmt -> execute(['id' => $_POST['delete']]);
          $lol = $stmt->fetch();
          if($lol->email==$_SESSION['email'])
          {
            if(!empty($lol->img))
              unlink($lol->img);
            if(!empty($lol->img2))
              unlink($lol->img2);
            if(!empty($lol->img3))
              unlink($lol->img3);
            if(!empty($lol->img4))
              unlink($lol->img4);
            if(!empty($lol->img5))
              unlink($lol->img5);
            $sql='DELETE FROM postare WHERE id= :id';
              $stmt = $handler->prepare($sql);
              $stmt -> execute(['id' => $_POST['delete']]);
              $sql='DELETE FROM favorite WHERE id= :id';
              $stmt = $handler->prepare($sql);
              $stmt -> execute(['id' => $_POST['delete']]);
          }
        }
      }

      //show some data of account
      echo "<div class='imgtext'><img src='img/pic.png' width=60px>";
      echo $post->username."</div>";
      echo "<a style='float:left;margin-left:20px;color:gray'>";
      echo "E-mail : ";
      echo $post->email."<br><br>";
      echo "Cont creat la : ";
      echo substr($post->space, 6, -6)."/".substr($post->space, 4, -8)."/".substr($post->space, 0, -10);
      echo "</a></div>";

      //see what tab it is
      $tab='anunturi';
      if(isset($_GET['tab']))
        $tab=$_GET['tab'];
      echo "<div class='detalii'>";

      //anunturi
      if($tab=='anunturi')
      {
        echo "<a class='pajestatic' >Anunturi</a>";
        if($myaccount)
          echo "<a class='paje' href='profil.php?e=".$_GET['e']."&tab=favorite'>Favorite</a>";
        echo "<br><br>";

        //see all ads
        $sql="SELECT * FROM postare WHERE email LIKE :email ORDER BY data DESC";
        $stmt = $handler->prepare($sql);
        $stmt -> execute(['email' => $_GET['e']]);
        $posts = $stmt->fetchAll();
        $nr = count($posts);

        //page managing
        $fire=$nritems;
        if(isset($_GET['fire']))
          $fire=$_GET['fire']*$nritems;

        if($nr>0 && $fire<$nr+$nritems && $fire>=$nritems)
          for($i=$fire-$nritems; $i<$fire && $i<$nr; $i++)
          {
            $mine=false;
            //check if it's mine
            if($gj && $posts[$i]->email==$_SESSION['email'])
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
        echo "<br>";
        $fire/=$nritems;
        $ending='&e='.$_GET['e'];

        echo "<center>";
        if($fire-1 > 0)
        {
          echo "<a class='paje' href='profil.php?fire=";
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
              echo "<a class='paje' href='profil.php?fire=";
              echo $i;
              echo $ending."'>";
              echo $i;
              echo "</a>";
            }

        if($fire*$nritems<$nr)
        {
          echo "<a class='paje' href='profil.php?fire=";
          echo $fire+1;
          echo $ending."'>";
          echo ">";
          echo "</a>";
        }

        echo '</center>';
      }

      //favorite (shown only when you it's your account)
      if($tab=='favorite')
      if($myaccount)
      {

        echo "<a class='paje' href='profil.php?e=".$_GET['e']."'>Anunturi</a>";
        echo "<a class='pajestatic' >Favorite</a>";

        echo "<br><br>";

        //take the ids of favourites
        $sql="SELECT * FROM favorite WHERE email LIKE :email";
        $stmt = $handler->prepare($sql);
        $stmt -> execute(['email' => $_GET['e']]);
        $array = $stmt->fetchAll();
        foreach($array as $array)
        {
          $new_array[]=$array->id;
        }
        $sql="SELECT * FROM postare WHERE id IN (".implode(',',$new_array).") ORDER BY data DESC";
        $stmt = $handler->prepare($sql);
        $stmt -> execute();
        $posts = $stmt->fetchAll();
        $nr = count($posts);


        //page managing
        $fire=$nritems;
        if(isset($_GET['fire']))
          $fire=$_GET['fire']*$nritems;

        if($nr>0 && $fire<$nr+$nritems && $fire>=$nritems)
          for($i=$fire-$nritems; $i<$fire && $i<$nr; $i++)
          {
            $mine=false;
            //check if it's mine
            if($gj && $posts[$i]->email==$_SESSION['email'])
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
        echo "<br>";
        $fire/=$nritems;
        $ending='&e='.$_GET['e'].'&tab=favorite';

        echo "<center>";
        if($fire-1 > 0)
        {
          echo "<a class='paje' href='profil.php?fire=";
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
            echo "<a class='paje' href='profil.php?fire=";
            echo $i;
            echo $ending."'>";
            echo $i;
            echo "</a>";
          }

        if($fire*$nritems<$nr)
        {
          echo "<a class='paje' href='profil.php?fire=";
          echo $fire+1;
          echo $ending."'>";
          echo ">";
          echo "</a>";
        }
        echo '</center>';
      }
      else
        header('Location: profil.php?e='.$_GET['e']);
      echo "</div>";
    }
    else
      echo "Cont inexistent!</div>";
  }
?>
</body>
</html>