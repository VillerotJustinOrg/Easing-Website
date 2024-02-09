<?php
session_start();
// echo "<pre>" . print_r($_POST, true) . "</pre>";

session_unset();

$destination = $_POST['destination'];
$debut = $_POST['debut'];
$fin = $_POST['fin'];
$tags = $_POST['tags'];
$nbrPlace = $_POST['champNombre'];
$adv_option = $_POST['adv_option'];

require_once "DB/LogementDAO.php";
$database = new Database();
$LogementDAO = new LogementDAO($database);

// echo "<br>";
if ($adv_option != "on") {
    // echo "Mini";
    // echo "<br>";
    $result = $LogementDAO->SimpleFilter($destination, $debut, $fin, $nbrPlace, $tags);
} else {
    // echo "Full";
    // echo "<br>";
    $visite = $_POST['visite'];
    if ($_POST['type'] == "null"){
        $type = null;
    } else {
        $type = $_POST['type'];
    }
    if ($_POST['category'] == "null"){
        $category = null;
    } else {
        $category = $_POST['category'];
    }
    $price_min = $_POST['price_min'];
    $price_max = $_POST['price_max'];

    $result = $LogementDAO->Filter($destination, $debut, $fin, $nbrPlace, $tags, $visite, $type, $category, $price_min, $price_max);

    $_SESSION['visite'] = $visite;
    $_SESSION['type'] = $type;
    $_SESSION['category'] = $category;
    $_SESSION['price_min'] = $price_min;
    $_SESSION['price_max'] = $price_max;
}
// echo "<br>";
// echo "<br>";
// echo "Result";
// Set Session variable
$_SESSION["result"] = $result;

$_SESSION['destination'] = $destination;
$_SESSION['debut'] = $debut;
$_SESSION['fin'] = $fin;
$_SESSION['tags'] = $tags;
$_SESSION['champNombre'] = $nbrPlace;
$_SESSION['adv_option'] = $adv_option;

// echo "<pre>" . print_r($_SESSION['result'], true) . "</pre>";

// Redirect
// echo "================= Redirection ========================";
header("Location: index.php");
// echo "NOOOOOOOOOOOOOOOO";


