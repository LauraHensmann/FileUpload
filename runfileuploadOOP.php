<?php
require_once('scandir.php');
require_once('fileuploadOOP.php');
$upload = new Upload($_FILES["file"], $_POST['imageName']);
 
if ($upload->isImage()) {
	$upload->checkIfNoNameIsSetAndReplace();
	$upload->renameImage();
	$upload->moveFile();
	$upload->leseverzeichnis();
}
 else {
	header("Location: index.php?error=".$upload->getError());	
}

