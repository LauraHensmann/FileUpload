<?php
require_once('scandir.php');
require_once('fileuploadOOP.php');

$upload = new Upload($_FILES["file"], $_POST['imagename']);
$result = '';

if ($upload->isImage() == false) {
	header("Location: index.php?error=".$upload->getError());	// Sonst Weiterleitung zur Startseite
	exit;
}
else {
	$upload->setImageName();
	$upload->checkIfNoNameIsSetAndReplace();
	$upload->renameImage();
	$upload->moveFile();
	$upload->leseverzeichnis();
	$result = $upload->leseverzeichnis();
}

header ("Location: index.php");
exit ();

?>
