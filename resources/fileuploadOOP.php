<?php

/**
* File uploader Class
*/
class Upload 
{
	/**
	* @var array
	*/
	private $file;
	
	/** @var int */
	private $error;
	
	/**
	* @var String
	*/
	private $imageName;
	
	/**
	* @var String
	*/
	private $newImageName;
	
	/**
	* @var String
	*/
	private $imageExtension;
	

	/**
	 * Konstruktor mit Übergabe von Datei und neuem Dateinamen
	 * 
	 * @param array $file Dateiname des Uploadfiles
	 * @param string $new_filename Der neu zu setzende Dateiname
	 */
	 
	public function __construct($file, $new_filename) {
		$this->file = $file;
		$this->newImageName = $new_filename;
		$this->imageExtension = strtolower(end(explode(".", $this->file["name"])));
	}

	/**
	* Kopiert die Datei vom Temporären Ablageort zum Zielverzeichnis inkl. umbenennen
	*/
	public function moveFile() {
		move_uploaded_file($this->file["tmp_name"],"uploads/".$this->imageName.".".$this->imageExtension);
	}
	
	/**
	 * Überprüfung ob es sich um ein Bild handelt 
	 * 
	 * @return bool
	 */
	public function isImage() {
		if (!exif_imagetype($this->file['tmp_name'])) {
			$this->error = 1;
			return false;
		}
		return true;
	}
	
	/**
	* get Upload errors
	* @return int
	*/
	public function getError() {
		return $this->error;
	}
	
	/**
	 * setzt den übergebenen Dateinamen, sonst den von der Originaldatei
	 */

	public function checkIfNoNameIsSetAndReplace() {
		if(isset($this->newImageName) && !empty($this->newImageName)) { 
			$this->imageName = escapeshellcmd($this->newImageName);
		} else {
			$this->imageName = escapeshellcmd($this->file['name']);
			$this->imageName = strtolower(explode(".", $this->file["name"])[0]);
		}
	}
	
	/**
	* set image name
	* @var String $imageName
	*/
	private function setImageName($imageName){
		$pattern = '/([^\w\d._-])/i';
		$replace = '_';
		preg_match($pattern, $imageName);
		$this->imageName = preg_replace($pattern, $replace, $imageName);
	}
	
	public function leseverzeichnis ()
	{
		if(isset($_GET['method']) && $_GET['method'] == "ajax"){
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
	}

	/** 
	* Falls Datei schon existiert wird die nächst
	* Freie Nummer angehangen welche noch frei ist
	*/
	public function renameImage()
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
}