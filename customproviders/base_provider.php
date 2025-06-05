<?php
require_once __DIR__ . '/../../config.php';
require_once './classes/Renderer.php';
require_once './classes/customProviders.php';
require_once 'customValues.php';
require_once '../../mod/assign/renderer.php';

global $OUTPUT, $PAGE, $CFG, $USER;

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

if (!isloggedin() || isguestuser()) {
	redirect($CFG->wwwroot . '/login/index.php');
}

if (
	($USER->profile['basefournisseur'] !== 'Gérer les fournisseurs') &&
	($USER->profile['basefournisseur'] !== 'Consulter la base fournisseur')
) {
	$message = "Vous n'avez pas les droits nécessaires pour accéder à cette page";
	redirect($CFG->wwwroot . '/my', $message, 3);
}

// Define the user actions
$actions = [
	'can_view' => true,
	'can_manage' => false,
	'can_delete' => false,
];

if ($USER->profile['basefournisseur'] === 'Gérer les fournisseurs') {
	$actions['can_manage'] = true;
}

if ($USER->profile['roleinpi'] === 'Gestionnaire INPI') {
	$actions['can_delete'] = true;
}

$templatePath = './templates/manageProvider.php';

try {
	$renderer = new Renderer($templatePath);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}

// Custom Providers Class
$customProviders = new customProviders();

// Pagination Setup
// Retrieve the current page number (default to 1 if not present)
$page = optional_param('page', 1, PARAM_INT);

// Set the number of providers per page.
$limit = 10;

// Calculate the offset.
$offset = ($page - 1) * $limit;

// Retrieve Providers & Count
$all_filters = [];
if (isset($_GET['filter_reset'])) {
	$all_filters = [];
} else {
	// Sanitize and filter GET parameters, excluding 'page'
	$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$all_filters = array_filter($_GET ?? [], function ($value, $key) {
		return $key !== 'page' && $key !== 'filter_reset' && !empty($value);
	}, ARRAY_FILTER_USE_BOTH);
}

// Retrieve only a subset of providers based on filter, limit, and offset.
$all_providers = $customProviders->getAllProviders($all_filters, $limit, $offset);
//Count the total number of providers that match the filter.
$total_providers = $customProviders->countProviders($all_filters);

// Calculate the total number of pages.
$total_pages = ceil($total_providers / $limit);

$total_providers_count = $customProviders->getProvidersCount();

// Data to be passed to the template.
$data = [
	'title' => 'Base des fournisseurs',
	'providers' => $all_providers,
	'current_page' => $page,
	'total_pages' => $total_pages,
	'functions' => $customValues['functions'],
	'domain_excellence' => $customValues['domain_excellence'],
	'regions' => $customValues['region'],
	'all_filters' => $all_filters,
	'actions' => $actions,
	'providers_count' => $total_providers_count,
	'provider_filtered_count' => $total_providers,
	'is_filtered' => !empty($all_filters),
];

echo $renderer->loadTemplate($data, $templatePath);

$PAGE->requires->js(new moodle_url('./js/delete_provider.js'));
echo $OUTPUT->footer();