<?php
require_once('fileuploadOOP.php');

$verzeichnis = new Upload(null, "");

/*
if(isset($_GET['error']) && $_GET['error'] == 1){
	echo "Invalid File Type";
}

if (isset($_GET['error2'])){
	if($_GET['error2'] && $_GET['error2'] == 1){
		echo "No File Selected";
	}
}
if (isset($_GET['error3'])){
	if($_GET['error3'] && $_GET['error3'] == 1){
		echo "Something went wrong";
	}
}	
*/
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
				<div id="dropzone" style="background-color: white; padding: 25px;">
					Dateien hierher ziehen
				</div>
			</p>
		</header>

		<section>
			<div>
				<ul id="gallery">
					<?php
					echo $verzeichnis->leseverzeichnis();
					?>
				</ul>		
			</div>
		</section>
	</body>
</html>