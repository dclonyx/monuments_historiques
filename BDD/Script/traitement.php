<?php

  // utilisation d'un fichier Json pour récupérer les informations de connexion
  $file_json = file_get_contents("config.json");
  $parsed_json = json_decode($file_json, true);
  $dbadmin = $parsed_json['dbadmin'];
  $adminPass = $parsed_json['adminPass'];
  $servername = $parsed_json['servername'];
  $dbname = $parsed_json['dbname'];
  $username = $parsed_json['username'];
  $password = $parsed_json['password'];

  // Se connecter à sql
  $conn = new PDO("mysql:host=$servername", $dbadmin, $adminPass);

  // Supprimer la base de données si elle existe
  $requete_pdo = "DROP DATABASE IF EXISTS ".$dbname;
  $conn->prepare($requete_pdo)->execute();

  // Créer la base de données
  $requete_pdo = "CREATE DATABASE ".$dbname;
  $conn->prepare($requete_pdo)->execute();

  // Utiliser la nouvelle database
  $requete_pdo = "USE ".$dbname;
  $conn->prepare($requete_pdo)->execute();  

//   $requete_pdo = "CREATE USER ".$username."@".$servername." IDENTIFIED WITH mysql_native_password AS ".$password." GRANT USAGE ON *.* TO ".$username."@".$servername." REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
//   $conn->prepare($requete_pdo)->execute(); 

//   $requete_pdo = "GRANT ALL PRIVILEGES ON ".$dbname.".* TO ".$username."@".$servername;
//   $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "DROP TABLE IF EXISTS DEPARTEMENT" ;
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "CREATE TABLE DEPARTEMENT (ID_DEPT INT(10) AUTO_INCREMENT NOT NULL,
      NUMDEPT VARCHAR(3) NOT NULL,
      NOMDEPT VARCHAR(255),
      NOMDEPTMAJ VARCHAR(255),
      SLUGDEPT VARCHAR(255),
      PRIMARY KEY (ID_DEPT)) ENGINE=InnoDB";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "DROP TABLE IF EXISTS PHOTOS" ;
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "CREATE TABLE PHOTOS (ID_PHOTO INT(10) AUTO_INCREMENT NOT NULL,
      NUMDEPT VARCHAR(3) NOT NULL,
      COMMUNE VARCHAR(255),
      EDIFICE VARCHAR(255),
      LEGENDE VARCHAR(255),
      AUTEUR VARCHAR(255),
      DATE VARCHAR(255),
      MINIATURE VARCHAR(255),
      IMAGE VARCHAR(255),
      PRIMARY KEY (ID_PHOTO)) ENGINE=InnoDB";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "DROP TABLE IF EXISTS VILLE" ;
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "CREATE TABLE VILLE (ID_VILLE INT(10) AUTO_INCREMENT NOT NULL,
      NUMDEPT VARCHAR(3) NOT NULL,
      NOMVILLE VARCHAR(255),
      SLUGVILLE VARCHAR(255),
      GPS_LAT FLOAT(4),
      GPS_LNG FLOAT(4),
      PRIMARY KEY (ID_VILLE)) ENGINE=InnoDB";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "DROP TABLE IF EXISTS PREFECTURE" ;
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "CREATE TABLE PREFECTURE (ID_PREF INT(10) AUTO_INCREMENT NOT NULL,
      NUMDEPT VARCHAR(3) NOT NULL,
      NOMDEPT VARCHAR(255),
      PREFECTURE VARCHAR(255),
      REGION VARCHAR(255),
      PRIMARY KEY (ID_PREF)) ENGINE=InnoDB";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE \`VILLE\` ADD INDEX(\`NUMDEPT\`)";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE \`DEPARTEMENT\` ADD INDEX(\`NUMDEPT\`)";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE \`PHOTOS\` ADD INDEX(\`NUMDEPT\`)";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE \`PREFECTURE\` ADD INDEX(\`NUMDEPT\`)";
  $conn->prepare($requete_pdo)->execute();


  $requete_pdo = "ALTER TABLE VILLE ADD CONSTRAINT FK_VILLE_NUMDEPT FOREIGN KEY (NUMDEPT) REFERENCES DEPARTEMENT (NUMDEPT)";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE DEPARTEMENT ADD CONSTRAINT FK_DEPARTEMENT_NUMDEPT FOREIGN KEY (NUMDEPT) REFERENCES PREFECTURE (NUMDEPT)";
  $conn->prepare($requete_pdo)->execute();

  $requete_pdo = "ALTER TABLE PHOTOS ADD CONSTRAINT FK_PHOTOS_NUMDEPT FOREIGN KEY (NUMDEPT) REFERENCES DEPARTEMENT (NUMDEPT)";
  $conn->prepare($requete_pdo)->execute();

  // se reconnecter avec le user créée pour la base
  // connexion PDO
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec('SET NAMES utf8');



  ///////////////////////////////////////
  // Pour remplir la table DEPARTEMENT //
  ///////////////////////////////////////

  $fichier = 'Departement.csv';
  // inserer les fichiers CSV
  // On instancie l'objet SplFileObject
  $csv = new SplFileObject($fichier); 
  // On indique que le fichier est de type CSV
  $csv->setFlags(SplFileObject::READ_CSV); 
  // On indique le caractère délimiteur, ici c'est la virgule
  $csv->setCsvControl(','); 

  // pour ignorer la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=0");
  $requete_pdo->execute();
  // remplissage de la table
  foreach($csv as $ligne){
      if ($ligne[0]!=NULL)
      {
      // $ligne est un array qui contient chaque champs    
      $nomdept=addslashes($ligne[2]);
      $nomdeptmaj=addslashes($ligne[3]);
      $slugdept=addslashes($ligne[4]);
      // insert SQL dans la table
      $requete_pdo = $conn->prepare("INSERT IGNORE INTO DEPARTEMENT (ID_DEPT, NUMDEPT, NOMDEPT, NOMDEPTMAJ, SLUGDEPT) VALUES ('$ligne[0]','$ligne[1]', '$nomdept', '$nomdeptmaj', '$slugdept')");
      $requete_pdo->execute();
      }
      else
      {
          break;
      }
  }
  // pour remettre la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=1");
  $requete_pdo->execute();
//   echo ("<br>La table DEPARTEMENT a été remplie");

  //////////////////////////////////////
  // Pour remplir la table PREFECTURE //
  //////////////////////////////////////

  $fichier = 'Prefecture.csv';
      // inserer les fichiers CSV
  // On instancie l'objet SplFileObject
  $csv = new SplFileObject($fichier); 
  // On indique que le fichier est de type CSV
  $csv->setFlags(SplFileObject::READ_CSV); 
  // On indique le caractère délimiteur, ici c'est la virgule
  $csv->setCsvControl(','); 

  // pour ignorer la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=0");
  $requete_pdo->execute();
  // remplissage de la table
  foreach($csv as $ligne){

      if ($ligne[0]!=NULL)
      {
      // $ligne est un array qui contient chaque champs  
      $nomdept=addslashes($ligne[2]);
      $prefecture=addslashes($ligne[3]);
      $region=addslashes($ligne[4]);

      //insert SQL dans la table
      $requete_pdo = $conn->prepare("INSERT IGNORE INTO PREFECTURE (ID_PREF, NUMDEPT, NOMDEPT, PREFECTURE, REGION) VALUES ('$ligne[0]','$ligne[1]','$nomdept','$prefecture','$region')");
      $requete_pdo->execute();
      }
      else
      {
          break;
      }
  }
  // pour remettre la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=1");
  $requete_pdo->execute();
//   echo ("<br>La table PREFECTURE a été remplie");

  //////////////////////////////////
  // Pour remplir la table VILLE //
  //////////////////////////////////

  $fichier = 'Ville.csv';
  // inserer les fichiers CSV
  // On instancie l'objet SplFileObject
  $csv = new SplFileObject($fichier); 
  // On indique que le fichier est de type CSV
  $csv->setFlags(SplFileObject::READ_CSV); 
  // On indique le caractère délimiteur, ici c'est la virgule
  $csv->setCsvControl(','); 

  // pour ignorer la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=0");
  $requete_pdo->execute();
  // remplissage de la table
  foreach($csv as $ligne){


      if ($ligne[0]!=NULL)
      {
      // $ligne est un array qui contient chaque champs  
      $nomville=addslashes($ligne[2]);
      $slugville=addslashes($ligne[3]);
      //insert SQL dans la table
      $requete_pdo = $conn->prepare("INSERT IGNORE INTO VILLE (ID_VILLE, NUMDEPT, NOMVILLE, SLUGVILLE, GPS_LAT, GPS_LNG) VALUES ('$ligne[0]','$ligne[1]','$nomville','$slugville','$ligne[4]','$ligne[5]')");
      $requete_pdo->execute();
      }
      else
      {
          break;
      }
  }
  // pour remttre la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=1");
  $requete_pdo->execute();
//   echo ("<br>La table VILLE a été remplie");

  //////////////////////////////////
  // Pour remplir la table PHOTOS //
  //////////////////////////////////

  $fichier = 'Photos.csv';
  // inserer les fichiers CSV
  // On instancie l'objet SplFileObject
  $csv = new SplFileObject($fichier); 
  // On indique que le fichier est de type CSV
  $csv->setFlags(SplFileObject::READ_CSV); 
  // On indique le caractère délimiteur, ici c'est la virgule
  $csv->setCsvControl(','); 

  // pour ignorer la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=0");
  $requete_pdo->execute();
  // remplissage de la table
  foreach($csv as $ligne){

      if ($ligne[0]!=NULL)
      {
      // $ligne est un array qui contient chaque champs  
      $commune=addslashes($ligne[2]);
      $edifice=addslashes($ligne[3]);
      $legende=addslashes($ligne[4]);
      $auteur=addslashes($ligne[5]);
      $miniature=addslashes($ligne[7]);
      $image=addslashes($ligne[8]);
      //insert SQL dans la table
      $requete_pdo = $conn->prepare("INSERT IGNORE INTO PHOTOS (ID_PHOTO, NUMDEPT, COMMUNE, EDIFICE, LEGENDE, AUTEUR, DATE, MINIATURE, IMAGE) VALUES ('$ligne[0]','$ligne[1]','$commune','$edifice','$legende','$auteur','$ligne[6]','$miniature','$image')");
      $requete_pdo->execute();
      }
      else
      {
          break;
      }
  }
  // pour remettre la contrainte de clé etrangère
  $requete_pdo = $conn->prepare("set FOREIGN_KEY_CHECKS=1");
  $requete_pdo->execute();
//   echo ("<br>La table PHOTOS a été remplie");

  $requete_pdo->closeCursor();


?>