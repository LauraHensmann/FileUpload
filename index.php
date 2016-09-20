<?php
$basedir = dirname(__FILE__);
$ordner = "/uploads";
$absoluterpfad = $basedir. $ordner;

$allebilder = scandir($absoluterpfad);
$bilder = array();

if(isset($_GET['error']) && $_GET['error'] == 1){
	echo "Invalid File Type";
}

if (isset($_GET['error2'])){
	if($_GET['error2'] && $_GET['error2'] == 1){
		echo "No File Selected";
	}
}

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
?>

<!DOCTYPE html>
<html>
	<head>
		<title>File upload</title>
		<meta charset="utf-8"/>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	</head>

	<body>
		<header>
			<h1 class="page-title">File upload</h1>
			<h3 class="page-description">Nur upload von Bildern möglich. </h3>
			<p>
				Wählen Sie eine Bilddatei (nur .jpg, .jpeg, .png oder .gif) von Ihrem Rechner aus:
				<form action="runfileuploadOOP.php" method="post" enctype="multipart/form-data"><label for="file">Filename:</label> 
					<input type="file" name="file" id="file" /> <br />
					Bildname: <input type="text" name="imagename"> <br /> 
				
					<div id="dropzone" style="background-color: #000000; padding: 100px;">
						Dateien hierher ziehen
					</div>
					<div id="list"></div>
						
					<script>
					
					var dropZone = document.getElementById('dropzone');
					dropZone.addEventListener('dragover', handleDragOver, false);
					dropZone.addEventListener('drop', dateiauswahl, false);
					console.log("fff");
					function handleDragOver(event){
						event.stopPropagation();
						event.preventDefault();
						console.log("a");
						//Kannste was mit machen!
					}
					
					function dateiauswahl(event){
						event.stopPropagation();
						event.preventDefault();
						var gewaehlteDateien = event.dataTransfer.files; // FileList Objekt
						var output = [];
						for (var i = 0, f; f = gewaehlteDateien[i]; i++) {
							uploadFile(f);	
						}
						document.getElementById('list')
							.innerHTML = '<ul>' + output.join('') + '</ul>';
						
						

					}
					
					function uploadFile(file)
					{
						var xhr = new XMLHttpRequest();    // den AJAX Request anlegen
						xhr.open('POST', 'runfileuploadOOP.php?method=ajax');    // Angeben der URL und des Requesttyps
						var formdata = new FormData();    // Anlegen eines FormData Objekts zum Versenden unserer Datei
						formdata.append('file', file);  // Anhängen der Datei an das Objekt
						//formdata.append('imagename','huhu');
						xhr.send(formdata);    // Absenden des Requests
					}

						
					</script>
						

					<button href="#" class="button" type="reset">Feld leeren</button>
					<input class="button" type="submit" value="absenden" name="submit">			
				</form>
			</p>
		</header>

		<section>
			<div>
				<ul	class="gallery">
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