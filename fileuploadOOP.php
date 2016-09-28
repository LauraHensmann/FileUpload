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
	
	/** 
	* @var int 
	*/
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
	* @var String
	*/
	private $uploadFolder = 'uploads';
	
	/**
	 *@var Array
	 */
	 private $notAllowed = array(".", "..", "_notes", "Thumbs.db");
	 
	/**
	 * Konstruktor mit Übergabe von Datei und neuem Dateinamen
	 * @param array $file Dateiname des Uploadfiles
	 * @param string $new_filename Der neu zu setzende Dateiname
	 */
	public function __construct($file, $new_filename) {
		$this->file = $file;
		$this->newImageName = $new_filename;
	}
	
	/**
	* Endungen werden zu Kleinbuchstaben 
	*/
	public function normaliseExtension() {
		$this->imageExtension = strtolower (end(explode(".",$this->file["name"])));
	}
	/**
	 * Kopiert die Datei vom Temporären Ablageort zum Zielverzeichnis inkl. umbenennen
	 *
	 * @return
	 */

	public function moveFile() {
	try{
		if(!move_uploaded_file($this->file["tmp_name"],$this->uploadFolder."\\".$this->imageName.".".$this->imageExtension)){
			$this->setError("1");
		return false;
		}
	} catch(Exception $e){
		$this->setError("1");
		return false;
	}
		return true;
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
	* set error
	* @var String $imageName
	*/
	private function setError($error){
	$this->error = $error;
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
	
	/**
	 * Leseverzeichnis
	 *
	 * @return string
	 */
	public function leseverzeichnis()
	{
		$bilder = $this->readFolder($this->uploadFolder);
		

		$result = "";
		foreach ($bilder as $bild) {
			$result = $result.'
		<li>
			<a href="'.$bild['link'].'">
			<img src="'.$bild['link'].'" height="100" alt="Vorschau" /></a>
			<span>'.$bild['name'].'</span>
			<form action="deleteFile.php" method="POST">
				<input type="hidden" name="filename" value="'.$bild['basename'].'">
				<input type="submit" value="Bild löschen">
			</form>
		</li>';
		}
		return $result;
	}
	
	private function readFolder($ordner){
		$basedir = dirname(__FILE__);
		$absoluterpfad = $basedir."\\".$ordner."\\";
		
		
		
		$allebilder = scandir($absoluterpfad);
		$bilder = array();
		
		
		foreach ($allebilder as $bild) {
			$bildinfo = pathinfo($absoluterpfad."/".$bild);
			if (in_array ($bild, $this->notAllowed)){
				continue;
			}
			$size = ceil(filesize($absoluterpfad."/".$bild)/1024);
			$bilder[] = array(
				'link' => $ordner."/".$bildinfo['basename'],
				'name' =>$bildinfo['filename'],
				'basename' => $bildinfo['basename']
			);
		}	
	
		return $bilder;
	
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