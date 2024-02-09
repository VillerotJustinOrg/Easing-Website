
<?php
include 'header.php';

//print "<pre>".print_r($_POST)."</pre><br>";

$logement_id = $_POST['ID_Logement'];


require_once 'DB/ImageDAO.php';

require_once 'DB/LogementDAO.php';
require_once 'DB/images_logementDAO.php';

require_once 'DB/LocationDAO.php';

require_once 'DB/AdaptationsDAO.php';
require_once 'DB/AdaptationDAO.php';
require_once 'DB/images_adaptationDAO.php';

require_once 'DB/EquipementDAO.php';
require_once 'DB/EquipementsDAO.php';
require_once 'DB/images_equipementDAO.php';

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

// ADAPTATIONS
$AdaptationsDAO = new AdaptationsDAO($database);
$AdaptationDAO = new AdaptationDAO($database);
$ImagesAdaptationDAO = new ImagesAdaptationDAO($database);

$listAdaptations = $AdaptationsDAO->getAdaptationsByLogementId($logement_id);

$Adaptations = [];
foreach ($listAdaptations as $adaptationlink){
    $adaptation = $AdaptationDAO->getAdaptationById($adaptationlink['ID_Adaptation']);
    array_push($Adaptations, $adaptation);
}

// Equipement
$EquipementsDAO = new EquipementsDAO($database);
$EquipementDAO = new EquipementDAO($database);
$ImagesEquipementDAO = new ImagesEquipementDAO($database);

$listEquipements = $EquipementsDAO->getEquipementsByLogementId($logement_id);

$Equipements = [];
foreach ($listEquipements as $equipementlink){
    $equipement = $EquipementDAO->getEquipementById($equipementlink['ID_Equipement']);
    array_push($Equipements, $equipement);
}

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
            <div class="row col-4">
                <h3 class="col-12 longlivecenter">Visite</h3>
                <?php
                // Recover visite
                require_once 'DB/VisiteDAO.php';
                $VisiteDAO = new VisiteDAO($database);
                $visite = $VisiteDAO->getVisiteByLogement($logement_id);
//                var_dump($visite);
                if ($visite == null) {?>
                <div class="longlivecenter col-12 d-flex flex-column justify-content-center" style="background-color:gray; min-height: 360px">
                    <p>
                        Pas de visite disponible pour ce logement.
                    </p>
                </div>
                <?php
                }
                else {
                ?>
                <a class="case-3"
                   target=”_blank”
                   href="Visites/<?php echo $visite['ID_Visite'] ?>/<?php echo $visite['Label']?>"
                   style='
                           min-height: 360px;
                           position:relative;
                           width: 100%;
                           height: 100%;
                           background-size:cover;
                           background-position:center center;
                           background-image:
                            url("<?php echo "img/IMG_Logement/".$images[0]["Label"]?>");
                           '>
                    <img class="img-360" src="img/360.svg" >
                </a>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="line"> </div>
        <div class="d-flex flex-row justify-content-between" style="margin-top:30px;position:relative">

            <div style="width:65%">
                <h2 class="bold"> Description </h2>
                <p class="justify-text text-formated"><?php echo $logement['Description']; ?> </p>

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

                <div class="line"> </div>
                <h2 class="bold"> Ce que propose le logement </h2>
                <h3 class="bold longlivecenter"> Adaptations </h3>
                <div>
                    <?php
                    foreach ($Adaptations as $Adaptation){
                        echo '<div class="row" style="margin-bottom: 10px;">';
                        $images = [];
                        $listIMG = $ImagesAdaptationDAO->getImagesByAdaptationId($Adaptation['ID_Adaptation']);
                        foreach ($listIMG as $IMG){
                            $image = $ImageDAO->getImageById($IMG['ID_Image']);
                            array_push($images, $image);
                        }

                        // Carousel Adaptation Image
                        echo '<div class="col-4 d-flex"  style="max-height: 200px;">';
                        echo '<div class="owl-carousel carousel-logement owl-theme">';
                        foreach ($images as $image) {
//                            echo "<img id=\"ada\" src=\"img/IMG_Logement/".$image['Label']."\" alt=\"".$image['Label']."\" />";
                            echo '<img src="img/IMG_Logement/'.$image['Label'].'">';
                        }
                        echo '</div>';
                        echo '</div>';
                        // Adaptation Info
                        echo '<div class="col-8">';
                        echo "<h4>".$Adaptation['Label']."</h4><br>";
                        echo "<p class='justify-text'>".$Adaptation['Description']."</p>";
                        echo '</div>';

                        echo '</div>';
                    }
                    ?>
                </div>
                <h3 class="bold longlivecenter"> Equipements </h3>
                <div>
                    <?php
                    foreach ($Equipements as $Equipment){
                        echo '<div class="row" style="margin-bottom: 10px;">';
                        $images = [];
                        $listIMG = $ImagesEquipementDAO->getImagesByEquipementId($Equipment['ID_Equipement']);
                        foreach ($listIMG as $IMG){
                            $image = $ImageDAO->getImageById($IMG['ID_Image']);
                            array_push($images, $image);
                        }

                        // Carousel Adaptation Image
                        echo '<div class="col-4 d-flex"  style="max-height: 200px;">';
                        echo '<div class="owl-carousel carousel-logement owl-theme">';
                        foreach ($images as $image) {
                            echo '<img src="img/IMG_Logement/'.$image['Label'].'">';
                        }
                        echo '</div>';
                        echo '</div>';
                        // Adaptation Info
                        echo '<div class="col-8">';
                        echo "<h4>".$Equipment['Label']."</h4><br>";
                        echo "<p class='justify-text'>".$Equipment['Description']."</p>";
                        echo '</div>';

                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="line"> </div>
                <h2 class="bold"> Reglement Interieur </h2>
                <p class="justify-text text-formated"><?php echo $logement['Reglement_interieur']; ?> </p>
                <div class="line"> </div>
                <h2 class="bold"> Frais additionnels </h2>
                <p class="justify-text text-formated"><?php echo $logement['Frais_additionnels']; ?> </p>
            </div>

            <div class="pop-reserver" >
                <script>
                    function process_price(){
                        console.log("process_price");
                        let debut = document.getElementById("debut").value;
                        let fin = document.getElementById("fin").value;
                        console.log(debut);
                        console.log(fin);
                        let date_debut = new Date(debut);
                        let date_fin = new Date(fin);
                        const diffTime = Math.abs(date_debut - date_fin);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        console.log(diffDays);

                        let span_n_nuit = document.getElementById("nombreNuit");
                        span_n_nuit.innerHTML = diffDays.toString();

                        let price_element = document.getElementById("prix")
                        let price = price_element.getAttribute("data-prix")
                        price_element.innerHTML = "Prix : "+price*diffDays+" €";
                    }
                </script>
                <p><span style="font-size:25px" class="bold" > <?php echo $logement['Prix']; ?></span> € par nuit </p>

                <div style="margin-top:10px" class="d-flex flex-row justify-content-between">

                    <div class="d-flex flex-column" style="width:48%">
                        <p> Arrivée </p>
                        <input type="date" id="debut" name="debut" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" />
                    </div>

                    <div class="d-flex flex-column" style="width:48%">
                        <p> Départ </p>
                        <input type="date" id="fin" name="fin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>

                </div>

                <p style="margin-top:15px" >Nombre de personnes : <?php echo $logement['Nombre_Max'] ?></p>

                <a class="button" onclick="process_price()"> Calculer prix </a>

                <p> <?php echo $logement['Prix']; ?>€ x <span id="nombreNuit"> 5 </span> nuits </p>

                <p style="margin-top:30px;margin-top:10px;font-size:20px" class="bold" id="prix" data-prix="<?php echo $logement['Prix']; ?>"> Prix : <?php echo $logement['Prix']; ?> € </p>


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