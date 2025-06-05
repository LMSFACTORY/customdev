<?php

// Définition du fuseau horaire

date_default_timezone_set('Europe/Paris');

// Session
//session_start();


// Connexion à la BDD de la plateforme qu'on utilise

$pdo2 = new PDO(

	'mysql:host=inpi-prod-sql1;dbname=inpi-moodle-prod',

	'inpi-moodle-prod',

	'Vxz0hn5bJpHzxeLU',

	array(

		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,

		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',

		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

	)

);




// Connexion à la BDD externe de prod

$pdo = new PDO(

	'mysql:host=inpi-prod-sql1;dbname=inpi-moodle-prod-ext',

	'inpi-moodle-prod',

	'Vxz0hn5bJpHzxeLU',

	array(

		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,

		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',

		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

	)

);



require_once('functions.php');
