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
return "";
}


?>
<!DOCTYPE html>
<head>
<link rel = "stylesheet" href = "css/fine.css"/>
<title>SecondMag - Adaugare anunt</title>
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

    //Try adding post if requested
    if(isset($_POST['action']))
    {
        //connection
        $handler = new PDO('mysql:host=127.0.0.1;dbname=nameless','root','');
        $handler ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

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
                if($time <= $post->lastpost + 300 && $post->lastpost!=$post->space)
                //check if he recently posted
                $spam_error="Asteapta 3 minute inainte de a posta din nou!";
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
        $localitate=htmlspecialchars($_POST['localitate']);
        $moneda=htmlspecialchars($_POST['moneda']);

        //categorie
        if(empty($categorie))
            $categorie_error="Nu ati ales o categorie";
        else
        if($categorie!='Autoturisme' && $categorie!='Remorcabile' && $categorie!='Motociclete-Scutere' && $categorie!='Piese-Accesori' && $categorie!='Ambarcatiuni' && $categorie!='Alte vehicule' && $categorie!='Terenuri' && $categorie!='Case-Apartamente' && $categorie!='Case-Apartamente de inchiriat' && $categorie!='Spatii de inchiriat' && $categorie!='Cazari' && $categorie!='Loc de munca' && $categorie!='Alte servicii' && $categorie!='Telefoane' && $categorie!='Leptopuri-Calculatoare-Console' && $categorie!='Componente-Accesorii PC' && $categorie!='TV-Audio-Video' && $categorie!='Electrocasnice' && $categorie!='Aparate Foto-Camere Video' && $categorie!='Componente-Accesorii electronice' && $categorie!='Alte electronice' && $categorie!='Jocuri Video' && $categorie!='Jucarii Copii' && $categorie!='Figurine si obiecte de colectie' && $categorie!='Jocuri de amuzament' && $categorie!='Incaltaminte' && $categorie!='Haine' && $categorie!='Accesori-Bijuterii' && $categorie!='Parfumuri-Cosmetice' && $categorie!='Mobila' && $categorie!='Atricole Menaj' && $categorie!='Unelte' && $categorie!='Materiale' && $categorie!='Alte elemente pentru casa' && $categorie!='Unelte Gradinarit' && $categorie!='Plante' && $categorie!='Utilaje' && $categorie!='Elemente de Gradina' && $categorie!='Echipament sportiv' && $categorie!='' && $categorie!='Obiecte de arta' && $categorie!='Filme-Muzica-Carti' && $categorie!='Divertisment' && $categorie!='Animale Domestice' && $categorie!='Animale de companie' && $categorie!='Accesori' && $categorie!='Hrana' && $categorie!='Mancaruri' && $categorie!='Alta categorie')
            $categorie_error='Ceva a mers prost';
        //localitate
        if(empty($localitate))
            $localitate_error="Nu ati ales o localitate";
        else
        if($localitate!='Anina' && $localitate!='Baile Herculane' && $localitate!='Bocsa' && $localitate!='Caransebes' && $localitate!='Resita')
        $localitate_error='Ceva a mers prost';
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
        if(empty($categorie_error) && empty($localitate_error) && empty($titlu_error) && empty($descriere_error) && empty($pret_error) && empty($telefon_error) && empty($spam_error))
        {
            $pret/=$monede[$moneda];

            $img = upload("img");
            $img2 = upload("img2");
            $img3 = upload("img3");
            $img4 = upload("img4");
            $img5 = upload("img5");

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

            $sql = "INSERT INTO postare (titlu,categorie,descriere,pret,localitate,moneda,telefon,email,username,data,img,img2,img3,img4,img5) VALUES (:titlu,:categorie,:descriere,:pret,:localitate,:moneda,:telefon,:email,:username,:data,:img,:img2,:img3,:img4,:img5)";
            $query = $handler->prepare($sql);

            $query->execute(['titlu' => $titlu ,'categorie'=> $categorie,'descriere'=> $descriere,'pret'=> $pret,'localitate' => $localitate,'moneda' => $moneda, 'telefon' => $telefon, 'email'=> $email, 'username' => $username, 'data' => $time, 'img' => $img, 'img2' => $img2, 'img3' => $img3, 'img4' => $img4, 'img5' => $img5]);
            
            //modify last post date
            $sql = "UPDATE conturi SET lastpost='$time' WHERE email='$email'";
            $stmt = $handler->prepare($sql);
            $stmt -> execute();

            header('Location: mage.php');
            exit;
        }
    }
?>

</div>
<br>
<br>
<div class="post" style="width: 640px;overflow: hidden; overflow-wrap: break-word;">
Informatii anunt
<hr>
<form method="POST" action="addpost.php" enctype="multipart/form-data" autocomplete="off">
    <br>
	<div style="text-align: right; margin-right: 100px">
	Titlu : <input style="margin-right:37px" maxlength="40" type="text" name="titlu" value="<?php if(isset($titlu)) echo $titlu?>" > <br>
    <?php
    if(isset($titlu_error))
        echo '<a style="color:red">'.$titlu_error."<br></a>";
    ?>
    <br>
	Categorie : 
    <select name="categorie" >
    <?php
        if(isset($categorie))
        echo '<option value="'.$categorie.'" hidden>'.$categorie.'</option>';
        else
        echo '<option value="" hidden></option>';
    ?>
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
            <option value="Componente-Accesorii PC">Componente-Accesori PC</option>
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
    <img style='vertical-align: middle;width:32px' src='img/tag.png'/><br>
    <?php
    if(isset($categorie_error))
        echo '<a style="color:red">'.$categorie_error."<br></a>";
    ?>
	<br><br><br>
	Descriere : 
	<textarea style="" type='text' name='descriere' maxlength="8000"><?php if(isset($_POST['descriere'])) echo $descriere ?></textarea> <img style='width:39px' src='img/descriere.png'/>
	<br>
    <?php
    if(isset($descriere_error))
        echo '<a style="color:red">'.$descriere_error."<br></a>";
    ?>
    <br>
	Pret:
	<input maxlength="10" style="width: 90px; text-align: right;" id="money" type="text" name="pret" value="<?php if(isset($_POST['pret'])) echo $_POST['pret'] ?>" >

    <select name="moneda" style="width: 70px">
        <option value="<?php if(isset($_POST['moneda'])) echo $_POST['moneda']; else echo 'Lei';?>" selected  hidden><?php if(isset($_POST['moneda'])) echo $_POST['moneda']; else echo 'Lei';?></option>
        <option value="Lei">Lei</option>
        <option value="Euro">Euro</option>
    </select> <img style='vertical-align: middle;width:32px' src='img/money.png'/>
    <br>

    <?php
    if(isset($pret_error))
        echo '<a style="color:red">'.$pret_error."<br></a>";
    ?>
    <br> 

	</div>
	<br><br>
	Fotografii
	<hr>
    <div class="couple">
	<div style="width:200px; height:167px;" class="img-up">
		<label for="file">
		<a id="imagePreview"></a>
		</label>
		<input type="file" id="file" accept="image/*" name="img" onchange="return fileValidation()"/>
	</div>
    <img style="width:250px;height:150px ; float:right;" src="img/lol.png">
    </div>
    <br>
    <div class="couple">
	<div class="img-up">
		<label for="file2">
		<a id="imagePreview2"></a>
		</label>
		<input type="file" id="file2" accept="image/*" name="img2" onchange="return fileValidation2()"/>
	</div>
	<div class="img-up">
		<label for="file3">
		<a id="imagePreview3"></a>
		</label>
		<input type="file" id="file3" accept="image/*" name="img3" onchange="return fileValidation3()"/>
	</div>
	<div class="img-up">
		<label for="file4">
		<a id="imagePreview4"></a>
		</label>
		<input type="file" id="file4" accept="image/*" name="img4" onchange="return fileValidation4()"/>
	</div>
	<div class="img-up">
		<label for="file5">
		<a id="imagePreview5"></a>
		</label>
		<input type="file" id="file5" accept="image/*" name="img5" onchange="return fileValidation5()"/>
	</div>
    </div>
    <br>
	Date de contactare
	<hr>
	<div style="margin-left: 20%">
	<br><br><br><br>
	Localitate :

	<select name="localitate">
    <?php
        if(isset($localitate))
    	echo '<option value="'.$localitate.'" hidden>'.$localitate.'</option>';
        else
        echo '<option value="" hidden></option>';
    ?>
    	<option value="Anina">Anina</option>
    	<option value="Baile Herculane">Baile Herculane</option>
    	<option value="Bocsa">Bocsa</option>
    	<option value="Caransebes">Caransebes</option>
    	<option value="Resita">Resita</option>
  	</select><img style='vertical-align: middle;width:32px' src='img/locatie.png'/>

  	<br>
    <?php
    if(isset($localitate_error))
        echo '<a style="color:red">'.$localitate_error."<br></a>";
    ?>
    <br>
  	Numar de telefon : <input maxlength="15" style="width: 120px; text-align: right;" value="<?php if(isset($_POST['telefon'])) echo $telefon?>" type="text" name="telefon" ><img style='vertical-align: middle;width:32px' src='img/phone.png'/>
  	<br>
    <?php
    if(isset($telefon_error))
        echo '<a style="color:red">'.$telefon_error."<br></a>";
    ?>
    <br>
    <br>
    <?php
    if(isset($spam_error))
        echo '<a style="color:red">'.$spam_error."<br></a>";
    ?>
  	</div>
  	</div>
  	<button style="margin-left:65%; width:150px; margin-top:3px;" type="submit" value="submit" name="action">Posteaza</button>
</form>
<br><br><br><br>
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
document.getElementById('imagePreview').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';

function fileValidation(){
    var fileInput = document.getElementById('file');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    var imgpath=document.getElementById('file');
    if(!allowedExtensions.exec(filePath))
    {
    	fileInput.value = '';
        document.getElementById('imagePreview').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview').innerHTML = '<img class="imgprevprim" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview2').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview3').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview4').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
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
        document.getElementById('imagePreview5').innerHTML = '<img class="imgprev" src="img/boi.jpg"/>';
    	}
    }
}
</script>
</body>
</html>