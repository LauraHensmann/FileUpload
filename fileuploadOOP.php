<?php

class Upload {
	
	private $file;
	private $error;
	private $imageName;
	private $imageExtension;
	
	public function __construct() {
		$this->file = $_FILES['file'];
		$this->setImageName($_POST['imagename']);
		$this->imageExtension = strtolower(end(explode(".", $_FILES["file"]["name"])));
	}

	public function moveFile() {
		move_uploaded_file($this->file["tmp_name"],"uploads/".$this->imageName.".".$this->imageExtension);
	}
	
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
	
	public function checkIfNoNameIsSetAndReplace () {
		if(isset($_POST['imagename']) && !empty($_POST['imageName'])) { 
		$this->imageName = escapeshellcmd($_POST['imageName']);
	} else {
		$this->imageName = escapeshellcmd($_FILES['file']['name']);
		}
	}
	
	private function setImageName($imageName){
		$pattern = '/([^\w\d._-])/i';
		$replace = '_';
		preg_match($pattern, $imageName);
		$this->imageName = preg_replace($pattern, $replace, $imageName);
	}
	
	 public function renameImage ($imageName){
			$deployOK = 0; $deployTries = 1;
	while($deployOK == 0){

	if(file_exists("uploads/" . $this->imageName.".".$extension)) {
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