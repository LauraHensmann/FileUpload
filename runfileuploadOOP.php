<?php
require_once('scandir.php');
require_once('fileuploadOOP.php');
$upload = new Upload($_FILES["file"], $_POST['imagename']);

if ($upload->isImage()) {
	$upload->checkIfNoNameIsSetAndReplace();
	$upload->renameImage();
	$upload->moveFile();
}

if(isset($_GET['method']) && $_GET['method'] == "ajax"){
	if($upload->getError() == 1){
		echo "error: Kein Bild";
	} else {
		$bilder = leseverzeichnis(dirname(__FILE__));
		foreach ($bilder as $bild) {
			?>
			<li>
				<a href="<?php echo $bild['link'];?>">
				<img src="<?php echo $bild['link'];?>" height="100" alt="Vorschau" /></a>
				<span><?php echo $bild['name']; ?> </span>
				<form action="deleteFile.php" method="POST">
					<input type="hidden" name="filename" value="<?php echo $bild['basename']; ?>">
					<input type="submit" value="Bild löschen">
				</form>
			</li>
			<?php
		}
	}
} else {
	header("Location: index.php?error=".$upload->getError());	// Sonst Weiterleitung zur Startseite
}
?>
