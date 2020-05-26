<?php
//Aidée par Pierre BOISLEVE
include "connexpdo.php";
$base='pgsql:dbname=notes;host=127.0.0.1;port=5432';
$user = 'postgres';
$password = 'isen2018';

$idcon = connexpdo($base,$user,$password);

header ("Content-type: image/png");
$image = imagecreate(600,200); //Création de l'image
//deinition des couleurs
$gris = imagecolorallocate($image, 125, 125, 125);
$blanc = imagecolorallocate($image, 255, 255, 255);
$orange = imagecolorallocate($image, 255, 128, 0);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$noir = imagecolorallocate($image, 0, 0, 0);

$notesE1=0;
$nbNotesE1=0;
$notesE2=0;
$nbNotesE2=0;
$nbNotes=0;
//Initialisation des tableaux qui permettront de séparer les données de E1 et de E2
$grapheE1=array();
$grapheE2=array();

$query = "SELECT * FROM notes";
$result = $idcon->query($query);

foreach ($result as $data){
    if ($data['etudiant']=="E1"){
        $notesE1 += $data['note'];
        array_push($grapheE1, $data['note']);  // Je place dans un tableau toutes les valeurs correspondant au graphe E1
        $nbNotesE1++; //Je calcule le nombre total de point a placer dans mon graphe
    }

    if ($data['etudiant']=="E2"){
        $notesE2 += $data['note'];
        array_push($grapheE2, $data['note']);// Je place dans un tableau toutes les valeurs correspondant au graphe E2
        $nbNotesE2++; //Je calcule le nombre total de point a placer dans mon graphe
    }
    $nbNotes++;
}
$moyenneE1=$notesE1/$nbNotesE1;
$moyenneE2=$notesE2/$nbNotesE2;

for ($i=0; $i<$nbNotesE1; $i++){//Ce for permet de placer chaque point de du graphe
    imageline($image, $i*60, (100-$grapheE1[$i]), ($i+1)*60, (100-$grapheE1[$i+1]), $blanc);
    //paramètre 1 = Image dans laquelle sont placés les points du graphe
    //paramètre 2 = x1
    //paramètre 3 = y1 : coordonnées du premier point /!\ le (0,0) se trouve en haut a gauche de l'image
    //paramètre 4 = x2 :
    //paramètre 5 = y2 : coordonnées du second point
    //paramètre 6 = couleurs des points
}
for ($i=0; $i<$nbNotesE2; $i++){
    imageline($image, $i*60, (100-$grapheE2[$i]), ($i+1)*60, (100-$grapheE2[$i+1]), $bleu);
}

imagestring($image, 7, 180, 10, "Notes des etudiants E1 et E2", $noir);
imagestring($image, 4, 350, 160, "Moyenne des notes de E1 : ".$moyenneE1 , $noir);
imagestring($image, 4, 350, 180, "Moyenne des notes de E2 : ".$moyenneE2 , $noir);
imagestring($image, 4, 25, 140, "E1" , $blanc);
imagestring($image, 4, 60, 140, "E2", $bleu);

imagepng($image);
?>