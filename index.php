<?php /* Template Name: Liste logements */ ?>

<?php include 'header.php';

require_once 'DB/TagDAO.php';

$template_url = ".";
$database = new Database();

$TagDAO = new TagDAO($database);

$tags = $TagDAO->getAllTags();

//echo "<pre>" . print_r($tags, true) . "</pre>";

?>

<main class="container">
    <form style="margin-top:30px;margin-bottom:30px" class="accueil_form d-flex align-items-end justify-content-center flex-row" action="logement_filter_treatment.php" method="post" style="margin-bottom:50px">
        <!-- Champ nombre -->
        <div class="d-flex flex-column champ">
            <label class="bold" for="destination">Destination</label>
            <input type="text" id="destination" name="destination">
        </div>
        <div class="d-flex flex-column champ">
            <label class="bold" for="debut">Date de début</label>
            <input type="date" id="debut" name="debut" min="<?php echo date('Y-m-d'); ?>" />
        </div>
        <div class="d-flex flex-column champ">
            <label class="bold" for="fin">Date de fin </label>
            <input type="date" id="fin" name="fin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" ?>
        </div>
        <!-- Champ nombre -->
        <div class="d-flex flex-column champ">
            <label class="bold" for="champNombre">Voyageurs</label>
            <input type="number" id="champNombre" name="champNombre">
        </div>
        <div class="d-flex flex-column champ champ_filtre" style="margin-right:25px">
            <label style="margin-bottom:0" class="bold" for="">Filtres</label>
            <img style="width:33px" src="<?php echo $template_url; ?>/img/filter.svg" alt="" >
        </div>
        <!-- Bouton de soumission -->
        <button class="envoyer d-flex justify-content-center align-items-center" type="submit" value="">
            <img src="<?php echo $template_url; ?>/img/search-white.svg" alt="" >
        </button>
        <div class="filtre-popup align-items-center justify-content-center">
            <div class="d-flex flex-column pop-pup-content" >
                <p class="croix_filtre" > + </p>
                <h3 style="margin-bottom:25px"> Filtres </h3>
                <div style="margin-bottom:10px;flex-flow:row wrap" class="d-flex tags-utilise"></div>
                <div  style="flex-flow:row wrap" class="d-flex tags">
                    <?php foreach ($tags as $tag) { ?>
                        <div style="margin-bottom:10px" class="tag tag-noselect"> <?php echo $tag["Label"]; ?> </div>
                    <?php  } ?>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex flex-row" style="flex-flow:row wrap;gap:1%">
        <?php
            require_once 'DB/LogementDAO.php';
            require_once 'DB/ImageDAO.php';
            require_once 'DB/images_logementDAO.php';

            $LogementDAO = new LogementDAO($database);

            $logements = $LogementDAO->getAllLogementsComplete();

            $ImageDAO = new ImageDAO($database);
            $ImagesLogementDAO = new ImagesLogementDAO($database);

            foreach ($logements as $logement) {
                $logement_id=$logement["ID_Logement"];

                $images_id = $ImagesLogementDAO->getImagesByLogementId($logement_id);

                $images = [];
                foreach ($images_id as $id){
                    $image = $ImageDAO->getImageById($id["ID_Image"]);
                    array_push($images, $image);
                }

                $available_after = $LogementDAO->nextDisponibility($logement_id);

                $lien = "single-logement.php/?ID=". $logement_id . "?debut=" . $available_after . "&fin=" . date('Y-m-d', strtotime($available_after.'+1 week'));

                ?>
                <form class="card_logement" target="_blank" action="single-logement.php" method="post" id="<?php print_r($logement_id) ?>">
                    <input type="hidden" name="ID_Logement" value="<?php echo $logement_id ?>">
                    <div class="owl-carousel carousel-logement owl-theme">
                        <?php foreach($images as $photo){ ?>
                            <img src="img/IMG_Logement/<?php echo $photo['Label']  ?>">
                        <?php } ?>
                    </div>
                    <div class="informations" >
                        <p class="ville"><?php echo $logement['Adresse'] ?>, France </p>
                        <p><?php echo $logement['Nom'] ?> </p>
                        <p><?php echo $logement['Category_Label']." - ".$logement['Type_Label']  ?> </p>
                        <p><?php echo strftime("%e %b", strtotime($available_after)) ?></p>
                        <p class="prix"><span class="bold"> <?php echo $logement['Prix'] ?> </span> € par nuit </p>
<!--    Don't work so useless   <div class="coeur" > </div>-->
                        <button type="submit" class="super_button">Plus d'informations</button>
                    </div>
                </form>
            <?php } ?>
        </div>
</main>

<?php
include 'footer.php';
?>