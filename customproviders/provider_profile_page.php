<?php

require_once __DIR__ . '/../../config.php';
require_once('./classes/Renderer.php');
require_once 'customValues.php';
require_once './classes/customProviders.php';

global $OUTPUT, $PAGE, $CFG, $USER;

$arr_intervention = [
	'prediagnostic_PI' => 'Prédiagnostic PI',
	'pass_pi' => 'Pass PI',
	'coaching' => 'Coaching',
	'parrain' => 'Parrain / Marraine',
	'facile_collaboration' => 'Facilitation Collaborative Alliance PI',
	'formation_academie' => 'Formation Académie',
];

// Get the email from the url.
$email = $_GET['email'];
$userid = $_GET['userid'];

// Set up the page context and layout.
$PAGE->set_url("/customdev/customproviders/provider_profile_page.php?email=$email&userid=$userid");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->requires->css(new moodle_url('./css/styles.css'));

// Echo the header
echo $OUTPUT->header();

// Check the user logged in.
if (!isloggedin() || isguestuser()) {
	redirect($CFG->wwwroot . '/login/index.php');
}

//if ($USER->profile['basefournisseur'] !== 'Gérer les fournisseurs') {
//	$message = "Vous n'avez pas les droits nécessaires pour accéder à cette page";
//	redirect($CFG->wwwroot . '/my', $message, 3);
//}

// Instantiating the new class.
$customProviders = new customProviders();

// Get the provider from the email
$provider = (object) $customProviders->timestampToDate($customProviders->getProvider($email));

// Get the provider full name to display in the title
$providerFullname = $provider->firstname . ' ' . $provider->lastname;

// Define the user actions
$actions = [
	'can_view' => true,
	'can_manage' => false,
	'can_delete' => false,
];

// Check the user role and modify the actions accordingly.
if ($USER->profile['basefournisseur'] === 'Gérer les fournisseurs') {
	$actions['can_manage'] = true;
}

if ($USER->profile['roleinpi'] === 'Gestionnaire INPI') {
	$actions['can_delete'] = true;
}

// Render the page
try {
	$renderer = new Renderer('./templates/profilePage.php');
	// $renderer->render();
} catch (\Throwable $th) {
	echo $e->getMessage();
	exit;
}

// Adding the block to the profile page.
$block = $OUTPUT->blocks('side-pre');

// Prepare the data to be passed to the template
$data = [
	'title' => "Fournisseur : $providerFullname",
	'provider' => $provider,
	'arr_intervention' => $arr_intervention,
	'actions' => $actions,
	'userid' => $userid,
	'block' => $block,
];

// Load the template
echo $renderer->loadTemplate($data, './templates/profilePage.php');
$PAGE->requires->js(new moodle_url('./js/delete_provider.js'));


// Render the footer
echo $OUTPUT->footer();