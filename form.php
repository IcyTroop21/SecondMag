<form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
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
	<textarea style="" type='text' name='descriere' maxlength="8000"><?php if(isset($descriere)) echo $descriere ?></textarea> <img style='width:39px' src='img/descriere.png'/>
	<br>
    <?php
    if(isset($descriere_error))
        echo '<a style="color:red">'.$descriere_error."<br></a>";
    ?>
    <br>
	Pret:
	<input maxlength="10" style="width: 90px; text-align: right;" id="money" type="text" name="pret" value="<?php if(isset($pret)) echo $pret ?>" >

    <select name="moneda" style="width: 70px">
        <option value="<?php if(isset($_POST['moneda'])) echo $_POST['moneda']; else echo 'Lei';?>" selected  hidden><?php if(isset($moneda)) echo $moneda; else echo 'Lei';?></option>
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
	Judet :

	<select name="judet">
    <?php
        if(isset($judet))
    	echo '<option value="'.$judet.'" hidden>'.$judet.'</option>';
        else
        echo '<option value="" hidden></option>';
    ?>
    	<option value="Alba">Alba</option>
        <option value="Arad">Arad</option>
        <option value="Arges">Arges</option>
        <option value="Bacau">Bacau</option>
        <option value="Bihor">Bihor</option>
        <option value="Bistrita-Nasaud">Bistrita-Nasaud</option>
        <option value="Botosani">Botosani</option>
        <option value="Brasov">Brasov</option>
        <option value="Braila">Braila</option>
        <option value="Bucuresti">Bucuresti</option>
        <option value="Buzau">Buzau</option>
        <option value="Caras-Severin">Caras-Severin</option>
        <option value="Calarasi">Calarasi</option>
        <option value="Cluj">Cluj</option>
        <option value="Constanta">Constanta</option>
        <option value="Covasna">Covasna</option>
        <option value="Dambovita">Dambovita</option>
        <option value="Dolj">Dolj</option>
        <option value="Galati">Galati</option>
        <option value="Giurgiu">Giurgiu</option>
        <option value="Gorj">Gorj</option>
        <option value="Harghita">Harghita</option>
        <option value="Hunedoara">Hunedoara</option>
        <option value="Ialomnita">Ialomnita</option>
        <option value="Iasi">Iasi</option>
        <option value="Ilfov">Ilfov</option>
        <option value="Maramures">Maramures</option>
        <option value="Mehedinti">Mehedinti</option>
        <option value="Mures">Mures</option>
        <option value="Neamt">Neamt</option>
        <option value="Olt">Olt</option>
        <option value="Prahova">Prahova</option>
        <option value="Satu Mare">Satu Mare</option>
        <option value="Salaj">Salaj</option>
        <option value="Sibiu">Sibiu</option>
        <option value="Suceava">Suceava</option>
        <option value="Teleorman">Teleorman</option>
        <option value="Timis">Timis</option>
        <option value="Tulcea">Tulcea</option>
        <option value="Vaslui">Vaslui</option>
        <option value="Valcea">Valcea</option>
        <option value="Vrancea">Vrancea</option>
  	</select><img style='vertical-align: middle;width:32px' src='img/locatie.png'/>

  	<br>
    <?php
    if(isset($judet_error))
        echo '<a style="color:red">'.$judet_error."<br></a>";
    ?>
    <br>
  	Numar de telefon : <input maxlength="15" style="width: 120px; text-align: right;" value="<?php if(isset($telefon)) echo $telefon?>" type="text" name="telefon" ><img style='vertical-align: middle;width:32px' src='img/phone.png'/>
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
  	<button style="margin-left:65%; width:150px; margin-top:3px;" type="submit" value="submit" name="action">Modifica</button>
</form>
<br><br><br><br>