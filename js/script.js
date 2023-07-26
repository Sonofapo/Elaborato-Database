function simulate() {
	const xhttp = new XMLHttpRequest();
	xhttp.onload = function() {
		document.querySelector("div#play-outcome").style.visibility = "visible";
		document.querySelector("div#play-outcome").innerHTML = this.responseText;
		setTimeout(hide, 3000);
	}
	xhttp.open("GET", "index.php?action=stats&mode=play");
	xhttp.send();
}

function hide() {
	document.querySelector("div#play-outcome").style.visibility = "hidden";
}