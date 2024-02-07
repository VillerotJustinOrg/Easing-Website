
<?php
include 'header.php';

//print "<pre>".print_r($_POST)."</pre><br>";

$logement_id = $_POST['ID_Logement'];

// Logement
require_once 'DB/LogementDAO.php';
require_once 'DB/ImageDAO.php';
require_once 'DB/images_logementDAO.php';
require_once 'DB/LocationDAO.php';
$database = new Database();
$LogementDAO = new LogementDAO($database);
$ImageDAO = new ImageDAO($database);
$ImagesLogementDAO = new ImagesLogementDAO($database);

$logement = $LogementDAO->getLogementCompleteByID($logement_id);

// Images
$images_id = $ImagesLogementDAO->getImagesByLogementId($logement_id);
$images = [];
foreach ($images_id as $id){
    $image = $ImageDAO->getImageById($id["ID_Image"]);
    array_push($images, $image);
}

$LocationDAO = new LocationDAO($database);

$locations = $LocationDAO->getAllLocationsOfLogement($logement_id);

$data = array(
    'latitude' => $logement['Lattitude'],
    'longitude' => $logement['Longitude']
);

$json_data = json_encode($data);

?>


<main id="singleLogement" class="container">
    <div>

        <h1 class="bold" > <?php echo $logement['Nom'] ?> </h1>
        <!--TODO Image Carousel & 3D view-->
        <div class="row align-items-start">
            <div class="col-8">
                <script src="JS/flickity.pkgd.min.js.js"></script>
                <!-- Flickity HTML init -->
                <div class="carousel" data-flickity='{ "wrapAround": true, "imagesLoaded": true, "percentPosition": false }'>
                    <?php
                    foreach ($images as $image) {
                        //echo "<!--".$image['Label']."-->";
                        echo "<img src=\"img/IMG_Logement/".$image['Label']."\" alt=\"".$image['Label']."\" />";
                    }
                    ?>
                </div>
            </div>
            <div class="col-4">
                VISITE MAISON
                <!--    TODO Visite 3D maison        -->
                <!--            <a target=”_blank” href="visite/Maison.html" style="position:relative;background-size:cover;background-position:center center;background-image: url(--><?php //echo $photo['url'] );" class="case- //echo $i ?><!--">-->
                <!--                <img class="img-360" src="img/360.svg" >-->
                <!--            </a>-->
            </div>
        </div>
        <div class="line"> </div>
        <div class="d-flex flex-row justify-content-between" style="margin-top:30px;position:relative">

            <div style="width:65%">
                <h2 class="bold"> Description </h2>
                <p class="justify-text"><?php echo $logement['Description']; ?> </p>

                <div class="line"> </div>
                <h2 class="bold"> Disponibilités </h2>

                <?php
                include_once 'vanillaCalendar.php';

                $calendar = new Calendar();
//                echo "<pre>".print_r($locations)."</pre>";
                foreach ($locations as $location){
                    echo "/ event added ";
                    $startDate = date_create(date("Y-m-d", strtotime($location[1])));
                    $endDate = date_create(date("Y-m-d", strtotime($location[2])));
                    $diff = date_diff($startDate, $endDate);
                    $diff = $diff->format('%a');
                    $calendar->add_event("LOUER", $startDate, intval($diff), 'red');
                }
                echo "<br>";
                ?>
                <?=$calendar?>
                <!-- TODO JS Calendar to replace the fake one -->
<!--                <img style="width:100%" src="img/Calendrier.png" >-->
                <div class="line"> </div>
                <h2 class="bold"> Ce que propose le logement </h2>

                <!-- TODO Adaptations -->

                <div class="line"> </div>
                <h2 class="bold"> Reglement Interieur </h2>
                <p class="justify-text"><?php echo $logement['Reglement_interieur']; ?> </p>
                <div class="line"> </div>
                <h2 class="bold"> Frais additionnels </h2>
                <p class="justify-text"><?php echo $logement['Frais_additionnels']; ?> </p>
            </div>

            <div class="pop-reserver" >
                <p><span style="font-size:25px" class="bold" > <?php echo $logement['Prix']; ?></span> € par nuit </p>

                <div style="margin-top:10px" class="d-flex flex-row justify-content-between">

                    <div class="d-flex flex-column" style="width:48%">
                        <p> Arrivée </p>
                        <input type="date" id="debut" name="debut" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $debut ?>" />
                    </div>

                    <div class="d-flex flex-column" style="width:48%">
                        <p> Départ </p>
                        <input type="date" id="fin" name="fin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" value="<?php echo $fin ?>" ?>
                    </div>

                </div>

                <p style="margin-top:15px" >Nombre de personnes : <?php echo $logement['nombre_personnes'] ?></p>

                <a class="button" href="#"> Réserver </a>

                <p> <?php echo $logement['Prix']; ?>€ x <span id="nombreNuit"> 5 </span> nuits </p>

                <p style="margin-top:30px;margin-top:10px;font-size:20px" class="bold" id="prix" data-prix="<?php echo $logement['Prix']; ?>"> Prix : <?php echo  $fields['prix_nuit'] ?> € </p>


            </div>

        </div>

        <div class="grand_line"> </div>

        <h2 class="bold"> Localisation </h2>
        <div  id="map-log" data-logements='<?php echo htmlspecialchars($json_data, ENT_QUOTES, 'UTF-8'); ?>'>

        </div>


    </div>
</main>

<?php
include 'footer.php';
?>