<?php
function leseverzeichnis($dir){
	$basedir = $dir;
	$ordner = "/uploads";
	$absoluterpfad = $basedir. $ordner;

	$allebilder = scandir($absoluterpfad);
	$bilder = array();

	foreach ($allebilder as $bild) {
		$bildinfo = pathinfo($absoluterpfad."/".$bild);
		if ($bild == "." || $bild == ".." || $bild == "_notes" || $bildinfo['basename'] == "Thumbs.db"){
			continue;
		}
		$size = ceil(filesize($absoluterpfad."/".$bild)/1024);
		$bilder[] = array(
			'link' => ".".$ordner."/".$bildinfo['basename'],
			'name' =>$bildinfo['filename'],
			'basename' => $bildinfo['basename']
		);
	}	
	
	return $bilder;
	
}

?>