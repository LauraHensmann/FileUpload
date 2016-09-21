<?php
require_once('scandir.php');
$bilder = leseverzeichnis(dirname(__FILE__));

if(isset($_GET['error']) && $_GET['error'] == 1){
	echo "Invalid File Type";
}

if (isset($_GET['error2'])){
	if($_GET['error2'] && $_GET['error2'] == 1){
		echo "No File Selected";
	}
}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>File upload</title>
		<meta charset="utf-8"/>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="resources/css/style.css">
		<script type="text/javascript" src="resources/js/fileupload.js"></script>
	</head>

	<body>
		<header>
			<h1 class="page-title">File upload</h1>
			<h3 class="page-description">Nur upload von Bildern möglich. </h3>
			<p>
				Wählen Sie eine Bilddatei (nur .jpg, .jpeg, .png oder .gif) von Ihrem Rechner aus:
				<form action="runfileuploadOOP.php" method="post" enctype="multipart/form-data"><label for="file">Filename:</label> 
					<input type="file" name="file" id="file" /> <br />
					Bildname: <input type="text" name="imagename" id="imagename"> <br /> 
				
					<div id="dropzone" style="background-color: #000000; padding: 10px;">
						Dateien hierher ziehen (Mehrfachupload möglich)
					</div>
					<div id="list"></div>
						
					<button href="#" class="button" type="reset">Feld leeren</button>
					<input class="button" type="submit" value="absenden" name="submit">			
				</form>
			</p>
		</header>

		<section>
			<div>
				<ul	class="gallery" id="gallery">
					<?php
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
					?>
				</ul>		
			</div>
		</section>
	</body>
</html>