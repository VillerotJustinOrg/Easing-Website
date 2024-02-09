<?php session_start(); ?>

<?php include 'header.php';
require_once 'DB/TagDAO.php';
require_once 'DB/TypeDAO.php';
require_once 'DB/CategoryDAO.php';
require_once 'DB/LogementDAO.php';
require_once 'DB/ImageDAO.php';
require_once 'DB/images_logementDAO.php';

$template_url = ".";
$database = new Database();

$TagDAO = new TagDAO($database);
$TypeDAO = new TypeDAO($database);
$CategoryDAO = new CategoryDAO($database);
$LogementDAO = new LogementDAO($database);
$ImageDAO = new ImageDAO($database);
$ImagesLogementDAO = new ImagesLogementDAO($database);

$tags = $TagDAO->getAllTags();
$types = $TypeDAO->getAllTypes();
$categories = $CategoryDAO->getAllCategories();

//if (isset($_SESSION['adv_option']) and $_SESSION['adv_option'] == "on") {echo "CHECKED";}

$logements = $_SESSION['result'] ?? $LogementDAO->getAllLogementsComplete();



?>

<script>
    function affiche_bloc() {
        let CheckBox = document.getElementById("adv_option");
        console.log("test");
        if (CheckBox.checked)
        {
            document.getElementById("advanced_option").style.display="block";
        }
        else
        {
            document.getElementById("advanced_option").style.display="none";
        }
    }

</script>
<main class="container">
    <form class="accueil_form d-flex flex-column" action="logement_filter_treatment.php" method="post" style="margin-bottom:50px;margin-top:30px;">
        <div class="d-flex align-items-end justify-content-center flex-row">
            <!-- Champ nombre -->
            <div class="d-flex flex-column champ">
                <label class="bold" for="destination">Destination</label>
                <input type="text" id="destination" name="destination" value="<?php  if (isset($_SESSION['destination'])) {echo $_SESSION['destination'];} ?>">
            </div>
            <div class="d-flex flex-column champ">
                <label class="bold" for="debut">Date de début</label>
                <input type="date" id="debut" name="debut" min="<?php echo date('Y-m-d'); ?>" value="<?php  if (isset($_SESSION['debut'])) {echo $_SESSION['debut'];} ?>"/>
            </div>
            <div class="d-flex flex-column champ">
                <label class="bold" for="fin">Date de fin </label>
                <input type="date" id="fin" name="fin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" value="<?php  if (isset($_SESSION['fin'])) {echo $_SESSION['fin'];} ?>">
            </div>
            <!-- Champ nombre -->
            <div class="d-flex flex-column champ">
                <label class="bold" for="champNombre">Voyageurs</label>
                <input type="number" id="champNombre" name="champNombre" value="<?php  if (isset($_SESSION['champNombre'])) {echo $_SESSION['champNombre'];} ?>">
            </div>
            <div class="d-flex flex-column champ champ_filtre" style="margin-right:25px">
                <label style="margin-bottom:0" class="bold" for="">Tags</label>
                <img style="width:33px" src="<?php echo $template_url; ?>/img/filter.svg" alt="" >
            </div>
            <!-- Bouton de soumission -->
            <button class="envoyer d-flex justify-content-center align-items-center" type="submit" value="">
                <img src="<?php echo $template_url; ?>/img/search-white.svg" alt="" >
            </button>

            <!-- Tags -->
            <div class="filtre-popup align-items-center justify-content-center">
                <div class="d-flex flex-column pop-pup-content" >
                    <p class="croix_filtre" > + </p>
                    <h3 style="margin-bottom:25px"> Tags </h3>
                    <?php
                    $i = 0;
                    foreach ($tags as $tag) {?>
                        <div>
                            <label for="<?php echo $i ?>"><?php echo $tag["Label"] ?></label>
                            <input id="<?php echo $i ?>" name="tags[]" value="<?php echo $tag['ID_Tag'] ?>" type="checkbox">
                        </div>
                    <?php $i+=1;  } ?>
                </div>
            </div>
        </div>
        <div>
            <label id="adv_option_label" for="adv_option">Options Avancées</label>
            <input id="adv_option" name="adv_option" <?php if (isset($_SESSION['adv_option']) and $_SESSION['adv_option'] == "on") {echo "checked";} ?> type="checkbox" onchange="affiche_bloc()" onload="affiche_bloc()">
        </div>
        <div class="" id="advanced_option" style="display: <?php if (isset($_SESSION['adv_option']) and $_SESSION['adv_option'] == "on") {echo "box";} else { echo "none";} ?>">
            <div>
                <label id="visite_label" for="visite">A une Visite</label>
                <input id="visite" name="visite" type="checkbox" <?php if (isset($_SESSION['visite']) and $_SESSION['visite'] == "on") {echo "CHECKED";}?>>
            </div>
            <div class="d-flex flex-column justify-content-left">
                <label id="type_label" for="type">Type :</label>
                <select id="type" name="type">
                    <option value="null"></option>
                    <?php
                    foreach ($types as $type){
                        $is_selected = "";
                        if (isset($_SESSION['type']) and (intval($type['ID_Type']) == intval($_SESSION['type']))) {
                            $is_selected = 'selected="selected"';
                        }
                        echo '<option value="'.$type['ID_Type'].'" '.$is_selected.'>'.$type['Label'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="d-flex flex-column justify-content-left">
                <label id="category_label" for="category">Category :</label>
                <select id="category" name="category">
                    <option value="null"></option>
                    <?php
                    foreach ($categories as $category){
                        $is_selected = "";
                        if (isset($_SESSION['category']) and (intval($category['ID_Category']) == intval($_SESSION['category']))) {
                            $is_selected = 'selected="selected"';
                        }
                        echo '<option value="'.$category['ID_Category'].'" '.$is_selected.'>'.$category['Label'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="d-flex flex-row justify-content-left">
                <div class="d-flex flex-column justify-content-center">
                    <label id="price_min_label" for="price_min">Prix Min</label>
                    <input id="price_min" name="price_min" type="number" value="<?php  if (isset($_SESSION['price_min'])) {echo $_SESSION['price_min'];} ?>">
                </div>
                <div class="d-flex flex-column justify-content-center">
                    <label id="price_max_label" for="price_max">Prix Max</label>
                    <input id="price_max" name="price_max" type="number" value="<?php  if (isset($_SESSION['price_max'])) {echo $_SESSION['price_max'];} ?>">
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex flex-row" style="flex-flow:row wrap;gap:1%">
        <?php
            foreach ($logements as $logement) {
                $logement_id=(isset($_SESSION['result']) ? $logement[0] : $logement['ID_Logement']);
                $logement_Nom=(isset($_SESSION['result']) ? $logement[1] : $logement['Nom']);
                $logement_Adresse=(isset($_SESSION['result']) ? $logement[2] : $logement['Adresse']);
                $logement_Category_Label=(isset($_SESSION['result']) ? $logement[18] : $logement['Category_Label']);
                $logement_Type_Label=(isset($_SESSION['result']) ? $logement[19] : $logement['Type_Label']);
                $logement_Prix=(isset($_SESSION['result']) ? $logement[12] : $logement['Prix']);



                $images_id = $ImagesLogementDAO->getImagesByLogementId($logement_id);

                $images = [];
                foreach ($images_id as $id){
                    $image = $ImageDAO->getImageById($id["ID_Image"]);
                    array_push($images, $image);
                }

                $available_after = $LogementDAO->nextDisponibility($logement_id);

                ?>
                <form class="card_logement" target="_blank" action="single-logement.php" method="post" id="<?php print_r($logement_id) ?>">
                    <input type="hidden" name="ID_Logement" value="<?php echo $logement_id ?>">
                    <div class="owl-carousel carousel-logement owl-theme">
                        <?php foreach($images as $photo){ ?>
                            <img src="img/IMG_Logement/<?php echo $photo['Label'] ?>" alt="<?php echo $photo['Label']?>">
                        <?php } ?>
                    </div>
                    <div class="informations" >
                        <p class="ville"><?php echo $logement_Adresse ?>, France </p>
                        <p><?php echo $logement_Nom ?> </p>
                        <p><?php echo $logement_Category_Label." - ".$logement_Type_Label  ?> </p>
                        <p><?php echo strftime("%e %b", strtotime($available_after)) ?></p>
                        <p class="prix"><span class="bold"> <?php echo $logement_Prix ?> </span> € par nuit </p>
                        <button type="submit" class="super_button">Plus d'informations</button>
                    </div>
                </form>
            <?php } ?>
        </div>
</main>

<?php
include 'footer.php';
?>