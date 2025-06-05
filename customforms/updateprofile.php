<?php

/**
 * Update profile page
 * This page is used to update the user profile information in the external database
 * @package    customforms
 */

require_once('../../config.php');
require_once('./classes/User.php');

global $OUTPUT, $PAGE, $USER;
$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

// Check if the user is connected
$isconnected = isloggedin();
if ($isconnected) {
	// Check if the user is an admin or a guest user or a user with an external database auth
	if (is_siteadmin() || !$USER->auth == 'db' || isguestuser()) {
		$message = '<p class="mt-5">Vous ne pouvez pas modifier votre profil sur ce lien</p>';
		redirect('/my', $message, '30');
	}
	// Check if the user is an external user
	$user = new User();

	// Get the user email that is currently connected
	$email = $USER->email;
	// Get the user information from the external database
	$current_user = $user->get_user($email);
	// Check if the user has the same address for the billing and the personal address
	if ($current_user->address == $current_user->address_facture) {
		$isSame = true;
	}

	// Check if the user has submitted the form
	if (isset($_POST['update'])) {
		// Sanitize the post data
		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		// Update the user information
		$update_user = $user->update($_POST);

		// Check if the user information has been updated successfully
		if ($update_user['success']) {
			$message = "<p class='mt-5'>Les modifications ont été prise en compte. La mise à jour de vos informations et celles de votre structure peuvent prendre quelques minutes, pour vous et vos collaborateurs.</p>";
			redirect(new moodle_url('/my'), $message);
		} else {
			// Get the errors if the user information has not been updated successfully and display them to the user
			// $update_user contains the errors and the user information to prefill the form.
			$err_data = $update_user['errors'];
		}
	}
}

// Array for all the region values makes it a lot easier to select
$regions = [
	'Auvergne-Rhône-Alpes',
	'Autre pays',
	'Bourgogne-Franche-Comté',
	'Bretagne',
	'Centre-Val de Loire',
	'Corse',
	'Grand Est',
	'Guadeloupe',
	'Guyane',
	'Hauts-de-France',
	'Île-de-France',
	'La Réunion',
	'Martinique',
	'Mayotte',
	'Normandie',
	'Nouvelle-Aquitaine',
	'Occitanie',
	'Pays de la Loire',
	'Provence-Alpes-Côte d\'Azur'
];
// Array for all the activity values makes it alot easier to select
$activities = [
	"Startup",
	"Micro entrepreneur (Auto entrepreneur)",
	"Microentreprise / TPE (inférieur à 10)",
	"PME/PMI (entre 11 et 249)",
	"ETI (entre 250 et 4999)",
	"Grande entreprise (supérieur à 5000)",
	"Grand groupe",
	"Indépendant / Profession libérale",
	"Établissement privé d enseignement supérieur (université, école, …)",
	"Enseignement secondaire (collège, lycée)",
	"Acteurs de l accompagnement des entreprises (consulaire, pôle de compétitivité, incubateur, SATT, …)",
	"Organisme de recherche publique",
	"Collectivités territoriales / structure intercommunales",
	"Fonction publique de l état (ministère, EPA nationaux, …)",
	"Fonction publique hospitalière",
	"Autre établissement public",
	"Cabinet d Avocats",
	"Cabinet de Conseil en PI",
	"Autre mandataire PI",
	"Mandataire formaliste (avocat, expert-comptable, notaire…)",
	"Association / Groupement (GFA GIE GAEC ….) / Syndicat"
];
// Array for all the function values makes it alot easier to select
$functions = [
	"Start-uper (porteur de projet)",
	"Créateur d'entreprise (hors Startup et Startuper)",
	"Créateur , Inventeur",
	"Enseignant / Chercheur",
	"Étudiant",
	"Cadre dirigeant",
	"Cadre commercial / export / marketing",
	"Cadre innovation / R&D",
	"Responsable PI",
	"Avocat-e",
	"CPI",
	"Autre mandataire PI",
	"Juriste PI",
	"Avocat-e formaliste",
	"Expert comptable formaliste",
	"Notaire formaliste",
	"Autre mandataire formaliste",
	"Ingénieur / responsable technique",
	"Assistant-e",
	"Consultant-e",
	"En recherche d'emploi"
];
?>
<?php

if ($isconnected) {
	?>
    <!-- HTML content for the update profile page -->
    <div class="form-container container col-md-8 mb-2 mt-5">
        <div class="form_container_header">
            <h3 class="font-weight-bold">Informations détaillées : Compte <?php echo $current_user->account_type ?></h3>
        </div>
        <div class="form_container_body">
            <form method="post" action="updateprofile.php">
                <section class="personal_info section">
                    <div class="section_header">
                        <h4>Mes Informations</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-group">
                            <label for="email">Courriel</label>
                            <input type="email" name="email"
                                   class="form-control  <?php echo (!empty($err_data['email_err'])) ? 'is-invalid' : ''; ?>"
                                   id="email" aria-describedby="emailHelp" value="<?php echo $USER->email ?>"
                                   placeholder="Votre Email" readonly="readonly">
                            <span class="invalid-feedback"><?php echo $err_data['email_err']; ?></span>
                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                        </div>
                        <!-- account type -->
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Profil</label>
                            <select class="form-control" name="account_type" id="account_type" readonly="readonly">
                                <option value="Professionnel" selected>Professionnel</option>
                            </select>
                        </div>

                        <div class="form-group form-check-inline">
                            <label class="form-check-label" for="civilite">Civilité
                            </label><br>
                            <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr"
                                   required
								<?php echo ($current_user->civilite == 'M.') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_mr">M.</label>
                            <input type="radio" class="form-check-input" name="civilite" value="Mme" id="civilite_mrs"
                                   required <?php echo ($current_user->civilite == 'Mme') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_mrs">Mme</label>
                            <span class="invalid-feedback"><?php echo $err_data['civilite_err']; ?></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstname">Prénom
                                </label>
                                <input type="text" name="firstname"
                                       class="form-control <?php echo (!empty($err_data['firstname_err'])) ? 'is-invalid' : ''; ?>"
                                       id="firstname" aria-describedby="firstnameHelp" placeholder="Votre Prénom"
                                       value="<?php echo ($err_data['isErrors']) ? $err_data['firstname']
										   : $current_user->firstname; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['firstname_err']; ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname">Nom de famille
                                </label>
                                <input type="text" name="lastname"
                                       class="form-control <?php echo (!empty($err_data['lastname_err'])) ? 'is-invalid' : ''; ?>"
                                       id="lastname" aria-describedby="lastnameHelp" placeholder="Votre Nom de famille"
                                       value="<?php echo ($err_data['isErrors']) ? $err_data['lastname']
										   : $current_user->lastname; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['lastname_err']; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="function">Fonction
                            </label>
                            <select class="form-control" name="function" id="function">
								<?php foreach ($functions as $value): ?>
                                    <option value="<?php echo htmlspecialchars($value); ?>"
										<?php
										echo ($value === $current_user->fonction) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($value); ?>
                                    </option>
								<?php endforeach; ?>
                                <span class="invalid-feedback"><?php echo $err_data['info_formation_err']; ?></span>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="section structure_section">
                    <div class="section_header">
                        <h4>Ma Structure</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-group">
                            <label for="raison">Raison sociale
                            </label>
                            <input type="text" name="raison"
                                   class="form-control <?php echo (!empty($err_data['raison_err'])) ? 'is-invalid' : ''; ?>"
                                   id="raison" placeholder="Raison sociale"
                                   value="<?php echo $current_user->raison; ?>"
                            >
                            <span class="invalid-feedback"><?php echo $err_data['raison_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="siret">Siret
                            </label>
                            <input type="text" name="siret"
                                   class="form-control <?php echo (!empty($err_data['siret_err'])) ? 'is-invalid' : ''; ?>"
                                   id="siret" placeholder="Numéro Siret"
                                   value="<?php echo $current_user->nom_etab; ?>"
                                   maxlength="14" readonly>
                            <span class="invalid-feedback"><?php echo $err_data['siret_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="activity">Activité
                            </label>
                            <select class="form-control" name="activity" id="activity">
								<?php foreach ($activities as $value): ?>
                                    <option value="<?php echo htmlspecialchars($value); ?>"
										<?php echo ($value === $current_user->activity) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($value); ?>
                                    </option>
								<?php endforeach; ?>
                                <span class="invalid-feedback"><?php echo $err_data['info_formation_err']; ?></span>
                            </select>
                        </div>
                        <!-- Telephone -->
                        <div class="form-group">
                            <label for="telephone">Téléphone
                            </label>
                            <input
                                    type="text"
                                    name="telephone"
                                    class="form-control <?php echo (!empty($err_data['telephone_err'])) ? 'is-invalid' : ''; ?>"
                                    id="telephone"
                                    placeholder="Votre numéro téléphone"
                                    value="<?php echo ($err_data['isErrors']) ? $err_data['telephone']
										: $current_user->telephone; ?>"
                            >
                            <span class="invalid-feedback"><?php echo $err_data['telephone_err']; ?></span>
                        </div>
                        <div class="form-row">
                            <!-- Adresse -->
                            <div class="form-group col-md-6">
                                <label for="address">Adresse Postale
                                </label>
                                <input type="text" name="address"
                                       class="form-control <?php echo (!empty($err_data['address_err'])) ? 'is-invalid' : ''; ?>"
                                       id="address" placeholder="Votre adresse"
                                       value="<?php echo ($err_data['isErrors']) ? $err_data['address'] :
										   $current_user->address; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['address_err']; ?></span>
                            </div>
                            <!-- Adresse complét -->
                            <div class="form-group col-md-6">
                                <label for="address">Complément d'adresse : </label>
                                <input type="text" name="address_comp"
                                       class="form-control <?php echo (!empty($err_data['address_comp_err'])) ? 'is-invalid' : ''; ?>"
                                       id="address_comp" placeholder="Votre adresse complément"
                                       value="<?php echo ($err_data['isErrors']) ? $err_data['address_comp'] :
										   $current_user->address_comp; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['address_comp_err']; ?></span>
                            </div>
                        </div>
                        <!-- Code postale -->
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="postalcode">Code Postal
                                </label>
                                <input type="text" name="postalcode"
                                       class="form-control <?php echo (!empty($err_data['postalcode_err'])) ? 'is-invalid' : ''; ?>"
                                       id="postalcode" placeholder="Votre code postal"
                                       value="<?php echo $current_user->postalcode; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['postalcode_err']; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="city">Ville
                                </label>
                                <input type="text" name="city"
                                       class="form-control <?php echo (!empty($err_data['city_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city" placeholder="Ville"
                                       value="<?php echo ($err_data['isErrors']) ? $err_data['city'] :
										   $current_user->city; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['city_err']; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="country">Pays
                                </label>
                                <input type="text" name="country"
                                       class="form-control <?php echo (!empty($err_data['country_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city" placeholder="Pays"
                                       value="<?php echo ($current_user->country) ? $current_user->country : 'France' ?>">
                                <span class="invalid-feedback"><?php echo $err_data['country_err']; ?></span>
                            </div>
                        </div>
                        <!-- Region -->
                        <div class="form-group">
                            <label for="region">Région
                            </label>
                            <select name="region" class="form-control">
								<?php foreach ($regions as $region): ?>
                                    <option value="<?php echo $region ?>"
										<?php echo ($region === $current_user->region) ? 'selected' : ''; ?>>
										<?php echo $region; ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </section>
                <section class="section structure_section">
                    <div class="section_header">
                        <h4>Information de Facturation</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remise" name="remise"
                                   maxlength="<?php echo ($current_user->conv_remise === 'Oui') ? 'checked' : ''; ?>"
                                   disabled>
                            <label class="form-check-label" for="remise">
                                Tarif préférentiel
                            </label>
                            <span>
                                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover"
                                   data-placement="right" data-content="<div class=&quot;no-overflow&quot;>
                                <p>Une réduction de 30 % après vérification par l’INPI peut être accordée :</p>
                                <p>-Sur les formations courtes pour les personnels des établissements publics et consulaires chargés de relayer l'information en matière de propriété industrielle, des établissements de recherche publique, des incubateurs, de la direction générale des entreprises, de Bpifrance, des structures de gouvernance des pôles de compétitivité,</p>
                                <p>-Sur justificatif à fournir : les enseignants, les étudiants et les demandeurs d'emploi,</p>
                                <p>-Pour les autres entités ainsi que pour les formations certifiantes, uniquement dans le cadre de partenariats et conventions validées par le Directeur général de l’INPI. »</p>
                                </div> " data-html="true" tabindex="0" data-trigger="focus" aria-label="Aide"
                                   data-original-title="" title="" id="yui_3_18_1_1_1728642733113_19">
                                <i class="icon fa fa-question-circle text-info fa-fw "
                                   title="Aide sur Votre navigateur doit prendre en charge les cookies" role="img"
                                   aria-label="Aide sur Votre navigateur doit prendre en charge les cookies"
                                   id="yui_3_18_1_1_1728642733113_18"></i>
                                </a>
                            </span>
                        </div>
                        <div class="form-check">
                            <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="toggleFields"
                                    name="toggleFields"
                                    aria-label="Toggle Fields"
								<?php echo ($isSame) ? 'checked' : ''; ?>
                            >
                            <label class="form-check-label" for="toggleFields">
                                L'adresse de facturation est identique à l'adresse personnelle.
                            </label>
                        </div>
                        <!-- Raison De facturation  -->
                        <div class="form-group">
                            <label for="raison_facture">Raison sociale</label>
                            <input type="text" name="raison_facture"
                                   class="form-control facture <?php echo (!empty($err_data['raison_facture_err'])) ?
									   'is-invalid' : ''; ?>"
                                   id="raison_facture" placeholder="Raison sociale de facturation"
                                   value="<?php echo ($err_data['isErrors']) ? $err_data['raison_facture'] :
									   $current_user->raison_facture
								   ?>">
                            <span class="invalid-feedback"><?php echo $err_data['raison_facture_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse postale</label>
                            <input type="text" name="address_facture"
                                   class="form-control facture <?php echo (!empty($err_data['address_facture_err'])) ? 'is-invalid' : ''; ?>"
                                   id="address_facture" placeholder="Votre adresse postale de facturation"
                                   value="<?php echo ($err_data['isErrors']) ? $err_data['address_facture'] :
									   $current_user->address_facture ?>">
                            <span class="invalid-feedback"><?php echo $err_data['address_facture_err']; ?></span>
                        </div>

                        <div class="form-row">
                            <!-- Code postale d'adresse de facuration  -->
                            <div class="form-group col-md-4">
                                <label for="postalcode">Code postal</label>
                                <input type="text" name="postalcode_facture"
                                       class="form-control facture <?php echo (!empty($err_data['postalcode_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="postalcode" placeholder="Votre code postal de facturation"
                                       value="<?php echo $current_user->postalcode_facture ?>">
                                <span class="invalid-feedback"><?php echo $err_data['postalcode_facture_err']; ?></span>
                            </div>
                            <!-- Ville d'adresse de facturation -->
                            <div class="form-group col-md-4">
                                <label for="city">Ville</label>
                                <input type="text" name="city_facture"
                                       class="form-control facture <?php echo (!empty($err_data['city_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city_facture" placeholder="Votre ville de facturation"
                                       value="<?php echo $current_user->city_facture ?>">
                                <span class="invalid-feedback"><?php echo $err_data['city_facture_err']; ?></span>
                            </div>

                            <!-- Pays de facturation -->
                            <div class="form-group col-md-4">
                                <label for="country">Pays</label>
                                <input type="text" name="country_facture"
                                       class="form-control facture <?php echo (!empty($err_data['country_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city_facture" placeholder="Votre pays de facturation"
                                       value="<?php echo ($current_user->country_facture) ? $current_user->country_facture : 'France' ?>">
                                <span class="invalid-feedback"><?php echo $err_data['country_facture_err']; ?></span>
                            </div>
                        </div>
                        <!-- Adresse complte de facturation -->
                        <div class="form-group">
                            <label for="address">Complément d'adresse</label>
                            <input type="text" name="address_comp_facture"
                                   class="form-control facture <?php echo (!empty($err_data['address_comp_facture_err'])) ? 'is-invalid' : ''; ?>"
                                   id="address_comp_facture" placeholder="Votre adresse complet de facturation"
                                   value="<?php echo $current_user->address_comp_facture ?>">
                            <span class="invalid-feedback"><?php echo $err_data['address_comp_facture_err']; ?></span>
                        </div>
                    </div>
                </section>

                <section class="section section_marketing">
                    <div class="section_header">
                        <h4>Informations complémentaires</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-group form-check-inline">
                            <label class="form-check-label mr-3" for="offer_formation">Souhaitez-vous recevoir des
                                informations
                                sur
                                notre offre
                                de
                                formation ?
                            </label>
                            <input type="radio" class="form-check-input" name="offer_formation" value="Oui"
                                   id="offer_formation" required
								<?php echo ($current_user->offer_formation == 'Oui') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="offer_formation">Oui</label>
                            <input type="radio" class="form-check-input ml-3" name="offer_formation" value="Non"
                                   id="offer_formation" required

								<?php echo ($current_user->offer_formation == 'Non') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="offer_formation">Non</label>
                            <span class="invalid-feedback"><?php echo $err_data['offer_formation_err']; ?></span>
                        </div>
                    </div>
                </section>
                <div class="button_container mt-3">
                    <button type="submit" name="update" class="btn btn-primary">Valider mes informations</button>
                </div>
            </form>

        </div>
    </div>

<?php } else {
	redirect('/login/index.php', 'Aucune compte connecté.');
}

// Include the javascript file for the toggle fields
$PAGE->requires->js(new moodle_url('./js/facturetoggle.js'), false);
echo $OUTPUT->footer();
