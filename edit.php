<?php
session_start();
include 'const.php';
?>
<?php

function upload($img)
{
if(isset($_FILES[$img]))
if(!empty($_FILES[$img]['name']))
{
    $file = $_FILES[$img];

    $filename = $_FILES[$img]['name'];
    $fileloc = $_FILES[$img]['tmp_name'];
    $filesize = $_FILES[$img]['size'];
    $fileerror = $_FILES[$img]['error'];
    $filetype = $_FILES[$img]['type'];

    $fileext = explode('.', $filename);
    $fileactualext = strtolower(end($fileext));

    $allowed = array('jpg','jpeg','png');

    if(in_array($fileactualext,$allowed))
    {
        if($fileerror === 0)
        {
            if($filesize < 5000000)
            {
            $newfilename = uniqid('',true).".".$fileactualext;
            $filedestination = "uploads/".$newfilename;
            move_uploaded_file($fileloc, $filedestination);
            return $filedestination;
            }
            else
            echo "Erroare";
        }
        else
        echo "Erroare";
    }
    else
    echo "Erroare";
}
else
return false;
}


?>
<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Modificare anunt</title>
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
    //Bar
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
    {
	header('Location: login.php');
    exit;
    }
?>
</div>

<?php
	$gtg=false;
	if(isset($_GET['id']) && isset($_SESSION['email']))
	{
		//connection
        $handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
        $handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);


        //verify 
        $sql='SELECT * FROM postare WHERE id= :id';
        $stmt = $handler->prepare($sql);
        $stmt -> execute(['id' => $_GET['id']]);
        $anunt = $stmt->fetch();
        if(isset($anunt->email))
        if($anunt->email==$_SESSION['email'])
        	$gtg=true;

	}

	if($gtg)
	{
		$titlu=$anunt->titlu;
		$categorie=$anunt->categorie;
		$descriere=$anunt->descriere;
		$pret=round($anunt->pret*$monede[$anunt->moneda],2);
		$moneda=$anunt->moneda;
		$judet=$anunt->judet;
		$telefon=$anunt->telefon;
	}

    //Try editing post if requested
    if(isset($_POST['action']) && $gtg)
    {
        //take some data
        $email=htmlspecialchars($_SESSION['email']);
        $password=htmlspecialchars($_SESSION['psw']);
        $username=htmlspecialchars($_SESSION['username']);
        date_default_timezone_set("Europe/Bucharest");
        $time= date("Y").date("m").date("d").date("H").date("i").date("s");

        //verify the email and pass
        $sql='SELECT * FROM conturi WHERE email= :email';
        $stmt = $handler->prepare($sql);
        $stmt -> execute(['email' => $email]);
        $post = $stmt->fetch();
        if(isset($post->psw))
        {
            $hash = $post->psw;
            if(password_verify($password,$hash))
            {
                if($time <= $post->lastpost + $timebetween && $post->lastpost!=$post->space)
                //check if he recently posted
                $spam_error="Asteapta 3 minute inainte de a modifica alt anunt!";
            }
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

        //take the data
        $telefon=htmlspecialchars($_POST['telefon']);
        $pret=htmlspecialchars($_POST['pret']);
        $titlu=htmlspecialchars($_POST['titlu']);
        $descriere = htmlspecialchars($_POST['descriere']);

        $categorie=htmlspecialchars($_POST['categorie']);
        $judet=htmlspecialchars($_POST['judet']);
        $moneda=htmlspecialchars($_POST['moneda']);

        //categorie
        if(empty($categorie))
            $categorie_error="Nu ati ales o categorie";
        else
        if($categorie!='Autoturisme' && $categorie!='Remorcabile' && $categorie!='Motociclete-Scutere' && $categorie!='Piese-Accesori' && $categorie!='Ambarcatiuni' && $categorie!='Alte vehicule' && $categorie!='Terenuri' && $categorie!='Case-Apartamente' && $categorie!='Case-Apartamente de inchiriat' && $categorie!='Spatii de inchiriat' && $categorie!='Cazari' && $categorie!='Loc de munca' && $categorie!='Alte servicii' && $categorie!='Telefoane' && $categorie!='Leptopuri-Calculatoare-Console' && $categorie!='Componente-Accesorii PC' && $categorie!='TV-Audio-Video' && $categorie!='Electrocasnice' && $categorie!='Aparate Foto-Camere Video' && $categorie!='Componente-Accesorii electronice' && $categorie!='Alte electronice' && $categorie!='Jocuri Video' && $categorie!='Jucarii Copii' && $categorie!='Figurine si obiecte de colectie' && $categorie!='Jocuri de amuzament' && $categorie!='Incaltaminte' && $categorie!='Haine' && $categorie!='Accesori-Bijuterii' && $categorie!='Parfumuri-Cosmetice' && $categorie!='Mobila' && $categorie!='Atricole Menaj' && $categorie!='Unelte' && $categorie!='Materiale' && $categorie!='Alte elemente pentru casa' && $categorie!='Unelte Gradinarit' && $categorie!='Plante' && $categorie!='Utilaje' && $categorie!='Elemente de Gradina' && $categorie!='Echipament sportiv' && $categorie!='' && $categorie!='Obiecte de arta' && $categorie!='Filme-Muzica-Carti' && $categorie!='Divertisment' && $categorie!='Animale Domestice' && $categorie!='Animale de companie' && $categorie!='Accesori' && $categorie!='Hrana' && $categorie!='Mancaruri' && $categorie!='Alta categorie')
            $categorie_error='Ceva a mers prost';
        //judet
        if(empty($judet))
            $judet_error="Nu ati ales un judet";
        else
        if($judet!='Alba' && $judet!='Arad' && $judet!='Arges' && $judet!='Bacau' && $judet!='Bihor' && $judet!='Bistrita-Nasaud' && $judet!='Botosani' && $judet!='Brasov' && $judet!='Braila' && $judet!='Bucuresti' && $judet!='Buzau' && $judet!='Caras-Severin' && $judet!='Calarasi' && $judet!='Cluj' && $judet!='Constanta' && $judet!='Covasna' && $judet!='Dambovita' && $judet!='Dolj' && $judet!='Galati' && $judet!='Giurgiu' && $judet!='Gorj' && $judet!='Harghita' && $judet!='Hunedoara' && $judet!='Ialomnita' && $judet!='Iasi' && $judet!='Ilfov' && $judet!='Maramures' && $judet!='Mehedinti' && $judet!='Mures' && $judet!='Neamt' && $judet!='Olt' && $judet!='Prahova' && $judet!='Satu Mare' && $judet!='Salaj' && $judet!='Sibiu' && $judet!='Suceava' && $judet!='Teleorman' && $judet!='Timis' && $judet!='Tulcea' && $judet!='Vaslui' && $judet!='Valcea' && $judet!='Vrancea')
        $judet_error='Ceva a mers prost';
        //moneda
        if(empty($moneda))
            $moneda_error="Nu ati ales moneda";
        else
        if($moneda!='Lei' && $moneda!='Euro')
            $moneda_error='Ceva a mers prost';

        //titlul
        if(empty($titlu))
        $titlu_error="Acest camp trebuie completat";
        else
        if(strlen($_POST['titlu']) <=2)
        $titlu_error="Titlul este prea scurt";
        else
        if(strlen($_POST['titlu']) >40)
        $titlu_error="Titlul este prea lung";

        //descrierea
        if(empty($descriere))
        $descriere_error="Acest camp trebuie completat";
        else
        if(strlen($_POST['descriere']) <=10)
        $descriere_error="Descrierea este prea scurta";
        else
        if(strlen($_POST['descriere']) >8000)
        $descriere_error="Descrierea este prea lunga";

        //pretul
        if(empty($pret))
        $pret_error="Acest camp trebuie completat";
        else
        if(!ctype_digit($pret))
        $pret_error="Pretul este format doar din cifre!";
        else
        if(strlen($_POST['pret']) >= 10)
        $pret_error="Pretul este prea mare";

        //telefonul
        if(empty($telefon))
        $telefon_error="Acest camp trebuie completat";
        else
        if(strlen($_POST['telefon']) <=5)
        $telefon_error="Numarul de telefon este prea scurt";
        else
        if(strlen($_POST['telefon']) >16)
        $telefon_error="Numarul de telefon este prea lung";

        //if no error, upload
        if(empty($categorie_error) && empty($judet_error) && empty($titlu_error) && empty($descriere_error) && empty($pret_error) && empty($telefon_error) && empty($spam_error))
        {
            $pret/=$monede[$moneda];


            $img = upload("img");
            if(!$img)
            	$img=$anunt->img;
            else
            	unlink($anunt->img);

            $img2 = upload("img2");
            if(!$img2)
            	$img2=$anunt->img2;
            else
            	unlink($anunt->img2);

            $img3 = upload("img3");
            if(!$img3)
            	$img3=$anunt->img3;
            else
            	unlink($anunt->img3);

            $img4 = upload("img4");
            if(!$img4)
            	$img4=$anunt->img4;
            else
            	unlink($anunt->img4);

            $img5 = upload("img5");
            if(!$img5)
            	$img5=$anunt->img5;
            else
            	unlink($anunt->img5);

            $verify=true;
            while($verify)
            {
                $verify=false;
                if(empty($img))
                if(!empty($img2))
                {
                    $img=$img2;
                    $img2="";
                    $verify=true;
                }

                if(empty($img2))
                if(!empty($img3))
                {
                    $img2=$img3;
                    $img3="";
                    $verify=true;
                }
        
                if(empty($img3))
                if(!empty($img4))
                {
                    $img3=$img4;
                    $img4="";
                    $verify=true;
                }
            
                if(empty($img4))
                if(!empty($img5))
                {
                    $img4=$img5;
                    $img5="";
                    $verify=true;
                }
            }

            $sql = "UPDATE postare SET titlu=:titlu, categorie=:categorie, descriere=:descriere, pret=:pret, judet=:judet, moneda=:moneda, telefon=:telefon, email=:email, username=:username, data=:data, img=:img, img2=:img2, img3=:img3, img4=:img4, img5=:img5 WHERE id=:id";
            $query = $handler->prepare($sql);

            $query->execute(['titlu' => $titlu ,'categorie'=> $categorie,'descriere'=> $descriere,'pret'=> $pret,'judet' => $judet,'moneda' => $moneda, 'telefon' => $telefon, 'email'=> $email, 'username' => $username, 'data' => $time, 'img' => $img, 'img2' => $img2, 'img3' => $img3, 'img4' => $img4, 'img5' => $img5, 'id' => $_GET['id']]);
            
            //modify last post date
            $sql = "UPDATE conturi SET lastpost=:time WHERE email=:email";
            $stmt = $handler->prepare($sql);
            $stmt -> execute(['time'=> $time,'email'=>$email]);

            header('Location: page.php?id='.$_GET['id']);
            exit;
        }
    }
?>

<br>
<br>

<div class="post" style="width: 640px;overflow: hidden; overflow-wrap: break-word;">
<?php
	$blank="img/boi.jpg";
	if($gtg)
	{
		echo "Informatii anunt <hr>";
		include "form.php";
	}
	else
		echo "Error 231231";
?>

<script>
    
//id money
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    textbox.addEventListener(event, function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      }
    });
  });
}
setInputFilter(document.getElementById("money"), function(value) {
  return /^-?\d*[.,]?\d{0,2}$/.test(value);
});

//descriere auto-expand
var autoExpand = function (field) {

	field.style.height = 'inherit';

	var computed = window.getComputedStyle(field);

	var height = parseInt(computed.getPropertyValue('border-top-width'), 10)
	             + parseInt(computed.getPropertyValue('padding-top'), 10)
	             + field.scrollHeight
	             + parseInt(computed.getPropertyValue('padding-bottom'), 10)
	             + parseInt(computed.getPropertyValue('border-bottom-width'), 10);

	field.style.height = height + 75 + 'px';

};
document.addEventListener('input', function (event) {
	if (event.target.tagName.toLowerCase() !== 'textarea') return;
	autoExpand(event.target);
}, false);

//Selectare imagine
document.getElementById('imagePreview').innerHTML = '<img class="imgprevprim" src="<?php if(!empty($anunt->img)) echo $anunt->img; else echo $blank; ?>"/>';
document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img2)) echo $anunt->img2; else echo $blank; ?>"/>';
document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img3)) echo $anunt->img3; else echo $blank; ?>"/>';
document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img4)) echo $anunt->img4; else echo $blank; ?>"/>';
document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img5)) echo $anunt->img5; else echo $blank; ?>"/>';

function fileValidation(){
    var fileInput = document.getElementById('file');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file');
    if(!allowedExtensions.exec(filePath))
    {
    	fileInput.value = '';
        document.getElementById('imagePreview').innerHTML = '<img class="imgprevprim" src="<?php if(!empty($anunt->img)) echo $anunt->img; else echo $blank; ?>"/>';
        alert('Doar imagini! (.jpeg/.jpg/.png)');
    }
    else
    {
    	if (!imgpath.value=="")
        var img=imgpath.files[0].size;
        //Image preview
        if (fileInput.files && fileInput.files[0] && img < 5000000) 
        {
            var reader = new FileReader();
            reader.onload = function(e) 
            {
                document.getElementById('imagePreview').innerHTML = '<img class="imgprevprim" src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
        else
        {
        fileInput.value = '';
        document.getElementById('imagePreview').innerHTML = '<img class="imgprevprim" src="<?php if(!empty($anunt->img)) echo $anunt->img; else echo $blank; ?>"/>';
        alert("Imagine prea mare( maxim 5MB)");
    	}
    }
}
function fileValidation2(){
    var fileInput = document.getElementById('file2');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file2');
    if(!allowedExtensions.exec(filePath))
    {
        alert('Doar imagini! (.jpeg/.jpg/.png)');
        fileInput.value = '';
        document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img2)) echo $anunt->img2; else echo $blank; ?>"/>';
    }
    else
    {
    	if (!imgpath.value=="")
        var img=imgpath.files[0].size;
        //Image preview
        if (fileInput.files && fileInput.files[0] && img < 5000000) 
        {
            var reader = new FileReader();
            reader.onload = function(e) 
            {
                document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
        else
        {
        alert("Imagine prea mare( maxim 5MB)");
        fileInput.value = '';
        document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img2)) echo $anunt->img2; else echo $blank; ?>"/>';
    	}
    }
}
function fileValidation3(){
    var fileInput = document.getElementById('file3');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file3');
    if(!allowedExtensions.exec(filePath))
    {
        alert('Doar imagini! (.jpeg/.jpg/.png)');
        fileInput.value = '';
        document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img3)) echo $anunt->img3; else echo $blank; ?>"/>';
    }
    else
    {
    	if (!imgpath.value=="")
        var img=imgpath.files[0].size;
        //Image preview
        if (fileInput.files && fileInput.files[0] && img < 5000000) 
        {
            var reader = new FileReader();
            reader.onload = function(e) 
            {
                document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
        else
        {
        alert("Imagine prea mare( maxim 5MB)");
        fileInput.value = '';
        document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img3)) echo $anunt->img3; else echo $blank; ?>"/>';
    	}
    }
}
function fileValidation4(){
    var fileInput = document.getElementById('file4');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file4');
    if(!allowedExtensions.exec(filePath))
    {
        alert('Doar imagini! (.jpeg/.jpg/.png)');
        fileInput.value = '';
        document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img4)) echo $anunt->img4; else echo $blank; ?>"/>';
    }
    else
    {
    	if (!imgpath.value=="")
        var img=imgpath.files[0].size;
        //Image preview
        if (fileInput.files && fileInput.files[0] && img < 5000000) 
        {
            var reader = new FileReader();
            reader.onload = function(e) 
            {
                document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
        else
        {
        alert("Imagine prea mare( maxim 5MB)");
        fileInput.value = '';
        document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img4)) echo $anunt->img4; else echo $blank; ?>"/>';
    	}
    }
}
function fileValidation5(){
    var fileInput = document.getElementById('file5');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file5');
    if(!allowedExtensions.exec(filePath))
    {
        alert('Doar imagini! (.jpeg/.jpg/.png)');
        fileInput.value = '';
        document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img5)) echo $anunt->img5; else echo $blank; ?>"/>';
    }
    else
    {
    	if (!imgpath.value=="")
        var img=imgpath.files[0].size;
        //Image preview
        if (fileInput.files && fileInput.files[0] && img < 3000000) 
        {
            var reader = new FileReader();
            reader.onload = function(e) 
            {
                document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
        else
        {
        alert("Imagine prea mare( maxim 5MB)");
        fileInput.value = '';
        document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="<?php if(!empty($anunt->img5)) echo $anunt->img5; else echo $blank; ?>"/>';
    	}
    }
}
</script>
</body>
</html>