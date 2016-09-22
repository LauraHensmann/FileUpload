<?php

class Upload 
{
	
	private $file;
	private $error;
	private $imageName;
	private $newImageName;
	private $imageExtension;
	
	//Konstruktor mit Übergabe von Datei und neuem Dateinamen
	public function __construct($file, $new_filename) {
		$this->file = $file;
		$this->newImageName = $new_filename;
		$this->imageExtension = strtolower(end(explode(".", $this->file["name"])));
	}

	//Kopiert die Datei vom Temporären Ablageort zum Zielverzeichnis inkl. umbenennen
	public function moveFile() {
		move_uploaded_file($this->file["tmp_name"],"uploads/".$this->imageName.".".$this->imageExtension);
	}
	
	//Überprüfung ob es sich um eine Datei handelt 
	public function isImage() {
		if (!exif_imagetype($this->file['tmp_name'])) {
			$this->error = 1;
			return false;
		}
		return true;
	}
	
	public function getError() {
		return $this->error;
	}
	
	//Nimmt den übergebenen neuen Dateinamen => Sonst den von der Originaldatei
	public function checkIfNoNameIsSetAndReplace () {
		if(isset($this->newImageName) && !empty($this->newImageName)) { 
			$this->imageName = escapeshellcmd($this->newImageName);
		} else {
			$this->imageName = escapeshellcmd($this->file['name']);
			$this->imageName = strtolower(explode(".", $this->file["name"])[0]);
		}
	}
	// Sucht nach "verbotenen" Zeichen und ersetzt diese mit "_"
	private function setImageName($imageName){
		$pattern = '/([^\w\d._-])/i';
		$replace = '_';
		preg_match($pattern, $imageName);
		$this->imageName = preg_replace($pattern, $replace, $imageName);
	}

	//Falls Datei schon existiert wird die nächst Freie Nummer angehangen welche noch frei ist
	public function renameImage ()
	{
		$deployOK = 0; $deployTries = 1;
		while($deployOK == 0){
			if(file_exists("uploads/" . $this->imageName.".".$this->imageExtension)) {
				if(strpos($this->imageName, '_'.$deployTries) !== false) {  
					$this->imageName = rtrim($this->imageName,"_".$deployTries);
					$deployTries++;									
					$this->imageName = $this->imageName."_".$deployTries;		
				}else{						
					$this->imageName = $this->imageName."_".$deployTries;		
				}
				continue;
			} 
			$deployOK = 1;										
		}	
	}
	public function leseverzeichnis ()
	{
		if(isset($_GET['method']) && $_GET['method'] == "ajax"){
		if($upload->getError() == 1){
			echo "error: Kein Bild";
		} else {
			$bilder = leseverzeichnis(dirname(__FILE__));
			foreach ($bilder as $bild) {
				?>
				<li>
					<a href="<?php echo $this->bild['link'];?>">
					<img src="<?php echo $this->bild['link'];?>" height="100" alt="Vorschau" /></a>
					<span><?php echo $this->bild['name']; ?> </span>
					<form action="deleteFile.php" method="POST">
						<input type="hidden" name="filename" value="<?php echo $bild['basename']; ?>">
						<input type="submit" value="Bild löschen">
					</form>
				</li>
				<?php
			}
		}
	} else {
		header ("Location: index.php");
exit ();
	}
	}
}