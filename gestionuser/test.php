<?php

equire('../../config.php');
global $CFG, $PAGE, $USER, $DB, $SITE, $_SESSION;

if(!isloggedin()){
	redirect($CFG->wwwroot);
}
require_once('inc/init.php');
$title = 'Gestion des utilisateurs';
if(!empty($_SESSION['attente'])){
	$contenu .= '<div class="alert alert-success mt-2">' . $_SESSION['attente'] . '</div>';
	unset($_SESSION['attente']);
}
// 6.Suppression d'un utilisateur
if(isset( $_GET['action']) && $_GET['action']=='del' && !empty($_GET['username']) ){
	execRequete("DELETE FROM users_opco WHERE username=:username",array('username' => $_GET['username']));
	$_SESSION['attente'] = 'L\' utilisateur a été supprimé';
	redirect('index.php');
}
// 7. Demande de modification
if( isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['username'])){
	$resultat = execRequete("SELECT * FROM users_opco WHERE username=:username",array('username' => $_GET['username']));
	if( $resultat->rowCount() > 0){
		$utilisateur_courant = $resultat->fetch();
	}
	$_GET['action'] = 'edit';
}
