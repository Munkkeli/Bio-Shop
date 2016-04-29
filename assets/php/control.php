<?php

	/*
			Old functions
	 */
	function uploadImage(){
		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		if(isset($_POST["submit"])) {
    		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
   			if($check !== false) {
		        $uploadOk = 1;
		    } 
		    else {
		        echo "Tiedosto ei kelpaa. ";
		        $uploadOk = 0;
		    }

		    if (uploadOk == 0)
		    {
		    	echo "Lisäys epäonnistui. ";
		    }
		    else
		    {
		   		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        		echo "Lisäys onnistui. ";
        		}
		    }

		}
	}

	// this creates the small plates that include their picture and name, and it also includes two buttons for modifying the profile or deleting it
	function createHoitajaPlate($name,$imgsrc,$kuvaus ="",$id){
		echo '<div class="col-sm-2"><div class="thumbnail"><img src="'.$imgsrc.'" width="300" height="300"><p><strong>'.$name.'</strong></p></div><button type="button" data-toggle="modal" data-target="#' . $name. '-update">Muokkaa</button><button type="button" data-toggle="modal" data-target="#' . $name. '-delete">Poista</button></div>';

		echo '<div id="'.$name.'-update" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Muokataan hoitajaa '.$name.'</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="section" value="hoitaja"> </input><input type="hidden" name="id" value="'.$id.'"></input><input type="hidden" name="action" value="update"></input><input type="field" name="nimi" value="'.$name.'"> </input><input type="field" name="kuvaus" value="'.$kuvaus.'"></input><input type="file" name="image"></input><input type="submit" value="Päivitä"></input></form>';
		echo '</div></div></div>';
		echo '<div id="'.$name.'-delete" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Poistetaan hoitajaa '.$name.'</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="section" value="hoitaja"> </input> <input type="hidden" name="id" value="'.$id.'"> </input> <input type="hidden" name="action" value="delete"></input><input type="submit" value="Poista"></input></form>';
		echo '</div></div></div>';
	}

	//hoitaja functions

	// this creates the button to add more hoitajas
	function hoitajaNappi(){
		echo '<div class="col-sm-12"><button type="button" data-toggle="modal" data-target="#add">Lisää uusi hoitaja</button>';
		echo '<div id="add" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Lisätään uusi hoitaja</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="section" value="hoitaja"> </input><input type="hidden" name="action" value="add"></input><input type="field" name="nimi" placeholder="nimi"> </input><input type="field" name="kuvaus" placeholder="kuvaus"></input><input type="file" name="image"></input><input type="submit" value="Lisää"></input></form>';
		echo '</div></div></div></div>';
	}

	function listHoitajas(){
		$xml = simplexml_load_file('xml/hoitajat.xml');


		foreach($xml->hoitaja as $t)	
		{ 
		    $nimi = $t->name;
		    $kuva = $t->imagesrc;
		    $kuvaus = $t->kuvaus;
		    $id = $t['id'];
		    createHoitajaPlate($nimi,$kuva,$kuvaus,$id);
	 	}
	}

	// palvelu functions
	function listPalvelus() {
		$xml = simplexml_load_file('xml/palvelut.xml');

		foreach($xml->kategoria as $category) { 
			echo '<h2>' . $k['name'] . '</h2>';

			$nappiId = $k["id"];
		  foreach($k->palvelu as $p) {
	    	echo '<form method="POST" action="" ><input type="hidden" name="section" value="palvelu"> </input><input type="hidden" name="action" value="update"> </input><input type="hidden" name="id" value="'. $p["id"] .'"></input><input type="hidden" name="nappiid" value="'. $nappiId .'"></input><input type="field" name="palvelu" value="'. $p["nimi"] .'"></input><input type="field" name="kuvaus" value="'. $p->kuvaus .'"></input><input type="field" name="hinta" value="'. $p->hinta .'"></input><input type="field" name="hoitaja" value="'. $p->hoitajat .'"></input><input type="submit" value="Päivitä"></input></form> </br>';
	    	echo '<form method="POST" action="" ><input type="hidden" name="section" value="palvelu"> </input> <input type="hidden" name="id" value="'. $p["id"] .'"> </input><input type="hidden" name="nappiid" value="'. $nappiId .'"></input><input type="hidden" name="action" value="delete"></input><input type="submit" value="Poista"></input></form>';
	    }
	 	}
	}

    // Tapahtuma functions
	function listTapahtumas(){
		$xml = simplexml_load_file('xml/tapahtumat.xml');

		foreach($xml->tapahtuma as $t)
		{ 
			$id = $t['id'];
			echo '<form method="POST" action="" ><input type="hidden" name="section" value="tapahtuma"> </input><input type="hidden" name="action" value="update"> </input><input type="hidden" name="id" value="'.$id.'"></input><input type="field" name="title" value="'. $t->title .'"></input><input type="field" name="content" value="'. $t->content .'"></input><input type="submit" value="Päivitä"></input></form> </br>';

	 	}

	 	echo '<form method="POST" action="" ><input type="hidden" name="section" value="tapahtuma"> </input><input type="hidden" name="action" value="add"> </input><input type="field" name="title"></input><input type="field" name="content"></input><input type="submit" value="Lisää"></input></form> </br>';
	}
?>