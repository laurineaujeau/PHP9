<?php
//Aidée par Pierre BOISLEVE
include "connexpdo.php";
$base='pgsql:dbname=notes;host=127.0.0.1;port=5432';
$user = 'postgres';
$password = 'isen2018';

$idcon = connexpdo($base,$user,$password);

header ("Content-type: image/png");
$image = imagecreate(500,500);
 //deinition des couleurs
$gris = imagecolorallocate($image, 125, 125, 125);
$blanc = imagecolorallocate($image, 255, 255, 255);
$vert = imagecolorallocate($image, 0, 255, 0);
$rouge = imagecolorallocate($image, 255, 0, 0);

$nbActionsAls = 0;
$nbActionsFor = 0;
//Initialisation des tableaux qui permettront de séparer les données de Als et de For
$grapheAls=array();
$grapheFor=array();

$query = "SELECT * FROM statistique";
$result = $idcon->query($query);

foreach ($result as $data){
    if ($data['action']=="Als"){
        array_push($grapheAls, $data['valeur']); // Je place dans un tableau toutes les valeurs correspondant au graphe Als
        $nbActionsAls++; //Je calcule le nombre total de point a placer dans mon graphe
    }
    if ($data['action']=="For"){
        array_push($grapheFor, $data['valeur']);// Je place dans un tableau toutes les valeurs correspondant au graphe For
        $nbActionsFor++;//Je calcule le nombre total de point a placer dans mon graphe
    }
}
//Als
for ($i=0; $i<$nbActionsAls; $i++){//Ce for permet de placer chaque point de du graphe
    imageline($image, $i*50, (500-$grapheAls[$i]*7), ($i+1)*50, (500-$grapheAls[$i+1]*7), $blanc);
    //paramètre 1 = Image dans laquelle sont placés les points du graphe
    //paramètre 2 = x1
    //paramètre 3 = y1 : coordonnées du premier point /!\ le (0,0) se trouve en haut a gauche de l'image
    //paramètre 4 = x2 :
    //paramètre 5 = y2 : coordonnées du second point
    //paramètre 6 = couleurs des points
}
//For
for ($i=0; $i<$nbActionsFor; $i++){
    imageline($image, $i*50, (500-$grapheFor[$i]*7), ($i+1)*50, (500-$grapheFor[$i+1]*7), $rouge);
}

imagestring($image, 7, 15, 10, "Cours des actions Als et For en 2010", $vert);
imagestring($image, 4, 120, 475, "Als", $blanc);//ecrit Als en blanc en bas de page
imagestring($image, 4, 70, 475, "For" , $rouge); //ecrit for en rouge en bas de page

imagepng($image);
?>
