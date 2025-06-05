<?php
/**
 * This file is used to create a form for the professional user
 * @package    customforms
 */

// Include the config file for moodle
require_once('../../config.php');
// Call the global variables from moodle
global $OUTPUT, $PAGE, $CFG;
// Include the User class that is local class created in the customforms/classes folder
require_once('./classes/User.php');

// Add the css file to the page
$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

// Check if user is logged in
if (isloggedin() && !isguestuser()) {
	$message = '<p class="mt-5">Vous êtes deja connecté. Veuillez vous déconnecter pour accéder à cette page<p>';
	redirect(new moodle_url('/my'), $message, 180);
}
$email = $_GET['email'];
// Array for all the function values makes it alot easier to select
$functions = [
	"Chargé-e de formation, chargé-e des RH",
	"Assistant-e formation, assistant-e RH",
	"Responsable ou Directeur RH",
	"Assistant-e",
	"Chef d entreprise",
	"CPI",
	"Avocat-e",
	"Mandataire formaliste (expert comptable, notaire …)"
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
	"Établissement privé d'enseignement supérieur (université, école, …)",
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
// Array for all the info options values makes it a lot easier to select
$info_options = [
	"Communication INPI",
	"Bouche à oreille",
	"Recherche internet",
	"Délégation INPI",
	"Service formation de mon entreprise",
	"Autre"
];

// declare the variable to check if the checkbox is checked or not
$isChecked = true;

// Check if the form is submitted
if (isset($_POST['register'])) {
	$err_data = '';
	$user = new User();

	// Sanitize the post data
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	// Check if the checkbox is checked or not
	$isChecked = isset($_POST['toggleFields']) ? 'checked' : 'not_checked';

	// Check if the rgpd checkbox is checked or not
	$rgpd = isset($_POST['rgpd']) ? 'Accept' : 'Reject';

	// Check if the remise checkbox is checked or not
	$remise = isset($_POST['remise']) ? 'Oui' : 'Non';

	// Data array to store all the form data
	$data = [
		'civilite' => trim($_POST['civilite']),
		'firstname' => trim($_POST['firstname']),
		'lastname' => trim($_POST['lastname']),
		'email' => trim($_POST['email']),
		'password' => trim($_POST['password']),
		'confirm_password' => trim($_POST['confirm_pass']),
		'address' => trim($_POST['address']),
		'postalcode' => trim($_POST['postalcode']),
		'city' => trim($_POST['city']),
		'country' => trim($_POST['country']),
		'address_comp' => trim($_POST['address_comp']),
		'telephone' => trim($_POST['telephone']),
		'offer_formation' => trim($_POST['offer_formation']),
		'info_formation' => trim($_POST['info_formation']),
		'account_type' => trim($_POST['account_type']),
		'activity' => trim($_POST['activity']),
		'function' => trim($_POST['function']),
		'raison' => trim($_POST['raison']),
		'siret' => trim($_POST['siret']),
		'rgpd' => $rgpd,
		'remise' => $remise,
		'region' => trim($_POST['region']),
	];

	// Check if the checkbox is checked or not
	if ($isChecked === 'not_checked') {
		$address_facture = [
			'address_facture' => trim($_POST['address_facture']),
			'postalcode_facture' => trim($_POST['postalcode_facture']),
			'city_facture' => trim($_POST['city_facture']),
			'country_facture' => trim($_POST['country_facture']),
			'address_comp_facture' => trim($_POST['address_comp_facture']),
			'raison_facture' => trim($_POST['raison_facture'])
		];
		// merging two arrays as have to send to the database.
		$data = array_merge($data, $address_facture);
	} else {
		$data['is_checked'] = $isChecked;
	}

	// Call the register function from the User class
	$register_results = $user->register($data);

	// Check if the registration is successful or not
	if ($register_results['success']) {
		$message = "<p class='mt-5'>Votre compte a bien été créé. Vous avez reçu un email avec vos identifiants de connexion. Merci de bien vouloir vous connecter pour valider votre compte. </p>";
		redirect($CFG->wwwroot . '/my', $message, 180);
	} else {
		// If the registration is not successful then store the errors in the err_data variable and display the form again with the errors
		// This is done to keep the data in the form fields which were filled by the user to make it easier for the user to correct the errors.
		$err_data = $register_results['errors'];
		// Check if the checkbox is checked or not as it was not being stored in the err_data variable
		$isChecked = $err_data['is_checked'];
	}
}
?>
    <!-- HTML content for the professional form -->
    <div class="row mt-3">
        <div class="sidebar col-md-2">
			<?php require_once('./templates/sideprogress.php'); ?>
        </div>
        <div class="form-container container col-md-8 mb-2 mt-3">
            <div class="form_container_header">
                <h3 class="font-weight-bold">Créer votre compte : professionnel</h3>
            </div>
            <form method="post" action="professional_form.php?email=<?php echo $email ?>">
                <section class="personal_info section ">
                    <div class="section_header">
                        <h4>Mes Informations professionnelles</h4>
                    </div>
                    <div class="section_body">
                        <!-- email -->
                        <div class="form-group">
                            <label for="email">Courriel</label>
                            <input type="email" name="email"
                                   class="form-control  <?php echo (!empty($err_data['email_err'])) ? 'is-invalid' : ''; ?>"
                                   id="email" aria-describedby="emailHelp" value="<?php echo $email ?>"
                                   placeholder="Votre Email"
                                   readonly="readonly">
                            <span class="invalid-feedback"><?php echo $err_data['email_err']; ?></span>
                        </div>
                        <!-- account type -->
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Profil</label>
                            <select class="form-control" name="account_type" id="account_type" readonly="readonly">
                                <option value="Professionnel" selected>Professionnel</option>
                            </select>
                        </div>

                        <!-- civility -->
                        <div class="form-group form-check-inline">
                            <label class="form-check-label" for="civilite">
                                Civilité
                            </label><br>
                            <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr"
                                   required <?php echo ($err_data['civilite'] == 'M.') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_mr">M.</label>
                            <input type="radio" class="form-check-input" name="civilite" value="Mme" id="civilite_mrs"
                                   required <?php echo ($err_data['civilite'] == 'Mme') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_mrs">Mme</label>
                            <span class="invalid-feedback"><?php echo $err_data['civilite_err']; ?></span>
                        </div>

                        <div class="form-row">
                            <!-- firstname -->
                            <div class="form-group col-md-6">
                                <label for="firstname">Prénom
                                </label>
                                <input type="text" name="firstname"
                                       class="form-control <?php echo (!empty($err_data['firstname_err'])) ? 'is-invalid' : ''; ?>"
                                       id="firstname" aria-describedby="firstnameHelp" placeholder="Votre prénom"
                                       value="<?php echo $err_data['firstname'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['firstname_err']; ?></span>
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>
                            <!-- Lastname -->
                            <div class="form-group col-md-6">
                                <label for="lastname">Nom
                                </label>
                                <input type="text" name="lastname"
                                       class="form-control <?php echo (!empty($err_data['lastname_err'])) ? 'is-invalid' : ''; ?>"
                                       id="lastname" aria-describedby="lastnameHelp" placeholder="Votre nom"
                                       value="<?php echo $err_data['lastname'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['lastname_err']; ?></span>
                                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                            </div>
                        </div>
                        <!-- Function -->
                        <div class="form-group">
                            <label for="function">Fonction
                            </label>
                            <select class="form-control <?php echo (!empty($err_data['function_err'])) ? 'is-invalid' :
								''; ?>"
                                    name="function"
                                    id="function">
                                <option value="0" selected>Sélectionnez une fonction</option>
								<?php foreach ($functions as $value): ?>
                                    <option value="<?php echo $value; ?>"
										<?php echo ($value == $err_data['function']) ? 'selected' : ''; ?>
                                    >
										<?php echo $value; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $err_data['function_err']; ?></span>
                        </div>
                    </div>
                </section>
                <section class="section structure_section">
                    <div class="section_header">
                        <h4>Ma Structure</h4>
                    </div>
                    <div class="section_body">
                        <!-- Raison Sociale -->
                        <div class="form-group">
                            <label for="raison">Raison sociale
                            </label>
                            <input type="text" name="raison"
                                   class="form-control <?php echo (!empty($err_data['raison_err'])) ? 'is-invalid' : ''; ?>"
                                   id="raison" placeholder="Raison sociale" value="<?php echo($err_data['raison']);
							?>">
                            <span class="invalid-feedback"><?php echo $err_data['raison_err']; ?></span>
                        </div>
                        <!-- Siret -->
                        <div class="form-group">
                            <label for="siret">Siret
                            </label>
                            <input type="text" name="siret"
                                   class="form-control <?php echo (!empty($err_data['siret_err'])) ? 'is-invalid' : ''; ?>"
                                   id="siret" placeholder="Numéro siret" maxlength="14"
                                   value="<?php echo $err_data['siret'] ?>">
                            <span class="invalid-feedback"><?php echo $err_data['siret_err']; ?></span>
                        </div>
                        <!-- Activity -->
                        <div class="form-group">
                            <label for="activity">Activité
                            </label>
                            <select
                                    class="form-control <?php echo (!empty($err_data['activity_err'])) ? 'is-invalid' : ''; ?>"
                                    name="activity"
                                    id="activity">
                                <option value="0" <?php echo empty($err_data['activity']) ? "selected" : ""; ?>>
                                    Sélectionnez une activité
                                </option>
								<?php foreach ($activities as $value): ?>
                                    <option
                                            value="<?php echo $value; ?>"
										<?php echo ($value == $err_data['activity']) ? "selected"
											: ""; ?>
                                    >
										<?php echo $value; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $err_data['activity_err']; ?></span>
                        </div>
                        <!-- Telephone -->
                        <div class="form-group">
                            <label for="telephone">Téléphone
                            </label>
                            <input type="text" name="telephone"
                                   class="form-control <?php echo (!empty($err_data['telephone_err'])) ? 'is-invalid' : ''; ?>"
                                   id="telephone"
                                   placeholder="Téléphone" value="<?php echo $err_data['telephone']; ?>">
                            <span class="invalid-feedback"><?php echo $err_data['telephone_err']; ?></span>
                        </div>
                        <!-- Adresse -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="address">Adresse
                                </label>
                                <input type="text" name="address"
                                       class="form-control <?php echo (!empty($err_data['address_err'])) ? 'is-invalid' : ''; ?>"
                                       id="address" placeholder="Votre adresse"
                                       value="<?php echo $err_data['address'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['address_err']; ?></span>
                            </div>
                            <!-- Address Complement -->
                            <div class="form-group col-md-6">
                                <label for="address">Complément d'adresse (optionnel)</label>
                                <input type="text" name="address_comp"
                                       class="form-control"
                                       id="address_comp" placeholder="Votre adresse complément">
                            </div>
                        </div>
                        <!-- Code postale -->
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="postalcode">Code postal
                                </label>
                                <input type="text" name="postalcode"
                                       class="form-control <?php echo (!empty($err_data['postalcode_err'])) ? 'is-invalid' : ''; ?>"
                                       id="postalcode" placeholder="Code postal"
                                       value="<?php echo $err_data['postalcode'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['postalcode_err']; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="city">Ville
                                </label>
                                <input type="text" name="city"
                                       class="form-control <?php echo (!empty($err_data['city_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city" placeholder="Ville" value="<?php echo $err_data['city'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['city_err']; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="country">Pays
                                </label>
                                <input type="text" name="country"
                                       class="form-control <?php echo (!empty($err_data['country_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city" placeholder="Pays"
                                       value="<?php echo ($err_data['country']) ? $err_data['country'] : 'France' ?>">
                                <span class="invalid-feedback"><?php echo $err_data['country_err']; ?></span>
                            </div>
                        </div>
                        <!-- Region -->
                        <div class="form-group">
                            <label for="region">Région
                            </label>
                            <select name="region"
                                    class="form-control <?php echo (!empty($err_data['region_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="0" selected>Sélectionnez votre région</option>
								<?php foreach ($regions as $region): ?>
                                    <option value="<?php echo $region ?>"
										<?php echo ($region == $err_data['region']) ? 'selected' : ''; ?>>
										<?php echo $region; ?></option>
								<?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $err_data['region_err']; ?></span>
                        </div>
                    </div>
                </section>
                <!-- Facturation Section-->
                <section class="section structure_section">
                    <div class="section_header">
                        <h4>Informations de facturation</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-check">
                            <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remise"
                                    name="remise"
								<?php echo ($err_data['remise'] == 'Oui') ? 'checked' : ''; ?>
                            >
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
								<?php echo ($isChecked == 'checked') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="toggleFields">
                                L'adresse de facturation est identique à l'adresse personnelle.
                            </label>
                        </div>
                        <!-- Raison De facturation  -->
                        <div class="form-group">
                            <label for="raison_facture">Raison sociale</label>
                            <input type="text" name="raison_facture"
                                   class="form-control facture <?php echo (!empty($err_data['raison_facture_err'])) ? 'is-invalid' : ''; ?>"
                                   id="raison_facture" placeholder="Raison sociale"
                                   value="<?php echo $err_data['raison_facture']; ?>">
                            <span class="invalid-feedback"><?php echo $err_data['raison_facture_err']; ?></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="address">Adresse</label>
                                <input type="text" name="address_facture"
                                       class="form-control facture <?php echo (!empty($err_data['address_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="address_facture" placeholder="Votre adresse"
                                       value="<?php echo $err_data['address_facture']; ?>">
                                <span class="invalid-feedback"><?php echo $err_data['address_facture_err']; ?></span>
                            </div>
                            <!-- Adresse complét de facturation -->
                            <div class="form-group col-md-6">
                                <label for="address">Complément d'adresse (optionnel)</label>
                                <input type="text" name="address_comp_facture"
                                       class="form-control facture <?php echo (!empty($err_data['address_comp_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="address_comp_facture"
                                       placeholder="Votre adresse complément"
                                       value="<?php echo $err_data['address_comp_facture']; ?>
                                ">
                                <span class="invalid-feedback"><?php echo $err_data['address_comp_facture_err']; ?></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- Code postal d'adresse de facuration  -->
                            <div class="form-group col-md-4">
                                <label for="postalcode">Code postal</label>
                                <input type="text" name="postalcode_facture"
                                       class="form-control facture <?php echo (!empty($err_data['postalcode_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="postalcode" placeholder="Code postal"
                                       value="<?php echo $err_data['postalcode_facture'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['postalcode_facture_err']; ?></span>
                            </div>
                            <!-- Ville d'adresse de facturation -->
                            <div class="form-group col-md-4">
                                <label for="city">Ville</label>
                                <input type="text" name="city_facture"
                                       class="form-control facture <?php echo (!empty($err_data['city_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city_facture" placeholder="Ville"
                                       value="<?php echo $err_data['city_facture'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['city_facture_err']; ?></span>
                            </div>
                            <!-- Pays de facturation -->
                            <div class="form-group col-md-4">
                                <label for="country">Pays</label>
                                <input type="text" name="country_facture"
                                       class="form-control facture <?php echo (!empty($err_data['country_facture_err'])) ? 'is-invalid' : ''; ?>"
                                       id="city_facture" placeholder="Pays"
                                       value="<?php echo ($err_data['country']) ? $err_data['country'] : 'France' ?>">
                                <span class="invalid-feedback"><?php echo $err_data['country_facture_err']; ?></span>
                            </div>
                        </div>

                    </div>
                </section>
                <section class="section section_marketing">
                    <div class="section_header">
                        <h4>Informations complémentaires</h4>
                    </div>
                    <div class="section_body">
                        <div class="form-group">
                            <label for="info_formation">Comment avez-vous connu nos formations ? (optionnel)
                            </label>
                            <select class="form-control  <?php echo (!empty($err_data['info_formation_err'])) ? 'is-invalid' :
								''; ?>" name="info_formation" id="info_formation">
                                <option value="0" selected="">Sélectionnez une option</option>
								<?php foreach ($info_options as $value): ?>
                                    <option value="<?php echo $value; ?>"
										<?php echo ($value == $err_data['info_formation']) ? 'selected' : ''; ?>
                                    >
										<?php echo $value; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $err_data['info_formation_err']; ?></span>
                        </div>
                        <div class="form-group form-check-inline">
                            <label class="form-check-label mr-3" for="offer_formation">
                                Souhaitez-vous recevoir des communications de la part de l’INPI ?
                            </label>
                            <input type="radio" class="form-check-input" name="offer_formation" value="Oui"
                                   id="offer_formation" required <?php echo ($err_data['offer_formation'] == 'Oui') ?
								'checked' : ''; ?>>
                            <label class="form-check-label" for="offer_formation">Oui</label>
                            <input type="radio" class="form-check-input ml-3" name="offer_formation" value="Non"
                                   id="offer_formation" required <?php echo ($err_data['offer_formation'] == 'Non') ?
								'checked' : ''; ?>>
                            <label class="form-check-label" for="offer_formation">Non</label>
                            <span class="invalid-feedback"><?php echo $err_data['offer_formation_err']; ?></span>
                        </div>
                    </div>
                </section>
                <div class="alert alert-info mt-3">
                    <p><b>Note :</b> Votre mot de passe vous sera envoyé par courriel après la validation des
                        informations.</p>
                </div>
                <div class="button_container mt-3">
                    <button type="submit" name="register" class="btn btn-primary">Valider mes informations</button>
                </div>
            </form>
        </div>
    </div>

<?php
// Add the js files to the page
$PAGE->requires->js(new moodle_url('./js/facturetoggle.js'), false);
$PAGE->requires->js(new moodle_url('./js/sideprogress.js'), false);
echo $OUTPUT->footer();
