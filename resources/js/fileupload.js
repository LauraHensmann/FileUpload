window.onload = function () {
	xhr = new XMLHttpRequest();    										// den AJAX Request anlegen (ohne var weil Global => um readyState auslesen zu können)
	var dropZone = document.getElementById('dropzone');					//HTML Element für das Drop Event raussuchen
	if(dropZone){														// Nur wenn DropZone existiert
		dropZone.addEventListener('dragover', handleDragOver, false);	// Event für DragOver => Um später Styles anpassen zu können => HOVER Effekt
		dropZone.addEventListener('drop', dateiauswahl, false);			// Event-Listener für DROP
	}
		
	function handleDragOver(event){
		// TODO: EFFEKT/STYLE Änderung an der DropZone
		event.stopPropagation();
		event.preventDefault();
	}

	function dateiauswahl(event){
		event.stopPropagation();
		event.preventDefault();											//stopPropagation & preventDefault => Um das Standardverhalten vom Browser zu deaktivieren (Fileopen bei Drop)
		var gewaehlteDateien = event.dataTransfer.files; 				// FileList Objekt
		for (var i = 0, f; f = gewaehlteDateien[i]; i++) {
			uploadFile(f);	
		}
		
		//Hier später die Galerie neu LADEN!
		
	}

	function uploadFile(file)
	{
		xhr.open('POST', 'runfileuploadOOP.php?method=ajax');    		// Angeben der URL und des Requesttyps
		var formdata = new FormData();    								// Anlegen eines FormData Objekts zum Versenden unserer Datei
		formdata.append('file', file);  								// Anhängen der Datei an das Objekt
		xhr.onreadystatechange = updateGalery;							// Wenn sich der Status vom Request ändert => updateGalery
		var imagenameField = document.getElementById('imagename');
		if(imagenameField.value != undefined && imagenameField.value != ""){
			formdata.append('imagename', imagenameField.value); 		// Wenn Bildname-Feld befüllt ist wird es auch im AJAX Request mitgegeben
		}

		xhr.send(formdata);    // Absenden des Requests
	}
	
	/*
	SIDENOTE:
	 0: Der Request wurde noch nicht eingeleitet
    1: Der Request wurde gesetzt aber noch nicht gesendet
    2: Der Request wurde gesendet und wird gerade bearbeitet
    3: Der Request wird noch bearbeitet, Teildaten stehen aber zur Bearbeitung bereit
    4: Der Request (=Response) ist abgeschlossen, alle Daten sind verfügbar
	*/
	function updateGalery(){
		if (xhr.readyState == 4)   //Erst bei ReadyState 4 (Response ist da !!!)
		{
			if(xhr.responseText != "error"){
				document.getElementById('gallery').innerHTML = xhr.responseText;			//Überschreibe die Galerie mit übergebenen HTML => SPäter per JSON/XML 
			} else {
				alert("Da gab es irgendwo einen Fehler!");
			}			
		}
	}
	
}