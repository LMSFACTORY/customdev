<?php
/**
 * Custom form for moodle
 * This page is the choice page of the custom form. It allows the user to choose between creating a professional or individual account.
 * @package    customforms
 */
require_once('../../config.php');

global $OUTPUT, $PAGE;

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();
$email =  htmlspecialchars($_GET['email'] ?? '', ENT_QUOTES);
?>
    <!-- HTML content for the choice page -->
    <div class="row mt-3">
        <div class="sidebar col-md-3">
			<?php require_once('./templates/sideprogress.php');?>
        </div>
        <div class="choice_container col-md-8 mt-3">
            <div class="choice_container_header">
                <h3 class="fw-semibold">Sélectionner votre profil</h3>
            </div>
            <div class="row choice_container_body">
                <div class="col-md-5 m-2 choice" id="professional">
                    <div class="choice-content">
                        <h4 class="fw-semibold">Profil Professionnel</h4>
                        <div class="choice_desc">
                            <p>
                                Je crée un profil en tant que professionnel en qualité de représentant de ma structure.
                            </p>
                            <p>
                                Avec ce profil, je peux inscrire des collaborateurs de ma structure aux formations de l’Académie INPI et suivre les étapes de leur formation.
                            </p>
                        </div>
                    </div>
                    <div class="choice_btn_container">
                        <a href="professional_form.php?email=<?php echo $email?>&type=professional"
                           class="choice_btn">
                            Créer un compte professionnel
                        </a>
                    </div>
                </div>
                <div class="col-md-5 m-2 choice" id="personal">
                    <div class="choice-content">
                        <h4 class="fw-semibold">Particulier</h4>
                        <div class="choice_desc">
                            <p>
                                Je crée un profil en tant que particulier (étudiants, demandeurs d’emploi).
                            </p>
                            <p>
                                Avec ce profil, je m’inscris aux formations de l’Académie INPI <b>à titre individuel.</b>
                            </p>
                            <p>
                                À noter : si vous êtes collaborateur d’une structure, veuillez contacter votre service RH/ formation pour procéder à l’inscription.
                            </p>
                        </div>
                    </div>
                    <div class="choice_btn_container">
                        <a href="personal_form.php?email=<?php echo $email?>&type=individual" class="choice_btn">
                            Créer un compte particulier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

$PAGE->requires->js(new moodle_url('./js/sideprogress.js'), false);
echo $OUTPUT->footer();
