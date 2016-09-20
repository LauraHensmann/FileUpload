<?php
require_once('fileuploadOOP.php');
echo "hallo?";
$upload = new Upload();
if ($upload->isImage()) {
	$upload->checkIfNoNameIsSetAndReplace();
	$upload->renameImage();
	$upload->moveFile();
	
	header("Location: index.php");
}
if(isset($_GET['method']) && $_GET['method'] == "ajax"){
	echo "ajaaaaax";
} else {
	//header("Location: index.php?error=".$upload->getError());	
}

