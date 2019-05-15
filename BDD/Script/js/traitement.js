
function createbase() {
    document.getElementById("button").style.display = "none";  
    document.getElementById("explain").style.display = "none";  
    document.getElementById("text").style.display = "block";
    document.getElementById("loader").style.display = "block";
    var xhttp = new XMLHttpRequest;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200){
            document.getElementById("text").style.display = "none";
            document.getElementById("loader").style.display = "none";
            document.getElementById("resultat").style.display = "block";
        }
    };
    xhttp.open("GET","./traitement.php", true);
    xhttp.send();

}


