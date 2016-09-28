<?php
/**
* File upload execution 
*/

require_once('fileuploadOOP.php');

$imageName = isset($_POST['imagename']) ? $_POST['imagename'] : 'UploadedByDragAndDrop';


$upload = new Upload($_FILES["file"], $imageName);
 
if (!$upload->isImage()) {
	echo json_encode(['Error: File is not an image'=>1]);
	exit();
}

$upload->checkIfNoNameIsSetAndReplace();
$upload->renameImage();
$upload->moveFile();

echo $upload->leseverzeichnis();
