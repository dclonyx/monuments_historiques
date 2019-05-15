<?php
$edifice = addslashes($_GET["edifice"]);
$commune = addslashes($_GET["commune"]);
include ('./connect.php');
$tabnewPhoto = array();

$stmtnewPhotos = $pdo->query("SELECT IMAGE FROM PHOTOS where COMMUNE='$commune' and EDIFICE='$edifice'");
while ($rownew = $stmtnewPhotos->fetch()){
    if ($rownew != FALSE) {
        $tabnewPhoto[] = array($rownew['IMAGE']);
    };
};
$stmtnewPhotos->closeCursor();
?>