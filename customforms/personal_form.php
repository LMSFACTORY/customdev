<?php

/**
 * Custom form for moodle
 * This page is the form for the personal account. It allows the user to create a personal account.
 * @package    customforms
 */

// Include the Moodle Config file
require_once('../../config.php');
// Declare the global variables
global $OUTPUT, $PAGE, $CFG;

// Include the necessary files
require_once('./classes/User.php');

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

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
// Array for the information options
$info_options = [
	"Communication INPI",
	"Bouche à oreille",
	"Recherche internet",
	"Délégation INPI",
	"Service formation de mon entreprise"
];

// Get the email from the URL
$email = $_GET['email'];

// Check if the form is submitted
if (isset($_POST['register'])) {
	$err_data = '';
	// Create a new user object using the local class User in the /classes/User.php file.
	$user = new User();
	// Sanitize the POST data
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	// Create an array of the data
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
		'region' => $_POST['region']
	];

	// Call the register method from the User class
	$register_results = $user->register($data);

	// Check if the registration was successful. If it was, redirect the user to  /my page i.e. the dashboard page of the moodle.
	if ($register_results['success']) {
		$message = "<p class='mt-5'>Votre compte a bien été créé. Vous avez reçu un email avec vos identifiants de connexion. Merci de bien vouloir vous connecter pour valider votre compte. </p>";
		redirect($CFG->wwwroot . '/my', $message, 180);
	} else {
		// If the registration was not successful, display the errors to the user.
		// The errors are stored in the $register_results['errors'] array with the values entered by the user. This is done so that the user does not have to re-enter the values.
		$err_data = $register_results['errors'];
	}
}
?>
    <!-- HTML content for the personal form -->
    <div class="row mt-3">
        <div class="sidebar col-md-2">
			<?php require_once('./templates/sideprogress.php'); ?>
        </div>
        <div class="form-container container col-md-8 mb-2 mt-3">
            <div class="form_container_header">
                <h3 class="font-weight-bold">Créer votre compte : particulier</h3>
            </div>
            <div class="form_container_body">
                <form method="post" action="personal_form.php?email=<?php echo $email ?>">
                    <section class="section personal_info">
                        <div class="section_header">
                            <h4>Mes Informations personnelles</h4>
                        </div>
                        <div class="section_body">
                            <div class="form-group">
                                <label for="email">Courriel</label>
                                <input type="email" name="email"
                                       class="form-control  <?php echo (!empty($err_data['email_err'])) ? 'is-invalid' : ''; ?>"
                                       id="email" aria-describedby="emailHelp" value="<?php echo $email ?>"
                                       placeholder="Votre Email" readonly="readonly">
                                <span class="invalid-feedback"><?php echo $err_data['email_err']; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Profil
                                </label>
                                <select class="form-control" name="account_type" id="account_type" readonly="readonly">
                                    <option value="Particulier" selected>Particulier</option>
                                </select>
                            </div>
                            <div class="form-group form-check-inline">
                                <label class="form-check-label" for="civilite">Civilité
                                </label><br>
                                <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr"
                                       required <?php echo ($err_data['civilite'] == 'M.') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="civilite_mr">M.</label>
                                <input type="radio" class="form-check-input" name="civilite" value="Mme"
                                       id="civilite_mrs"
                                       required <?php echo ($err_data['civilite'] == 'Mme') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="civilite_mrs">Mme</label>
                                <span class="invalid-feedback"><?php echo $err_data['civilite_err']; ?></span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="firstname">Prénom
                                    </label>
                                    <input type="text" name="firstname"
                                           class="form-control <?php echo (!empty($err_data['firstname_err'])) ? 'is-invalid' : ''; ?>"
                                           id="firstname" aria-describedby="firstnameHelp" placeholder="Votre prénom"
                                           value="<?php echo $err_data['firstname'] ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['firstname_err']; ?></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">Nom
                                    </label>
                                    <input type="text" name="lastname"
                                           class="form-control <?php echo (!empty($err_data['lastname_err'])) ? 'is-invalid' : ''; ?>"
                                           id="lastname" aria-describedby="lastnameHelp"
                                           placeholder="Votre nom"
                                           value="<?php echo $err_data['lastname'] ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['lastname_err']; ?></span>
                                </div>
                            </div>
                            <!-- Telephone -->
                            <div class="form-group">
                                <label for="telephone">Votre numéro téléphone
                                </label>
                                <input type="text" name="telephone" class="form-control <?php echo (!empty
								($err_data['telephone_err'])) ? 'is-invalid' : ''; ?>" id="telephone"
                                       placeholder="Téléphone" value="<?php echo $err_data['telephone'] ?>">
                                <span class="invalid-feedback"><?php echo $err_data['telephone_err']; ?></span>
                            </div>
                        </div>
                    </section>
                    <section class="section address_structure">
                        <div class="section_header">
                            <h4>Adresse</h4>
                        </div>
                        <div class="section_body">
                            <!-- Address -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="address">Adresse
                                    </label>
                                    <input type="text" name="address"
                                           class="form-control main_address <?php echo (!empty($err_data['address_err'])) ? 'is-invalid' : ''; ?>"
                                           id="address" placeholder="Votre adresse"
                                           value="<?php echo $err_data['address'] ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['address_err']; ?></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">Complément d'adresse</label>
                                    <input type="text" name="address_comp"
                                           class="form-control main_address"
                                           id="address_comp" placeholder="Votre adresse complément">
                                </div>
                            </div>
                            <div class="form-row">
                                <!-- Postal Code -->
                                <div class="form-group col-md-4">
                                    <label for="postalcode">Code postal</label>
                                    <input type="text" name="postalcode"
                                           class="form-control main_address <?php echo (!empty($err_data['postalcode_err'])) ? 'is-invalid' : ''; ?>"
                                           id="postalcode" placeholder="Votre code postal"
                                           value="<?php echo $err_data['postalcode'] ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['postalcode_err']; ?></span>
                                </div>
                                <!-- City -->
                                <div class="form-group col-md-4">
                                    <label for="city">Ville</label>
                                    <input type="text" name="city"
                                           class="form-control  main_address <?php echo (!empty($err_data['city_err'])) ? 'is-invalid' : ''; ?>"
                                           id="city" placeholder="Ville"
                                           value="<?php echo $err_data['city'] ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['city_err']; ?></span>
                                </div>
                                <!-- Country -->
                                <div class="form-group col-md-4">
                                    <label for="country">Pays</label>
                                    <input type="text" name="country"
                                           class="form-control main_address <?php echo (!empty($err_data['country_err'])) ? 'is-invalid' : ''; ?>"
                                           id="city" placeholder="Pays"
                                           value="<?php echo ($err_data['country']) ? $err_data['country'] : 'France' ?>">
                                    <span class="invalid-feedback"><?php echo $err_data['country_err']; ?></span>
                                </div>
                            </div>
                            <!-- Region -->
                            <div class="form-group">
                                <label for="region">Région</label>
                                <select name="region" class="form-control <?php echo (!empty
								($err_data['region_err'])) ? 'is-invalid' : ''; ?>">
                                    <option value="0" selected>Sélectionnez votre région</option>
									<?php foreach ($regions as $region): ?>
                                        <option value="<?php echo $region ?>"
											<?php echo ($region == $err_data['region']) ? 'selected' : ''; ?>>
											<?php echo $region; ?></option>
									<?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $err_data['region_err']; ?></span>

                            </div>
                            <!-- Complete address -->
                        </div>
                    </section>
                    <section class="section marketing_section">
                        <div class="section_header">
                            <h4>
                                Informations complémentaires
                            </h4>
                        </div>
                        <div class="section_body">
                            <div class="form-group">
                                <label for="info_formation">Comment avez-vous connu nos formations ?</label>
                                <select class="form-control <?php echo (!empty($err_data['info_formation_err'])) ? 'is-invalid' : ''; ?>"
                                        name="info_formation"
                                        id="info_formation">
                                    <option value="0" selected>Sélectionnez une option</option>
									<?php foreach ($info_options as $option): ?>
                                        <option value="<?php echo $option ?>"
											<?php echo ($option == $err_data['info_formation']) ? 'selected' : ''; ?>>
											<?php echo $option; ?></option> <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $err_data['info_formation_err']; ?></span>
                            </div>
                            <div class="form-group form-check-inline">
                                <label class="form-check-label mr-3" for="offer_formation">Souhaitez-vous recevoir
                                    des communications de la part de l’INPI ?
                                </label>
                                <input type="radio" class="form-check-input" name="offer_formation" value="Oui"
                                       id="offer_formation" required <?php echo ($err_data['offer_formation'] == 'Oui')
									? 'checked' : ''; ?>>
                                <label class="form-check-label" for="offer_formation">Oui</label>
                                <input type="radio" class="form-check-input ml-3" name="offer_formation" value="Non"
                                       id="offer_formation"
                                       required <?php echo ($err_data['offer_formation'] == 'Non') ?
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
    </div>
<?php
// Include the necessary JS files
$PAGE->requires->js(new moodle_url("./js/sideprogress.js"), false);
echo $OUTPUT->footer();
