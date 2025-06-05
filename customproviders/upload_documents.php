<?php
require_once __DIR__ . '/../../config.php';
require_once('./classes/Renderer.php');
require_once 'customValues.php';
require_once './classes/customProviders.php';

global $OUTPUT, $PAGE, $CFG, $USER, $DB;

if (!isloggedin() || isguestuser()) {
	redirect($CFG->wwwroot . '/login/index.php');
}

if ($USER->profile['basefournisseur'] !== 'Gérer les fournisseurs') {
	$message = "Vous n'avez pas les droits nécessaires pour accéder à cette page";
	redirect($CFG->wwwroot . '/my', $message, 3);
}

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

$templatePath = './templates/uploadForm.php';
try {
	$renderer = new Renderer($templatePath);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}

$data['title'] = 'Pièces administratives';
$data['email'] = $_GET['email'];

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
$PAGE->requires->js(new moodle_url("./js/linksGen.js"), false);

echo $OUTPUT->footer();
