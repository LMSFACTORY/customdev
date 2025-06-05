<?php
require_once __DIR__ . '/../../config.php';
require_once('./classes/Renderer.php');
require_once './classes/customProviders.php';


global $OUTPUT, $PAGE, $CFG, $USER;

$PAGE->requires->css(new moodle_url('./css/styles.css'));
echo $OUTPUT->header();

echo '<h1>Information du fournisseur</h1>';
