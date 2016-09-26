<?php
/**
* File upload execution 
*/

require_once('scandir.php');
require_once('fileuploadOOP.php');

$imageName = isset($_POST['imagename']) ? $_POST['imagename'] : 'UploadedByDragAndDrop';

$upload = new Upload($_FILES["file"], $imageName);
 
if ($upload->isImage()) {
	$upload->checkIfNoNameIsSetAndReplace();
	$upload->renameImage();
	$upload->moveFile();
	$upload->leseverzeichnis();
	header("Location: index.php");
}
 else {
	header("Location: index.php?error=".$upload->getError());	
}

