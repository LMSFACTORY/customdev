<?php

/**
 * Custom form for moodle
 * This page is the entry point for the custom form. It checks if the user is logged in or not and redirects to the appropriate page.
 * @package    customdev_customforms
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include the moodle config file
require_once('../../config.php');
// Include the necessary classes from the customforms Classes
require_once('./classes/Renderer.php');

define(MOODLE_INTERNAL, true) || die();

// Include the necessary moodle classes
global $OUTPUT, $PAGE, $CFG, $USER;

// Include the necessary css file from with the customforms directory.
$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

if (!isloggedin() || isguestuser()) {
	redirect($CFG->wwwroot . '/login/index.php');
}

if($USER->profile['basefournisseur'] !== 'Gérer les fournisseurs') {
	$message = "Vous n'avez pas les droits nécessaires pour accéder à cette page";
	redirect($CFG->wwwroot . '/my', $message, 3);
}

// template path for the checkemail.php file
$templatePath = './templates/checkemail.php';
// rendering the template using the local renderer class that we created in the classes directory
$renderer = new Renderer($templatePath);
$data = '';
$output = $renderer->render($data);
// rendering the sidebar and the main content
?>
	<div class="row mt-3">
		<div class="sidebar col-md-3">
			<?php require_once('./templates/sideprogress.php'); ?>
		</div>
		<?php echo $output; ?>
	</div>

<?php
$PAGE->requires->js(new moodle_url("./js/sideprogress.js"), false);
echo $OUTPUT->footer();

// Include the js file which will handle ajax request for the email check if there is any user with the email.
$PAGE->requires->js(new moodle_url('./js/app.js'), false);
