<?php
	// sections, hoitaja
	// actions, add update delete
	if (isset($_POST["action"]) && !empty($_POST["action"])) {
		if ($_POST["section"] == "hoitaja"){
			if (isset($_POST["id"]) && !empty($_POST["id"])){
		    		
		    		if ($_POST["action"] == "delete"){
		    			deleteHoitaja($_POST["id"]);
		    			echo "poistettu!";
		    			echo $_POST["id"];
		    		}

		    		if ($_POST["action"] == "update"){
			    		updateHoitaja($_POST["id"],$_POST["nimi"],$_POST["kuvaus"],$_FILES["image"]);
			    		echo "updating";
		    		}

	    		}

	    		if ($_POST["action"] == "add"){
	    			echo "lisätään...";
	    			addHoitaja($_POST["nimi"],$_POST["kuvaus"],$_FILES["image"]);
	    		}
    		}

    	if ($_POST["section"] == "tapahtuma"){
			if (isset($_POST["id"]) && !empty($_POST["id"])){
		    		
		    		if ($_POST["action"] == "delete"){
		    			deleteTapahtuma($_POST["id"]);
		    			echo "poistettu!";
		    			echo $_POST["id"];
		    		}

		    		if ($_POST["action"] == "update"){
			    		updateTapahtuma($_POST["id"],$_POST["title"],$_POST["content"]);
			    		echo "updating";
		    		}

	    		}

	    		if ($_POST["action"] == "add"){
	    			echo "lisätään...";
	    			addTapahtuma($_POST["title"],$_POST["content"]);
	    		}
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

	function addHoitaja($name,$kuvaus,$file){
		$id = uniqid();

		$hoitajat = simplexml_load_file('xml/hoitajat.xml');

		$hoitaja = $hoitajat->addChild("hoitaja");
		$hoitaja->addAttribute('id', $id);
		$hoitaja->addChild('name',$name);
		$hoitaja->addChild('kuvaus',$kuvaus);

		// TODO add the upload image function
		if (isset($file))
			$hoitaja->addChild('imagesrc','baba.jpg');
		else
			$hoitaja->addChild('imagesrc','images/profile/to.jpg');

		$content = $hoitajat->asXml();
		file_put_contents("xml/hoitajat.xml", $content);

	}

	// TODO implement image upload here...
	function updateHoitaja($id,$name,$kuvaus,$file){
		$xml = simplexml_load_file('xml/hoitajat.xml');

		foreach($xml->hoitaja as $found){ 
			if ($found["id"] == $id){
				$found->name = $name;
				$found->kuvaus = $kuvaus;
			}
		}

		$content = $xml->asXml();

		file_put_contents("xml/hoitajat.xml", $content);
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
			$id = $t['id'];
			echo '<form method="POST" action="" ><input type="hidden" name="section" value="tapahtuma"> </input><input type="hidden" name="action" value="update"> </input><input type="hidden" name="id" value="'.$id.'"></input><input type="field" name="title" value="'. $t->title .'"></input><input type="field" name="content" value="'. $t->content .'"></input><input type="submit" value="Päivitä"></input></form> </br>';

	 	}

	 	echo '<form method="POST" action="" ><input type="hidden" name="section" value="tapahtuma"> </input><input type="hidden" name="action" value="add"> </input><input type="field" name="title"></input><input type="field" name="content"></input><input type="submit" value="Lisää"></input></form> </br>';
	}

	function addTapahtuma($title,$description){
		$id = uniqid();

		$tapahtumat = simplexml_load_file('xml/tapahtumat.xml');

		$tapahtuma = $tapahtumat->addChild("tapahtuma");
		$tapahtuma->addAttribute('id', $id);
		$tapahtuma->addChild('title',$title);
		$tapahtuma->addChild('content',$description);

		$content = $tapahtumat->asXml();
		file_put_contents("xml/tapahtumat.xml", $content);

	}

	function updateTapahtuma($id,$title,$content){
		$xml = simplexml_load_file('xml/tapahtumat.xml');

		foreach($xml->tapahtuma as $found){ 
			if ($found["id"] == $id){
				$found->title = $title;
				$found->content = $content;
			}
		}

		$content = $xml->asXml();

		file_put_contents("xml/tapahtumat.xml", $content);
	}

	function deleteTapahtuma($id){
		$xml = simplexml_load_file('xml/tapahtumat.xml');

		foreach($xml->tapahtuma as $found){ 
			if ($found["id"] == $id){
				$dom = dom_import_simplexml($found);
				$dom->parentNode->removeChild($dom); 
			}
		}

		$content = $xml->asXml();

		file_put_contents("xml/tapahtumat.xml", $content);
	}
?>