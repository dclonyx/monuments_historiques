<?php
include ('./connect.php');
$dep = $_GET['dep'];
// VILLE PREFECTURE

$stmt = $pdo->query("SELECT PREFECTURE FROM PREFECTURE where NUMDEPT='$dep'");
while ($row = $stmt->fetch()) {
    $result = addslashes($row['PREFECTURE']);
}

// PREFECTURE GPS

$stmt = $pdo->query("SELECT GPS_LAT, GPS_LNG FROM VILLE where NOMVILLE='$result' AND NUMDEPT='$dep'");
while ($row = $stmt->fetch()){
    $glat = $row['GPS_LAT'];
    $glng = $row['GPS_LNG'];
}

// Recuperer nom de chaque ville présente dans le fichier PHOTOS pour le département
// et selectionne sa latitude et sa longitude dans le fichier VILLE
$tabPhoto = array();


$stmtPhotos = $pdo->query("SELECT * FROM PHOTOS where NUMDEPT='$dep' GROUP BY EDIFICE");
while ($row = $stmtPhotos->fetch()){
    $resultat = addslashes($row['COMMUNE']);
    // Si le nom contient une particule entre parenthese, on enles celle ci et on remet tout dans l'ordre
    if(preg_match("#\(Le\)#", $resultat) || preg_match("#\(Les\)#", $resultat) || preg_match("#\(La\)#", $resultat)){
        preg_match('#\(+(.*)\)+#', $resultat, $result);
        $texte = preg_replace( '~\(.*\)~' , "", $resultat);   
        $resultat = $result[1]." ". $texte; 
    }
    $stmtVille = $pdo->query("SELECT NOMVILLE, GPS_LAT, GPS_LNG FROM VILLE where NOMVILLE='$resultat' AND NUMDEPT='$dep'");
    $row1 = $stmtVille->fetch();
    if ($row1 != FALSE) {
        $tabPhoto[] = array($row['COMMUNE'],$row1['GPS_LAT'],$row1['GPS_LNG'],$row['EDIFICE'],$row['LEGENDE'],$row['AUTEUR'],$row['DATE'],$row['MINIATURE']);
    };
};

$stmt->closeCursor();
$stmtPhotos->closeCursor();
?>