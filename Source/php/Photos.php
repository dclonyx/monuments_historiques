<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/style.css" media="screen" rel="stylesheet" type="text/css" />
    <?php
    include ('./trt-photos.php');
    ?>
    <title>Photos</title>
</head>
<body>
    <header>
        <h1>Photos</h1>
    </header> 
    <div class="photos">
        <?php
            for ($i=0; $i < count($tabnewPhoto); $i++) { 
                echo "<div class=bloc><img src=".$tabnewPhoto[$i][0]."></div>";
            }
        ?>
        <div class="bouton">
            <a href="departement.php?dep=<?php echo($dep);?>"><img src="../img/arrow-back.png" alt="bouton"></a>
        </div>
    </div>
    <footer class="footer_photo">
        <span class="lien"class="lien" href="#">Copyright © 2019 Dcl-Aramis</span>
        <a class="lien" href="./Source/html/mention.html">Mentions légale</a>
    </footer>
</body>
</html>