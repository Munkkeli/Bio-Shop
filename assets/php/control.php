<?php
	


	if (isset($_POST["action"]) && !empty($_POST["action"]) && isset($_POST["id"]) && !empty($_POST["id"])) {

    		if ($_POST["action"] == "delete"){
    			deleteHoitaja($_POST["id"]);
    			echo "poistettu!";
    			echo $_POST["id"];
    		}

    		if ($_POST["action"] == "update"){

    		}


    		if ($_POST["action"] == "add"){
    			addHoitaja();
    		}

	}

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
		    	echo "Lisäys epäonnistui. "
		    }
		    else
		    {
		   		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        		echo "Lisäys onnistui. ";
		    }

		}
	}

	function createHoitajaPlate($name,$imgsrc,$kuvaus ="",$id){
		echo '<div class="col-sm-2"><div class="thumbnail"><img src="'.$imgsrc.'" width="300" height="300"><p><strong>'.$name.'</strong></p></div><button type="button" data-toggle="modal" data-target="#' . $name. '-update">Muokkaa</button><button type="button" data-toggle="modal" data-target="#' . $name. '-delete">Poista</button></div>';

		echo '<div id="'.$name.'-update" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Muokataan hoitajaa '.$name.'</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="action" value="update"></input><input type="field" name="nimi"> </input><input type="field" name="kuvaus"></input><input type="file" name="image"></input><input type="submit" value="Päivitä"></input></form>';
		echo '</div></div></div>';
		echo '<div id="'.$name.'-delete" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Poistetaan hoitajaa '.$name.'</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="action" value="delete"></input><input type="submit" value="Poista"></input></form>';
		echo '</div></div></div>';
	}

	//hoitaja functions

	function hoitajaNappi(){
		echo '<button type="button" data-toggle="modal" data-target="#add">Lisää uusi hoitaja</button>';
		echo '<div id="add" class="modal fade" role="dialog"><div class="modal-content"> <div class="modal-header"> <h4>Lisätään uusi hoitaja</h4><button type="button" class="close" data-dismiss="modal">X</button> </div><div class="modal-dialog"> ';
		echo '<form method="POST" action="" ><input type="hidden" name="action" value="add"></input><input type="field" name="nimi" placeholder="nimi"> </input><input type="field" name="kuvaus" placeholder="kuvaus"></input><input type="file" name="image"></input><input type="submit" value="Lisää"></input></form>';
		echo '</div></div></div>';
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

	function addHoitaja(){
		$id = uniqid();
	}

	function updateHoitaja(){

	}

	function deleteHoitaja($id){
		$xml = simplexml_load_file('xml/hoitajat.xml');

		foreach($xml->hoitaja as $found){ 
			if ($found["id"] == $id){
				$dom = dom_import_simplexml($found);
				$dom->parentNode->removeChild($dom); 
			}
		}

		$content = $xml->asXml();

		file_put_contents("xml/hoitajat.xml", $content);
	}

	// palvelu functions
	function listPalvelus(){
		$xml = simplexml_load_file('xml/palvelut.xml');

		foreach($xml->tapahtuma as $t)
		{ 
		    echo $t->title;
		    echo $t->content;
	 	}
	}

	function addPalvelu(){

	}

	function updatePalvelu(){

	}

	function deletePalvelu(){

	}

    // Tapahtuma functions
	function listTapahtumas(){
		$xml = simplexml_load_file('xml/tapahtumat.xml');

		foreach($xml->tapahtuma as $t)
		{ 
		    echo $t->title;
		    echo $t->content;
	 	}
	}

	function addTapahtuma($title,$description){

	}

	function updateTapahtuma($id,$title,$description){
		
	}

	function deleteTapahtuma($id){

	}

?>
