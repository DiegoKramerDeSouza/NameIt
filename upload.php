<?php 
	
	if ($_FILES['BKPFile']['error'] > 0) {
		echo "Error: " . $_FILES['BKPFile']['error'] . "<br />";
	} else {
		
		$targetDir = "./uploads/";
		$targetFile = $targetDir . basename($_FILES["BKPFile"]["name"]);
		$validExtensions = array('.bkp');
		$fileExtension = strrchr($_FILES['BKPFile']['name'], ".");
		if (in_array($fileExtension, $validExtensions)) {
			if(move_uploaded_file($_FILES["BKPFile"]["tmp_name"], $targetFile)){
				echo 'Arquivo de backup carregado com sucesso!<br /><br />';
			} else {
				echo 'Falha ao carregar arquivo!<br /><br />';
			}
		} else {
			echo 'Por favor selecione uma imagem!';
		}
		
	}
	
	
	
?>