<?php

require_once __DIR__ . '/../../config.php';
require_once('./classes/Renderer.php');
require_once 'customValues.php';
require_once './classes/customProviders.php';

global $OUTPUT, $PAGE, $CFG, $USER;

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

if (!isloggedin() || isguestuser()) {
	redirect($CFG->wwwroot . '/login/index.php');
}

if ($USER->profile['basefournisseur'] !== 'Gérer les fournisseurs') {
	$message = "Vous n'avez pas les droits nécessaires pour accéder à cette page";
	redirect($CFG->wwwroot . '/my', $message, 3);
}

$templatePath = './templates/updateForm.php';

try {
	$renderer = new Renderer($templatePath);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}

$data = [
	'title' => 'Mise à jour du fournisseur',
	'email' => $_GET['email'],
	'countryCodes' => $customValues['countryCodes'],
	'functions' => $customValues['functions'],
	'domain_excellence' => $customValues['domain_excellence'],
	'region' => $customValues['region'],
	'user' => $USER,
];

$customProvider = new customProviders();
$provider = $customProvider->getProvider($_GET['email']);
$data['err_data'] = $customProvider->timestampToDate($provider);


if (isset($_POST['update_provider'])) {
	filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	$updatedProvider = $customProvider->updateProvider($_POST);

	if (isset($updatedProvider['success'])) {
		$data['success'] = $updatedProvider['success'];
		$email = $data['email'];

		redirect($CFG->wwwroot . "/customdev/customproviders/upload_documents.php?email={$email}");
	}

	$data['err_data'] = $updatedProvider['err_data'];
}

?>
<div class="row mt-3">
	<div class="sidebar col-md-3">
		<?php require_once('./templates/sideprogress.php'); ?>
	</div>
	<div class="col-md-8">
		<?php
		try {
			$renderer->loadTemplate($data, $templatePath);
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
		?>
	</div>
</div>


<?php

$PAGE->requires->js(new moodle_url("./js/sideprogress.js"), false);
$PAGE->requires->js(new moodle_url("./js/enableDatePick.js"), false);
$PAGE->requires->js(new moodle_url("./js/tags.js"), false);
echo $OUTPUT->footer();