<?php
require('../../config.php');
global $CFG, $PAGE, $USER, $DB, $SITE, $_SESSION;
@error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', '1');
define('MOODLE_DEBUG', true);
define('MOODLE_DEBUG_DISPLAY', true);

if(!isloggedin()){
    redirect($CFG->wwwroot);
}
require_once('inc/init.php');
$title = 'Gestion des utilisateurs';
if(!empty($_SESSION['attente'])){
    $contenu .= '<div class="alert alert-success mt-2">' . $_SESSION['attente'] . '</div>';
    unset($_SESSION['attente']);
}
// 6.Suppression d'un utilisateur
if(isset( $_GET['action']) && $_GET['action']=='del' && !empty($_GET['username']) ){
    execRequete("DELETE FROM users_opco WHERE username=:username",array('username' => $_GET['username']));
    $_SESSION['attente'] = 'L\' utilisateur a été supprimé';
    redirect('index.php');
}
// 7. Demande de modification
if( isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['username'])){
    $resultat = execRequete("SELECT * FROM users_opco WHERE username=:username",array('username' => $_GET['username']));
    if( $resultat->rowCount() > 0){
        $utilisateur_courant = $resultat->fetch();
    }
    $_GET['action'] = 'edit';
}
// 4. Traitement du POST pour ajout (celui qui marche actuellement)
if( !empty($_POST) && isset($_GET['action']) && $_GET['action'] == 'ajout') {
    $errors = array();



    //controler s'il n'hexiste pas un autre username
    $unique = execRequete2("SELECT * FROM mdlj0_user WHERE username=:username",
    array(
        'username' => $_POST['username']
    ));
    $unique_user = $unique->fetch();
    if($unique->rowCount() == 1) {
        $errors[] = 'Cliquez sur suivant pour voir plus de détails sur ce collaborateur';
        if($errors) {
            $_GET['action'] = 'user_unique';
        }
    }

    // Get the current user (rh_inv)

    $current_user = execRequete("SELECT * FROM users_opco WHERE username = '$USER->username'");

    $current_user = $current_user->fetch();



    // CONTROLES à IMAGINER
    if( !empty($errors) ){
        $contenu .= '<div class="container alert alert-danger mt-2">'.implode('<br>',$errors).'</div>';
    }else{
        if($_POST['username'] == $_POST['email']){
            if (isset($_POST['rh_delegue'])) {
                extract($_POST);

                execRequete(
                    "REPLACE INTO users_opco VALUES (:civlite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture,:rgpd,:raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise,:telephone,:raison_facture,:datefin_remise,:ref_id)",
                    array(
                        'civilite' => $civilite,
                        'username' => strtolower($username),
                        'password' => strtolower($password),
                        'firstname' => ucfirst($firstname),
                        'lastname' => strtoupper($lastname),
                        'email' => strtolower($email),
                        'fonction' => $fonction,
                        'nom_etab' => $nom_etab,
                        'siren' => $siren,
                        'region' => $region,
                        'segmentation' => $segmentation,
                        'branche_app' => $branche_app,
                        'num_adherent' => $num_adherent,
                        'rh_inv' => 'Non',
                        'rh_delegue' => 'Oui',
                        'account_type' => 'Stagiaire',
                        'address' => $current_user['address'],
                        'postalcode' => $current_user['postalcode'],
                        'city' => $current_user['city'],
                        'country' => $current_user['country'],
                        'address_comp' => $current_user['address_comp'],
                        'activity' => $current_user['activity'],
                        'offer_formation' => '',
                        'info_formation' => '',
                        'address_facture' => $current_user['address_facture'],
                        'postalcode_facture' => $current_user['postalcode_facture'],
                        'city_facture' => $current_user['city_facture'],
                        'country_facture' => $current_user['country_facture'],
                        'address_comp_facture' => $current_user['address_comp_facture'],
                        'rgpd' => 'Oui',
                        'raison' => $current_user['raison'],
                        'prenom_refclient' => $current_user['firstname'],
                        'nom_refclient' => $current_user['lastname'],
                        'email_refclient' => $current_user['email'],
                        'conv_remise' => $current_user['conv_remise'],
                        'telephone' => $telephone,
                        'raison_facture' => $current_user['raison_facture'],
                        'datefin_remise' => $current_user['datefin_remise'],
                        'ref_id' => $USER->id
                    )
                );
            } else {
                extract($_POST);
                execRequete(
                    "REPLACE INTO users_opco VALUES (:civilite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture,:rgpd,:raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise,:telephone,:raison_facture,:datefin_remise,:ref_id)",
                    array(
                        'civilite' => $civilite,
                        'username' => strtolower($username),
                        'password' => strtolower($password),
                        'firstname' => ucfirst($firstname),
                        'lastname' => strtoupper($lastname),
                        'email' => strtolower($email),
                        'fonction' => $fonction,
                        'nom_etab' => $nom_etab,
                        'siren' => $siren,
                        'region' => $region,
                        'segmentation' => $segmentation,
                        'branche_app' => $branche_app,
                        'num_adherent' => $num_adherent,
                        'rh_inv' => 'Non',
                        'rh_delegue' => 'Non',
                        'account_type' => 'Stagiaire',
                        'address' => $current_user['address'],
                        'postalcode' => $current_user['postalcode'],
                        'city' => $current_user['city'],
                        'country' => $current_user['country'],
                        'address_comp' => $current_user['address_comp'],
                        'activity' => $current_user['activity'],
                        'offer_formation' => '',
                        'info_formation' => '',
                        'address_facture' => $current_user['address_facture'],
                        'postalcode_facture' => $current_user['postalcode_facture'],
                        'city_facture' => $current_user['city_facture'],
                        'country_facture' => $current_user['country_facture'],
                        'address_comp_facture' => $current_user['address_comp_facture'],
                        'rgpd' => 'Oui',
                        'raison' => $current_user['raison'],
                        'prenom_refclient' => $current_user['firstname'],
                        'nom_refclient' => $current_user['lastname'],
                        'email_refclient' => $current_user['email'],
                        'conv_remise' => $current_user['conv_remise'],
                        'telephone' => $telephone,
                        'raison_facture' => $current_user['raison_facture'],
                        'datefin_remise' => $current_user['datefin_remise'],
						'ref_id' => $USER->id
                )
            );
            }

                $_SESSION['attente'] = 'L\'utilisateur a été enregistré';
                // die;
                redirect('index.php');
        }else{
            $contenu .= '<div class="container alert alert-danger mt-2">L\'utilisateur n\'a pas été enregistré car, les adresses e-mails renseignées ne correspondent pas !</div>';
        }
    }
}
// 4. Traitement du POST pour modif
if( !empty($_POST) && isset($_GET['action']) && $_GET['action'] == 'edit') {

        // Get the current user (rh_inv)

        $current_user = execRequete("SELECT * FROM users_opco WHERE username = '$USER->username'");

        $current_user = $current_user->fetch();

    if($_POST['username'] == $_POST['email']){
        if (isset($_POST['rh_delegue'])) {
            extract($_POST);
            execRequete(
                "REPLACE INTO users_opco VALUES (:civilite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture,:rgpd,:raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise,:telephone,:raison_facture,:datefin_remise,:ref_id)",
                array(
                    'civilite' => $civilite,
                    'username' => strtolower($username),
                    'password' => strtolower($password),
                    'firstname' => ucfirst($firstname),
                    'lastname' => strtoupper($lastname),
                    'email' => strtolower($email),
                    'fonction' => $fonction,
                    'nom_etab' => $nom_etab,
                    'siren' => $siren,
                    'region' => $region,
                    'segmentation' => $segmentation,
                    'branche_app' => $branche_app,
                    'num_adherent' => $num_adherent,
                    'rh_inv' => 'Non',
                    'rh_delegue' => 'Oui',
                    'account_type' => 'Stagiaire',
                    'address' => $current_user['address'],
                    'postalcode' => $current_user['postalcode'],
                    'city' => $current_user['city'],
                    'country' => $current_user['country'],
                    'address_comp' => $current_user['address_comp'],
                    'activity' => $current_user['activity'],
                    'offer_formation' => '',
                    'info_formation' => '',
                    'address_facture' => $current_user['address_facture'],
                    'postalcode_facture' => $current_user['postalcode_facture'],
                    'city_facture' => $current_user['city_facture'],
                    'country_facture' => $current_user['country_facture'],
                    'address_comp_facture' => $current_user['address_comp_facture'],
                    'rgpd' => 'Oui',
                    'raison' => $current_user['raison'],
                    'prenom_refclient' => $current_user['firstname'],
                    'nom_refclient' => $current_user['lastname'],
                    'email_refclient' => $current_user['email'],
                    'conv_remise' => $current_user['conv_remise'],
                    'telephone' => $telephone,
                    'raison_facture' => $current_user['raison_facture'],
                    'datefin_remise' => $current_user['datefin_remise'],
					'ref_id' => $USER->id
                )
            );
        } else {
            extract($_POST);
            execRequete(
                "REPLACE INTO users_opco VALUES (:civilite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture,:rgpd,:raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise,:telephone,:raison_facture,:datefin_remise,:ref_id)",
                array(
                    'civilite' => $civilite,
                    'username' => strtolower($username),
                    'password' => strtolower($password),
                    'firstname' => ucfirst($firstname),
                    'lastname' => strtoupper($lastname),
                    'email' => strtolower($email),
                    'fonction' => $fonction,
                    'nom_etab' => $nom_etab,
                    'siren' => $siren,
                    'region' => $region,
                    'segmentation' => $segmentation,
                    'branche_app' => $branche_app,
                    'num_adherent' => $num_adherent,
                    'rh_inv' => 'Non',
                    'rh_delegue' => 'Non',
                    'account_type' => 'Stagiaire',
                    'address' => $current_user['address'],
                    'postalcode' => $current_user['postalcode'],
                    'city' => $current_user['city'],
                    'country' => $current_user['country'],
                    'address_comp' => $current_user['address_comp'],
                    'activity' => $current_user['activity'],
                    'offer_formation' => '',
                    'info_formation' => '',
                    'address_facture' => $current_user['address_facture'],
                    'postalcode_facture' => $current_user['postalcode_facture'],
                    'city_facture' => $current_user['city_facture'],
                    'country_facture' => $current_user['country_facture'],
                    'address_comp_facture' => $current_user['address_comp_facture'],
                    'rgpd' => 'Oui',
                    'raison' => $current_user['raison'],
                    'prenom_refclient' => $current_user['firstname'],
                    'nom_refclient' => $current_user['lastname'],
                    'email_refclient' => $current_user['email'],
                    'conv_remise' => $current_user['conv_remise'],
                    'telephone' => $telephone,
                    'raison_facture' => $current_user['raison_facture'],
                    'datefin_remise' => $current_user['datefin_remise'],
					'ref_id' => $USER->id
            )
        );
        }


        $_SESSION['attente'] = 'L\'utilisateur a été modifié et enregistré';

        redirect('index.php');
    }else{
        $contenu .= '<div class="container alert alert-danger mt-2">L\'utilisateur n\'a pas été modifié car les adresses e-mails renseignées ne correspondent pas !</div>';
    }
}
//traitement du post pour user_unique
if(!empty($_POST) && isset($_POST['forcing_upload'])) {
    if(isset($_POST['rh_delegue'])){
        //supprimer le dernier élément c-a-d Enregistrer quand même
        $a = array_pop($_POST);
        extract($_POST); // génére des variables à partir des index
        execRequete(
            "REPLACE INTO users_opco VALUES (:civilite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture, :rgpd, :raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise)",
            array(
                'civilite' => $civilite,
                'username' => strtolower($username),
                'password' => strtolower($password),
                'firstname' => ucfirst($firstname),
                'lastname' => strtoupper($lastname),
                'email' => strtolower($email),
                'fonction' => $fonction,
                'nom_etab' => $nom_etab,
                'siren' => $siren,
                'region' => $region,
                'segmentation' => $segmentation,
                'branche_app' => $branche_app,
                'num_adherent' => $num_adherent,
                'rh_inv' => 'Non',
                'rh_delegue' => 'Non',
                'account_type' => 'Stagiaire',
                'address' => $current_user['address'],
                'postalcode' => $current_user['postalcode'],
                'city' => $current_user['city'],
                'country' => $current_user['country'],
                'address_comp' => $current_user['address_comp'],
                'activity' => $current_user['activity'],
                'offer_formation' => '',
                'info_formation' => '',
                'address_facture' => $current_user['address_facture'],
                'postalcode_facture' => $current_user['postalcode_facture'],
                'city_facture' => $current_user['city_facture'],
                'country_facture' => $current_user['country_facture'],
                'address_comp_facture' => $current_user['address_comp_facture'],
                'rgpd' => 'Oui',
                'raison' => $current_user['raison'],
                'prenom_refclient' => $current_user['firstname'],
                'nom_refclient' => $current_user['lastname'],
                'email_refclient' => $current_user['email'],
                'conv_remise' => $current_user['conv_remise']
        )
    );
    }else{
        extract($_POST);
        execRequete(
            "REPLACE INTO users_opco VALUES (:civilite,:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue,:account_type,:address,:postalcode,:city,:country,:address_comp,:activity,:offer_formation,:info_formation,:address_facture,:postalcode_facture,:city_facture,:country_facture,:address_comp_facture, :rgpd, :raison,:prenom_refclient,:nom_refclient,:email_refclient,:conv_remise)",
            array(
                'civilite' => $civilite,
                'username' => strtolower($username),
                'password' => strtolower($password),
                'firstname' => ucfirst($firstname),
                'lastname' => strtoupper($lastname),
                'email' => strtolower($email),
                'fonction' => $fonction,
                'nom_etab' => $nom_etab,
                'siren' => $siren,
                'region' => $region,
                'segmentation' => $segmentation,
                'branche_app' => $branche_app,
                'num_adherent' => $num_adherent,
                'rh_inv' => 'Non',
                'rh_delegue' => 'Non',
                'account_type' => 'Stagiaire',
                'address' => $current_user['address'],
                'postalcode' => $current_user['postalcode'],
                'city' => $current_user['city'],
                'country' => $current_user['country'],
                'address_comp' => $current_user['address_comp'],
                'activity' => $current_user['activity'],
                'offer_formation' => '',
                'info_formation' => '',
                'address_facture' => $current_user['address_facture'],
                'postalcode_facture' => $current_user['postalcode_facture'],
                'city_facture' => $current_user['city_facture'],
                'country_facture' => $current_user['country_facture'],
                'address_comp_facture' => $current_user['address_comp_facture'],
                'rgpd' => 'Oui',
                'raison' => $current_user['raison'],
                'prenom_refclient' => $current_user['firstname'],
                'nom_refclient' => $current_user['lastname'],
                'email_refclient' => $current_user['email'],
                'conv_remise' => $current_user['conv_remise']
        )
    );
        }
        $_SESSION['attente'] = 'L\'utilisateur est enregistré dans mon Siret établissement';
        redirect('index.php');
}
echo $OUTPUT->header();
require_once('inc/header.php');
echo '<br>';
echo $contenu;
/*infos du user*/
$numsiret = $USER->profile['siret_etablissement'];
echo '<b>Prénom : </b>' . $USER->firstname . ' - ' . ' <b>Nom : </b>' . $USER->lastname . '<br>';
/*
if(!empty($numsiret)){
    echo '<b>Username : </b>' . $USER->username . ' - ' . ' <span style="color: #be226e"><b>Nom établissement : </b>' . $numsiret . '</span>';
}else{
    echo '<b>Username : </b>' . $USER->username . ' - ' . ' <span style="color: #be226e">Nom établissement : <b> N\'hexiste pas dans la base de données</b></span>';
}
*/
// Afficher les onglets
?>
<div class="container" style="margin-top: 20px">
    <ul class="nav nav-tabs nav-justified" style="margin-bottom: 20px">
        <li class="nav-item" style="display: initial">
            <a style="font-weight: bold;" href="?action=affichage" class="nav-link <?= ( !isset($_GET['action'])
            || (isset($_GET['action']) && $_GET['action'] == 'affichage') ) ? 'active' : '' ?>">Liste de mes
                collaborateurs
            </a>
        </li>
        <li class="nav-item" style="display: initial">
            <a style="font-weight: bold;" href="?action=ajout" title=""
                class="nav-link <?= ( isset($_GET['action']) && $_GET['action'] == 'ajout') ? 'active' : '' ?>">Ajouter
                un collaborateur</a>
        </li>
        <!-- <li class="nav-item" style="display: initial">
            <a style="font-weight: bold;" href="?action=mutate" title=""
                class="nav-link <?= ( isset($_GET['action']) && $_GET['action'] == 'mutate') ? 'active' : '' ?>">Mutation
                des Utilisateurs</a>
        </li> -->
    </ul>
</div>
<?php
// 5. affichage des utilisateurs
if( !isset($_GET['action']) || ( isset($_GET['action']) && $_GET['action'] == 'affichage') ){
    $numsiret = $USER->profile['siret_etablissement'];
    $fonction = explode(',', $numsiret);
    foreach($fonction as $as){
        $sql[] = "nom_etab LIKE '%".addslashes($as)."%'";
    }
    $resultat = execRequete("SELECT * FROM users_opco WHERE (".implode(" OR ", $sql). ") AND rh_inv = 'Non' AND username != '$USER->username'");
    if( $resultat->rowCount() == 0 ){
        ?>
<div class="alert alert-warning">Il n'y a pas encore d'utilisateur(s) enregistrés</div>
<?php
    }else{
        ?>
<br>
<p class="mt-2">Il y a <?= $resultat->rowCount() ?> utilisateur(s) dans la liste des utilisateurs</p>
<table class="table table-bordered table-striped table-responsive text-center">
    <tr style="color: #fff; font-weight: bold;">
        <th style="background:#75287c">Identifiant</th>
        <!-- <td style="background:#75287c">Mot de passe</td> -->
        <th style="background:#00798c">Civilité</th>
        <th style="background:#75287c">Prénom</th>
        <th style="background:#75287c">Nom</th>
        <th style="background:#75287c">E-mail</th>
        <th style="background:#75287c">Fonction</th>
        <th style="background-color: rgba(117,40,124, .5); color: #fff">Nom établissement</th>
        <th style="background-color: rgba(117,40,124, .5); color: #fff; display:none;">Région</th>
        <!--
                <td style="background-color: rgba(117,40,124, .5); color: #fff">Ségmentation</td>
                <td style="background-color: rgba(117,40,124, .5); color: #fff">Branche applicable</td>
                <td style="background-color: rgba(117,40,124, .5); color: #fff">Numéro d'adhérent</td>
                -->
<!--        <td style="background:#75287c">Gestionnaire Formation</td>-->
        <th style="background:black" colspan="2">Actions</th>
    </tr>
    <?php
                while( $liste = $resultat->fetch() ){
                    ?>
    <tr>
        <td style="width:7.7%"><?= $liste['username'] ?></td>
        <td style="width:7.7%"><?= $liste['civilite'] ?></td>
        <!-- <td style="width:7.7%"><?= $liste['password'] ?></td> -->
        <td style="width:7.7%"><?= $liste['firstname'] ?></td>
        <td style="width:7.7%"><?= $liste['lastname'] ?></td>
        <td style="width:7.7%"><?= $liste['email'] ?></td>
        <td style="width:7.7%"><?= $liste['fonction'] ?></td>
        <td class="oddeven" style="width:30%;">
            <?php
                        // nom établissement
                            //if(is_siteadmin()){
                                $listenom_etab = $liste['nom_etab'];
                                $nom_etab = explode(',',$listenom_etab);
                                echo '<ul style="list-style: none;">';
                                for($i=0; $i<=count($nom_etab); $i++)
                                {
                                    if ($i % 2 == 0)
                                    {
                                        echo '<li><span style="background: #F1F8E9; padding: 5px; border-top: 2px solid rgba(255,255,255, .8);border-bottom: 2px solid rgba(255,255,255, .8)">'.$nom_etab[$i].'</span></li>';
                                    }else{
                                        echo '<li><span style="background: #F1F9F6; padding: 5px; border-top: 2px solid rgba(255,255,255, .8);border-bottom: 2px solid rgba(255,255,255, .8)">'.$nom_etab[$i].'</span></li>';
                                    }
                                }
                                echo '</ul>';
                        ?></td>
        <td style="width:7.7%; display:none;"><?= $liste['region'] ?></td>
        <?php
                        /*
                        echo '
                        <td style="width:7.7%"><?= $liste['segmentation'] ?></td>
        <td style="width:7.7%"><?= $liste['branche_app'] ?></td>
        <td style="width:7.7%"><?= $liste['num_adherent'] ?></td>
        ';
        */
        ?>
<!--        <td style="width:7.7%">--><?php //= $liste['rh_delegue'] ?><!--</td>-->
        <td>
            <?php
                            if($liste['rh_inv'] == 'Non'){
                                ?>
            <a style="font-size: 13px; color: #0a800a" href="?action=edit&username=<?= $liste['username'] ?>"
                title="Modifier cet utilisateur">Modifier ce collaborateur</a>
            <?php
                            }else{
                                echo '<em>Message...</em>';
                            }
                            ?>
        </td>
        <td class="d-none">
            <?php
                            if($liste['rh_inv'] == 'Non'){
                                ?>
            <a style="font-size: 13px; color: red" class="confirm" href="?action=del&username=<?= $liste['username'] ?>"
                title="Supprimer cet utilisateur">Supprimer cet utilisateur</a>
            <?php
                            }else{
                                echo '<em>Message...</em>';
                            }
                            ?>
        </td>
    </tr>
    <?php
                }
            ?>
</table>
<?php
    }
}
// le formulaire d'ajout d'utilisateur
if( isset($_GET['action']) && $_GET['action'] == 'ajout') {
    /*requete pour récupérer toutes les valeurs du champs fonction*/
    $reqfonction = execRequete("SELECT * FROM users");
    /*requete pour récupérer les infos du user dans la bdd externe*/
    $préremplir = execRequete("SELECT * FROM users_opco WHERE username = '$USER->username'");



    while($préremplir_champ = $préremplir->fetch()){
    ?>
<div class="container" style="margin-top: 55px !important;">
    <form action="" method="post" enctype="multipart/form-data" class="my-3">
        <!-- New fields -->

        <div class="form-group form-check-inline">
            <label class="form-check-label" for="civilite">Civilité:</label><br>
            <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr" required>
            <label class="form-check-label" for="civilite_mr">M.</label>
            <input type="radio" class="form-check-input" name="civilite" value="Mme" id="civilite_mrs" required>
            <label class="form-check-label" for="civilite_mrs">Mme</label>
        </div>


        <div class="form-group col-md-12">
            <label for="username">E-mail</label>
            <input type="text" id="inText" name="username" class="form-control in"
                value="<?= $_POST['username'] ?? '' ?>" required>
        </div>
        <!-- <div class="form-group col-md-6">
                <label for="password">Mot de passe</label>
                <input readonly type="text" style="color: blue; font-weight: bold" name="password" class="form-control"
                    id="password" value="">
            </div> -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" class="form-control"
                    value="<?= $_POST['firstname'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" class="form-control" value="<?= $_POST['lastname'] ?>">
            </div>
            <input type="hidden" id="nom_etab" value="<?= $USER->profile['siret_etablissement'] ?>">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Confirmation d'e-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>"
                    required>
            </div>

            <div class="form-group col-md-6">
                <label for="fonction">Fonction</label>
                <select name="fonction" id="fonction" class="form-control" required>
                    <option value="0" selected>Sélectionnez une fonction</option>
                    <option value="Start-uper (porteur de projet)">Start-uper (porteur de projet)</option>
                    <option value="Créateur d'entreprise (hors Startup et Startuper)">Créateur d'entreprise (hors
                        Startup et
                        Startuper)</option>
                    <option value="Microentreprise / TPE (<10)">Microentreprise / TPE (Moins 10)< /option>
                    <option value="PME/PMI (11-249)">PME/PMI (11-249)</option>
                    <option value="ETI (250 - 4999)">ETI (250 - 4999)</option>
                    <option value="Cadre dirigeant">Cadre dirigeant</option>
                    <option value="Cadre commercial / export / marketing">Cadre commercial / export / marketing</option>
                    <option value="Cadre innovation / R&D">Cadre innovation / R&D</option>
                    <option value="Responsable PI">Responsable PI</option>
                    <option value="Avocat-e">Avocat-e
                    </option>
                    <option value="CPI">CPI</option>
                    <option value="Autre mandataire PI">Autre mandataire PI</option>
                    <option value="Juriste PI">Juriste PI</option>
                    <option value="Avocat-e formaliste">Avocat-e formaliste</option>
                    <option value="Notaire formaliste">Notaire formaliste</option>
                    <option value="Autre mandataire formaliste">Autre mandataire formaliste</option>
                    <option value="Ingénieur / responsable technique">Ingénieur / responsable technique</option>
                    <option value="Ingénieur / responsable technique">Ingénieur / responsable technique</option>
                    <option value="Assistant-e">Assistant-e</option>
                    <option value="Consultant-e">Consultant-e</option>
                    <option value="En recherche d'emploi">En recherche d'emploi</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nom_etab">Nom établissement</label>
                <?php
                        if(!empty($préremplir_champ['nom_etab'])){
                            $nometab = $préremplir_champ['nom_etab'];
                            $listeetab = array($nometab);
                            foreach($listeetab as $value){
                                $a = explode(',',$value);
                                ?>
                <select name="nom_etab" id="nom_etab" class="form-control">
                    <?php
                                    for($i=0; $i<=count($a); $i++)
                                    {
                                        $ab = $a[$i];
                                        echo "<option value=\"$ab\">$ab</option>";
                                    }
                                    ?>
                </select>
                <?php
                            }
                        }else{
                            ?>
                <input type="text" style="color:red" name="nom_etab" class="form-control"
                    value="<?php echo 'Champs dans bdd vide !!!'; ?>">
                <?php
                        }
                    ?>
            </div>
            <div class="form-group col-md-6">
                <label for="telephone">Téléphone du stagiaire
                </label>
                <input type="text" name="telephone" class="form-control" value="" placeholder="+33 x xx xx xx xx">
            </div>
            <div class="form-group col-md-6 invisible">
                <label for="siren">Numéro siren</label>
                <?php
                // numéro de siren
                //if(is_siteadmin()){
                    $numsiren = $préremplir_champ['siren'];
                    $listeetab = array($numsiren);
                    foreach($listeetab as $value){
                        $a = explode(',',$value);
                            $ab = $a[0];
                            ?>
                <input readonly type="text" name="siren" id="siren" class="form-control" value="<?= $ab ?>">
                <?php
                    }
                ?>
            </div>
            <!--script pour créér un mot de passe à partir du prenom, du nom et du numéro de siret-->
            <script>
            <?php
                    $kl = 123456;
                    ?>
            var kl = 123456;
            $("#firstname").keyup(function() {
                var e = $("#password");
                var b = $("#firstname").val().substring(0, 3);
                var c = $("#lastname").val().substring(0, 3);
                var d = $("#siren").val().substring(0, 2);
                e.val(b + c + d).change();
            })
            $("#lastname").keyup(function() {
                var e = $("#password");
                var b = $("#firstname").val().substring(0, 3);
                var c = $("#lastname").val().substring(0, 3);
                var d = $("#siren").val().substring(0, 2);
                e.val(b + c + d).change();
            })
            $("#siren").keyup(function() {
            $("#siren").keyup(function() {
                var e = $("#password");
                var b = $("#firstname").val().substring(0, 3);
                var c = $("#lastname").val().substring(0, 3);
                var d = $("#siren").val().substring(0, 2);
                e.val(b + c + d).change();
            })
            </script>
        </div>
        <div class="form-row d-none">
            <div class="form-group col-md-6">
                <label for="region">Région</label>
                <!-- region -->
                <input class="form-control" type="text" name="region" readonly
                    value="<? echo $préremplir_champ['region'];?>" />
            </div>
            <div class="form-group col-md-6" style="visibility:hidden;">
                <label for="segmentation">Ségmentation</label>
                <!-- segmentation -->
                <input readonly type="text" name="segmentation" class="form-control"
                    value="<?= $préremplir_champ['segmentation'] ?>">
            </div>
        </div>
        <div class="form-row d-none">
            <div class="form-group col-md-6">
                <label for="branche_app">Branche applicable</label>
                <!-- branche applicable -->
                <input readonly type="text" name="branche_app" class="form-control"
                    value="<?= $préremplir_champ['branche_app'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="num_adherent">Numéro d'adhérent</label>
                <!-- numero d'adherent -->
                <input readonly type="text" name="num_adherent" class="form-control"
                    value="<?= $préremplir_champ['num_adherent'] ?>">
            </div>
        </div>
        <!-- des champs input et non des checkbox-->
        <div class="form-check">
            <input type="hidden" id="rh_inv" name="rh_inv" class="form-check-input"
                value="<?= $préremplir_champ['rh_inv'] ?>">
        </div>
        <!-- <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rh_delegue" name="rh_delegue" class="form-control"
                value="<?= $préremplir_champ['rh_delegue'] ?>" />
            <label class="form-check-label" for="rh_delgue">Gestionnaire Formation</label>
        </div> -->
        <input type="submit" class="btn btn-primary" style="float: right; background-color: #fd4c39; border: none"
            value="Crée le Compte">
    </form>
</div>
<?php
    }
}
// le formulaire de modif d'utilisateur sur icone de modif
if( isset($_GET['action']) && $_GET['action'] == 'edit') :
    ?>
<div class="container">
    <form action="" method="post" enctype="multipart/form-data" class="my-3">
        <div class="form-row">
            <div class="form-group col-md-12">
                <!--<label for="username">Identifiant</label>-->
                <input type="hidden" name="username" class="form-control"
                    value="<?= $_POST['username'] ?? $utilisateur_courant['username'] ?? '' ?>">
            </div>
        </div>


        <div class="form-group form-check-inline">
            <label class="form-check-label" for="civilite">Civilité:</label><br>
            <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr" required  <?php echo ($utilisateur_courant['civilite'] === 'M.') ? 'checked' : ''; ?>>
            <label class="form-check-label" for="civilite_mr">M.</label>
            <input type="radio" class="form-check-input" name="civilite" value="Mme" id="civilite_mrs" required <?php echo ($utilisateur_courant['civilite'] === 'Mme') ? 'checked' : ''; ?>>
            <label class="form-check-label" for="civilite_mrs">Mme</label>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" class="form-control"
                    value="<?= $_POST['firstname'] ?? $utilisateur_courant['firstname'] ?? '' ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" class="form-control"
                    value="<?= $_POST['lastname'] ?? $utilisateur_courant['lastname'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group col-md-12">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control"
                value="<?= $_POST['email'] ?? $utilisateur_courant['email'] ?? '' ?>">
        </div>
        <!-- <div class="form-group col-md-6 d-none">
                <label for="password">Mot de passe</label>
                <input type="text" name="password" class="form-control"
                    value="<?= $_POST['password'] ?? $utilisateur_courant['password'] ?? '' ?>">
            </div> -->

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nom_etab">Nom établissement</label>
                <?php
                    // nom établissement
                    //if(is_siteadmin()){
                        $préremplirnometab = execRequete("SELECT * FROM users_opco WHERE username = '$USER->username'");
                        while($prérempliretabuser = $préremplirnometab->fetch()){
                        if(!empty($prérempliretabuser['nom_etab'])){
                            $nometabuser = $prérempliretabuser['nom_etab'];
                            $listeetabuser = array($nometabuser);
                            foreach($listeetabuser as $value){
                                $a = explode(',',$value);
                                ?>
                <select name="nom_etab" id="nom_etab" class="form-control">
                    <?php
                                    for($i=0; $i<=count($a); $i++)
                                    {
                                        $ab = $a[$i];
                                        echo "<option value=\"$ab\">$ab</option>";
                                    }
                                    ?>
                </select>
                <?php
                            }
                        }else{
                            ?>
                <input type="text" style="color:red" name="nom_etab" class="form-control"
                    value="<?php echo 'Champs dans bdd vide !!!'; ?>">
                <?php
                        }
                    }
                    ?>
            </div>

            <div class="form-group col-md-6">
                <label for="telephone">Téléphone du stagiaire
                </label>
                <input type="text" name="telephone" class="form-control" value="<?php echo ($utilisateur_courant['telephone']);?>" placeholder="+33 x xx xx xx xx">
            </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="fonction">Fonction</label>
                <?php
                        $option = array(
                            'Start-uper (porteur de projet)' => 'Start-uper (porteur de projet)',
                            'Créateur d\'entreprise (hors Startup et Startuper)' => 'Créateur d\'entreprise (hors Startup et Startuper)',
                            'Microentreprise / TPE (<10)' => 'Microentreprise / TPE (<10)',
                            'PME/PMI (11-249)' => 'PME/PMI (11-249)',
                            'ETI (250 - 4999)' => 'ETI (250 - 4999)',
                            'Cadre dirigeant' => 'Cadre dirigeant',
                            'Cadre commercial / export / marketing' => 'Cadre commercial / export / marketing',
                            'Cadre innovation / R&D' => 'Cadre innovation / R&D',
                            'Responsable PI' => 'Responsable PI',
                            'Avocat-e' => 'Avocat-e',
                            'CPI'=> 'CPI',
                            'Autre mandataire PI' => 'Autre mandataire PI',
                            'Juriste PI' => 'Juriste PI',
                            'Avocat-e formaliste' => 'Avocat-e formaliste',
                            'Notaire formaliste' => 'Notaire formaliste',
                            'Autre mandataire formaliste' => 'Autre mandataire formaliste',
                            'Ingénieur / responsable technique' => 'Ingénieur / responsable technique',
                            'Assistant-e' => 'Assistant-e',
                            'Consultant-e' => 'Consultant-e',
                            'En recherche d\'emploi' => 'En recherche d\'emploi',
                        );
                ?>
                <select name="fonction" id="fonction" class="form-control" required>
                    <?php
                        foreach ($option as $key => $value) {
                            $selected = "";
                            if ($key == $utilisateur_courant['fonction']) {
                                $selected = "selected";
                            }
                            echo "<option $selected value=\"$key\">$value</option>";
                        }
                        ?>
                </select>
            </div>
            <div class="form-group col-md-6 invisible">
                <label for="siren">Numéro siren</label>
                <?php
                // nom siren
                //if(is_siteadmin()){
                        $numsiren = $utilisateur_courant['siren'];
                        $listeetab = array($numsiren);
                        foreach($listeetab as $value){
                            $a = explode(',',$value);
                            $ab = $a[0];
                            ?>
                <input readonly type="text" name="siren" id="siren" class="form-control" value="<?= $ab ?>">
                <?php
                        }
                        ?>
            </div>
        </div>
        <div class="form-row d-none">
            <div class="form-group col-md-6">
                <label for="region">Région</label>
                <input class="form-control" type="text" name="region" readonly
                    value="<? echo $utilisateur_courant['region'];?>" />
            </div>
            <div class="form-group col-md-6">
                <label for="segmentation">Ségmentation</label>
                <input readonly type="text" name="segmentation" class="form-control"
                    value="<?php echo $utilisateur_courant['segmentation']; ?>">
            </div>
        </div>
        <div class="form-row d-none">
            <div class="form-group col-md-6">
                <label for="branche_app">Branche applicable</label>
                <input readonly type="text" name="branche_app" class="form-control"
                    value="<?= $_POST['branche_app'] ?? $utilisateur_courant['branche_app'] ?? '' ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="num_adherent">Numéro d'adhérent</label>
                <input readonly type="text" name="num_adherent" class="form-control"
                    value="<?= $_POST['num_adherent'] ?? $utilisateur_courant['num_adherent'] ?? '' ?>">
            </div>
        </div>
        <div class="form-check">
            <input type="hidden" id="rh_inv" name="rh_inv" class="form-check-input"
                value="<?= $préremplir_champ['rh_inv'] ?>">
        </div>
        <!-- <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rh_delegue" name="rh_delegue" class="form-control"
                value="<?= $préremplir_champ['rh_delegue'] ?>" />
            <label class="form-check-label" for="rh_delgue">Gestionnaire Formation</label>
        </div> -->
        <input type="submit" class="btn btn-primary" style="float: right; background-color: #fd4c39; border: none"
            value="Modifier et enregistrer">
    </form>
</div>
<?php
endif;
//formulaire pour user_unique
if(isset($_GET['action']) && $_GET['action'] == 'user_unique') {
    /*requete pour récupérer les infos du user dans la bdd externe*/
    $préremplir = execRequete("SELECT * FROM users_opco WHERE username = '$USER->username'");
    while($préremplir_champ = $préremplir->fetch()){
    ?>
<div class="container">
    <form action="" method="post" enctype="multipart/form-data" class="my-3">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Identifiant</label>
                <input type="text" name="username" class="form-control" value="<?php
                        $complement_uniqueUsername = execRequete2("SELECT * FROM mdlj0_user u
                        INNER JOIN mdlj0_user_info_data ui ON u.id=ui.userid
                        INNER JOIN mdlj0_user_info_field uf ON uf.id=ui.fieldid
                        WHERE username=:username",
                        array(
                            'username' => $_POST['username']
                            ));
                        $complement_unique_userUsername = $complement_uniqueUsername->fetch();
                        echo $complement_unique_userUsername['username']; ?>">
            </div>
            <!-- <div class="form-group col-md-6">
                <label for="password">Mot de passe</label>
                <input type="text" id="password" name="password" class="form-control" value="">
            </div> -->

            <?php
                    // $complement_uniqueUsername = execRequete2("SELECT * FROM mdlj0_user u
                    // INNER JOIN mdlj0_user_info_data ui ON u.id=ui.userid
                    // INNER JOIN mdlj0_user_info_field uf ON uf.id=ui.fieldid
                    // WHERE username=:username",
                    // array(
                    //     'username' => $_POST['username']
                    //     ));
                    // $complement_unique_userUsername = $complement_uniqueUsername->fetch();
                    // echo $complement_unique_userUsername['password'];
                    ?>

        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php
                        $complement_uniqueFirstname = execRequete2("SELECT * FROM mdlj0_user u
                        INNER JOIN mdlj0_user_info_data ui ON u.id=ui.userid
                        INNER JOIN mdlj0_user_info_field uf ON uf.id=ui.fieldid
                        WHERE username=:username",
                        array(
                            'username' => $_POST['username']
                            ));
                        $complement_unique_userFirstname = $complement_uniqueFirstname->fetch();
                        echo $complement_unique_userFirstname['firstname']; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" id="lastname" class="form-control" value="<?php
                        $complement_uniqueLastname = execRequete2("SELECT * FROM mdlj0_user u
                        INNER JOIN mdlj0_user_info_data ui ON u.id=ui.userid
                        INNER JOIN mdlj0_user_info_field uf ON uf.id=ui.fieldid
                        WHERE username=:username",
                        array(
                            'username' => $_POST['username']
                            ));
                        $complement_unique_userLastname = $complement_uniqueLastname->fetch();
                        echo $complement_unique_userLastname['lastname']; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php
                        $complement_uniqueEmail = execRequete2("SELECT * FROM mdlj0_user u
                        INNER JOIN mdlj0_user_info_data ui ON u.id=ui.userid
                        INNER JOIN mdlj0_user_info_field uf ON uf.id=ui.fieldid
                        WHERE username=:username",
                        array(
                            'username' => $_POST['username']
                            ));
                        $complement_unique_userEmail = $complement_uniqueEmail->fetch();
                        echo $complement_unique_userEmail['email']; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="fonction">Fonction</label>
                <select name="fonction" id="fonction" class="form-control">
                    <option value="medical">Médical</option>
                    <option value="paramedical">Paramédical et médico-technique</option>
                    <option value="soin">Soin</option>
                    <option value="educatif">Éducatif / Animation</option>
                    <option value="enseignement">Enseignement et formation</option>
                    <option value="insertion">Insertion</option>
                    <option value="social">Social</option>
                    <option value="travail">Travail protégé</option>
                    <option value="direction">Direction</option>
                    <option value="administration">Services administration et gestion</option>
                    <option value="moyens">Services moyens généraux / techniques</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nom_etab">Nom établissement</label>
                <?php
                    // nom établissement
                    //if(is_siteadmin()){
                        if(!empty($préremplir_champ['nom_etab'])){
                            $nometab = $préremplir_champ['nom_etab'];
                            $listeetab = array($nometab);
                            foreach($listeetab as $value){
                                $a = explode(',',$value);
                                ?>
                <select name="nom_etab" id="nom_etab" class="form-control">
                    <?php
                                    for($i=0; $i<=count($a); $i++)
                                    {
                                        ?>
                    <option value="<?= $a[$i] ?>"><?= $a[$i] ?></option>
                    <?php
                                    }
                                    ?>
                </select>
                <?php
                            }
                        }else{
                            ?>
                <input type="text" style="color:red" name="nom_etab" class="form-control"
                    value="<?php echo 'Champs dans bdd vide !!!'; ?>">
                <?php
                        }
                    ?>
            </div>
            <div class="form-group col-md-6">
                <label for="siren">Numéro siren</label>
                <?php
                // numéro de siren
                //if(is_siteadmin()){
                    $numsiren = $préremplir_champ['siren'];
                    $listeetab = array($numsiren);
                    foreach($listeetab as $value){
                        $a = explode(',',$value);
                            $ab = $a[0];
                            ?>
                <input readonly type="text" name="siren" id="siren" class="form-control" value="<?= $ab ?>">
                <?php
                    }
                ?>
                <!--script pour créér un mot de passe à partir du prenom, du nom et du numéro de siret-->
                <script>
                <?php
                    $kl = 123456;
                    ?>
                var kl = 123456;
                $("#firstname").keyup(function() {
                    var e = $("#password");
                    var b = $("#firstname").val().substring(0, 3);
                    var c = $("#lastname").val();
                    var d = $("#siren").val();
                    e.val(b + c + d).change();
                })
                $("#lastname").keyup(function() {
                    var e = $("#password");
                    var b = $("#firstname").val().substring(0, 3);
                    var c = $("#lastname").val().substring(0, 3);
                    var d = $("#siren").val().substring(0, 2);
                    e.val(b + c + d).change();
                })
                $("#siren").keyup(function() {
                    var e = $("#password");
                    var b = $("#firstname").val().substring(0, 3);
                    var c = $("#lastname").val().substring(0, 3);
                    var d = $("#siren").val().substring(0, 2);
                    e.val(b + c + d).change();
                })
                </script>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="region">Région</label>
                <!-- region -->
                <select name="region" class="form-control">
                    <?php
                    $fonction = simplexml_load_file('departements-region.xml');
                    foreach($fonction as $l => $m){
                        ?>
                    <option value="<?= $m->region_name ?>"><?= $m->region_name ?></option>
                    <?php
                    }
                        ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="segmentation">Ségmentation</label>
                <!-- segmentation -->
                <input readonly type="text" name="segmentation" class="form-control"
                    value="<?= $préremplir_champ['segmentation'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="branche_app">Branche applicable</label>
                <input readonly type="text" id="branche_app" name="branche_app" class="form-control"
                    value="<?= $préremplir_champ['branche_app'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="num_adherent">Numéro d'adhérent</label>
                <input readonly type="text" id="num_adherent" name="num_adherent" class="form-control"
                    value="<?= $préremplir_champ['num_adherent'] ?>">
            </div>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="hidden" name="rh_inv" value="Non" />
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="rh_delgue" value="1" />
            <label class="form-check-label" for="rh_delgue">
                Gestionnaire Formation
            </label>
        </div>
        <!----------->
        <!--<a style="float: right; background-color: #fd4c39; border: none" href="#modal-opco-opened"-->
        <!--    class="btn btn-primary link-1" id="modal-opco-closed">suivant</a>-->
        <!--<div class="modal-opco-container" id="modal-opco-opened">-->
        <!--    <div class="modal-opco">-->
        <!--        <a href="#modal-colative-closed" class="link-2"></a>-->
        <!--        <div style="padding: 15px; width:100%; height: auto; background: white">-->
        <!--            <div style="text-align: center">-->
        <!--                <div>-->
        <!--                    <p style="color: #fd4c39"><b>Cet apprenant est connu dans le système, voulez-vous l’associer-->
        <!--                            à votre Siret établissement ?-->
        <!--                        </b></p><br>-->
        <!--                    <span style="display: block; color: #3f2881; padding-bottom: 13px"><b>Oui</b>, je confirme-->
        <!--                        l’associer à mon Siret établissement</span>-->
        <!--                </div>-->
        <!--                <div>-->
        <!--                    <input style="background-color: #3f2881; border: none" type="submit" name="forcing_upload"-->
        <!--                        class="btn btn-primary" value="Assigner à mon Siret établissement">-->
        <!--                </div><br>-->
        <!--                <div>-->
        <!--                    <span style="display: block; color: #75287c; padding-bottom: 13px"><b>Non</b>, il s’agit-->
        <!--                        d’un tout nouveau collaborateur </span>-->
        <!--                    <a href="?action=ajout" style="background-color: #75287c; border: none"-->
        <!--                        class="btn btn-primary">créér un nouveau collaborateur</a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!------------->

        <!------ajouter à 08-30-2023----->

        <a style="float: right; background-color: #fd4c39; border: none" href="#modal-opco-opened"
            class="btn btn-primary link-1" id="modal-opco-closed">suivant</a>
        <div class="modal-opco-container" id="modal-opco-opened">
            <div class="modal-opco">
                <a href="#modal-colative-closed" class="link-2"></a>
                <?php
                $username_to_add = $_POST['username'];
                $user_to_add = execRequete("SELECT * FROM users_opco WHERE username = '$username_to_add'")->fetch();


                if ($user_to_add['rh_inv'] === "Oui") {
                    $msg_if_rh_inv_is_yes = "Ce compte a été créé à partir de la connexion avec les Systèmes d'information de l'OPCO Santé et ne peut être modifié, adressez vous à votre interlocuteur OPCO pour qu'il le modifie";
                    echo ('
                <div style="display:flex;justify-content:center;align-items:center;padding: 15px; width:100%; height: 350px; background: white">
                <div style="text-align: center">
                <p>Ce compte a été créé à partir de la connexion avec les Systèmes d\'information de l\'OPCO Santé et ne peut être modifié, adressez vous à votre interlocuteur OPCO pour qu\'il le modifie.</p>
                <a class="btn btn-outline-primary" href="/admin/gestionuser/?action=ajout">Continuer</a>
                </div>
                </div>

                 ');

                }
                else {
                    echo ('
                    <div style="padding: 15px; width:100%; height: auto; background: white">
                    <div style="text-align: center">
                        <div>
                            <p style="color: #fd4c39"><b>Cet apprenant est connu dans le système, voulez-vous l’associer
                                    à votre Siret établissement ?
                                </b></p><br>
                            <span style="display: block; color: #3f2881; padding-bottom: 13px"><b>Oui</b>, je confirme
                                l’associer à mon Siret établissement</span>
                        </div>
                        <div>
                            <input style="background-color: #3f2881; border: none" type="submit" name="forcing_upload"
                                class="btn btn-primary" value="Assigner à mon Siret établissement">
                        </div><br>
                        <div>
                            <span style="display: block; color: #75287c; padding-bottom: 13px"><b>Non</b>, il s’agit
                                d’un tout nouveau collaborateur </span>
                            <a href="?action=ajout" style="background-color: #75287c; border: none"
                                class="btn btn-primary">créér un nouveau collaborateur</a>
                        </div>
                    </div>
                </div>
                    ');
				}
                ?>
            </div>
        </div>
        <!------------->


    </form>
</div>
<?php
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'mutate') {

    echo "<h3>Modifier l'établissement de rattachement des utilisateurs</h3>";
    $message = "";
    $currentuser = execRequete("SELECT * FROM users_opco WHERE email = '$USER->email'")->fetch();
    $number_of_etab = explode(',' , $currentuser['nom_etab']);
    if (isset($_POST['searchusersbtn'])) {
        $newsiret = $_POST['newsiret'];
    }
    ?>






<form method="post" action="" class="d-flex justify-content-center align-center">
    <input type="search" name="newsiret" placeholder="N° siret" class="form-control" required>
    <input type="submit" name="searchusersbtn" value="Search" class="btn btn-primary"
        style="background-color: #be226e; border:1px solid #be226e;">
</form>
<?php
if (isset($_POST['searchusersbtn'])) {
    $newsiret = $_POST['newsiret'];
    if ($newsiret != '' || $newsiret != null){
        $getusers = execRequete("SELECT * FROM users_opco WHERE nom_etab LIKE '%$newsiret%' AND rh_inv != 'Oui'");

        if($userstest = $getusers->fetch() != 0) {


?>

<form action="" method="post">
    <div class="container-fluid mt-5">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th colspan="2">Identifiant</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>E-mail</th>
                        <th>Nom établissement</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <?php
            while ($userstest = $getusers->fetch()) {
                ?>
                <tr>
                    <td class="text-center"><input type="checkbox" name="testcheck[]"
                            value="<? echo $userstest['username'] ?>"></td>
                    <td colspan="2">
                        <h6>
                            <? echo $userstest['username'] ;?>
                        </h6>
                    </td>

                    <td>
                        <? echo $userstest['firstname']; ?><br>
                    </td>
                    <td class="font-weight-bold">
                        <? echo $userstest['lastname']; ?>
                    </td>
                    <td>
                        <? echo $userstest['email'] ?>
                    </td>
                    <td>
                        <? echo $userstest['nom_etab'] ?>
                    </td>
                </tr>
                <?php

            }

?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center align-center">

        <div class="form-group w-100">
            <select class="form-control" name="updatedetab">
                <?php
for ($i=0; $i < count($number_of_etab) ; $i++) {
    // var_dump($number_of_etab[$i]);
    ?>
                <option value="<? echo $number_of_etab[$i]?> ">
                    <? echo $number_of_etab[$i]?>
                </option>
                <?
}
?>
            </select>
        </div>
        <input type="submit" name="updateuseretab" value="Muter vos utilisateurs" class="btn btn-primary"
            style="background: #be226e;border: 1px solid #be226e;">
    </div>
</form>
<?
}else{
    $message  =  "Aucune utilisateur trouvé avec ce N° siret";
}
}else {
    $message = "Veuillez renseigner ce champ";
}
}


if (isset($_POST['updateuseretab'])) {
    $userstopupdate = $_POST['testcheck'];
    $newetabname = $_POST['updatedetab'];
    for ($i=0; $i < count($userstopupdate); $i++) {
        $usersemailtochange = $userstopupdate[$i];
        $req = execRequete("UPDATE users_opco SET nom_etab = '$newetabname' WHERE email = '$usersemailtochange'");
        $message = "Vos collaborateurs ont été rattachés à leur nouvel établissement: $newetabname";
    }
}else {
    // $message = "l'établissement de rattachement n'a pas été modifié";
}


}
echo ($message != '' || $message != null ? "<h3 class='alert alert-warning'>$message</h3>" : '');




echo $OUTPUT->footer();