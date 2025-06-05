<?php
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
require('../../../config.php');
require_once('../inc/init.php');
echo $OUTPUT->header();
/**
 * Script d'import CSV.
 */
define('MYSQL_HOSTNAME', 'localhost');
define('MYSQL_USERNAME', 'aixmarseille_inpiext');
define('MYSQL_PASSWORD', 'sK[A~(={~]?U');
define('MYSQL_DATABASE', 'aixmarseille_inpiext');

define('MYSQL_TABLE_USERS', 'users_opco');
define('MYSQL_TABLE_CSVTEMPTABLE', 'csvdatatemptable');
/**
 * Permet d'activer les logs pour comprendre les cas d'erreur.
 */
define('ENABLE_LOG', TRUE);
define('ERROR_REPORT_RECEIVER', 'mohed.a@lmsfactory.com');

// if (PHP_SAPI !== 'cli') {
//     die('Lancement uniquement en ligne de commande');
// }

debug('Démarrage du script : ' . __DIR__ . '/' . implode(' ', $argv) . "</br>");
// debug("<span></span>");


try {

    debug("<span>Comencer à recupere les utilisateur exixtant </br></span>");

    // $pdo = getMysqlConnection();
    createTableIfNotExists();
    $existing_user_array = [];
    $sql_for_existing_users = "SELECT * FROM " . MYSQL_TABLE_USERS;

    $stmt =  $pdo->query($sql_for_existing_users);

    debug("<span>Obtenir tous les utilisateurs existants.</br></span>");
    $counter = 0;

    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_user_array[] = $result;
    }

    debug("Nombre d'utilisateurs: " . count($existing_user_array) . "<br>");


    $sql_for_csvtemptable = "SELECT siren,siret,GROUP_CONCAT(nom_etab,' ',siret,' ',code_naf) AS nom_etablissement,branche,region,segmentation,nom,prenom,email FROM csvdatatemptable GROUP BY email";

    $requete = $pdo->query($sql_for_csvtemptable);

    while($champs = $requete->fetch(PDO::FETCH_ASSOC)){

        $new_email = $champs['email'];

        $new_prenom = $champs['prenom'];

        $new_nom = $champs['nom'];

        $new_nom_etab = $champs['nom_etablissement'];

        $new_siren = $champs['siren'];

        $new_siret = $champs['siret'];

        $new_region = $champs['region'];

        $new_segmentation = $champs['segmentation'];

        $new_branche = $champs['branche'];

        $mdp_prenom = strtolower(substr($new_prenom, 0, 3));

        $mdp_nom = strtolower(substr($new_nom, 0, 3));

        $mdp_siret = strtolower(substr($new_siret, 0, 2));

        $new_mdp = $mdp_prenom.$mdp_nom.$mdp_siret;

        $new_user_array[]  = array(
            'username' => strtolower($new_email),
            'password' => $new_mdp,
            'firstname' => ucfirst($new_prenom),
            'lastname' => strtoupper($new_nom),
            'email' => strtolower($new_email),
            'fonction' => $fonction,
            'nom_etab' => $new_nom_etab,
            'siren' => $new_siren,
            'region' => $new_region,
            'segmentation' => $new_segmentation,
            'branche_app' => $new_branche,
            'num_adherent' => $num_adherent,
            'rh_inv' => 'Oui',
            'rh_delegue' => $rh_delegue
        );

    }

    debug("All the users stocked in an array <br>");

    $update_counter = 0;
    $registered_counter = 0;

    // New array to stock all the users with the new field/property update
    $data_user_array = array();
    foreach ($new_user_array as $key => $value) {
        // Creating a new array for the user with the value to update the user or not.
        // $value['update'] will be used in the condition whether the user will be updated
        $data_user = array();
        $data_user['firstname'] = $value['firstname'];
        $data_user['lastname'] = $value['lastname'];
        $data_user['password'] = $value['password'];
        $data_user['fonction'] = $value['fonction'];
        $data_user['nom_etab'] = $value['nom_etab'];
        $data_user['siren'] = $value['siren'];
        $data_user['region'] = $value['region'];
        $data_user['segmentation'] = $value['segmentation'];
        $data_user['branche_app'] = $value['branche_app'];
        $data_user['num_adherent'] = $value['num_adherent'];
        $data_user['rh_delegue'] = $value['rh_delegue'];
        $data_user['email'] = $value['email'];
        $found = false;

        $email_user = $value['email'];
        for ($i=0; $i < count($existing_user_array) ; $i++) {

            if($email_user == $existing_user_array[$i]['email']) {
                $found = true;
                $data_user['update'] = true;
                $update_counter++;
            }
        }
        if ($found == false) {
            $data_user['update'] = false;
            $registered_counter++;
        }
        $data_user_array[] = $data_user;
    }

    foreach ($data_user_array as $key => $value) {
        if ($value['update'] == True) {
        $upd_firstname = $value['firstname'];
        $upd_lastname = $value['lastname'];
        $upd_fonction = $value['fonction'];
        $upd_nom_etab = $value['nom_etab'];
        $upd_siren = $value['siren'];
        $upd_region = $value['region'];
        $upd_segmentation = $value['segmentation'];
        $upd_branche_app = $value['branche_app'];
        $upd_num_adherent = $value['num_adherent'];
        $upd_rh_delegue = $value['rh_delegue'];
        $upd_email = $value['email'];
        execRequete("UPDATE users_opco SET firstname = '$upd_firstname', lastname = '$upd_lastname', fonction = '$upd_fonction', nom_etab = '$upd_nom_etab', siren = '$upd_siren', region = '$upd_region', segmentation = '$upd_segmentation', branche_app = '$upd_branche_app', num_adherent = '$upd_num_adherent', rh_inv= 'Oui' , rh_delegue = '$upd_rh_delegue' WHERE email = '$upd_email'");
    }
    else {

        $values_user  = array(
            'username' => $value['email'],
            'password' => $value['password'],
            'firstname' => ucfirst($value['firstname']),
            'lastname' => strtoupper($value['lastname']),
            'email' => strtolower($value['email']),
            'fonction' => $value['fonction'],
            'nom_etab' =>$value['nom_etab'],
            'siren' => $value['siren'],
            'region' => $value['region'],
            'segmentation' => $value['segmentation'],
            'branche_app' => $value['branche_app'],
            'num_adherent' => $value['num_adherent'],
            'rh_inv' => 'Oui',
            'rh_delegue' => $value['rh_delegue']
        );

        execRequete("INSERT INTO users_opco VALUES (:username ,:password,:firstname,:lastname ,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue)" , $values_user);
    }
    }

    debug($update_counter . "<span> utilisateurs mis à jour.</span><br>");
    debug($registered_counter . "<span> nouveaux utilisateurs enregistrés.</span><br>");

    // debug("Suppression du tableau csvtemptable");
    // $pdo->exec("DROP TABLE csvdatatemptable");
    // echo('<a style="display: block;width: 150px;background: red;height: 50px;color: white;line-height: 3.7;margin: auto;text-decoration: none;text-align: center" href="/">Continuer</a>');
    debug('<a style="display: block;width: 150px;background: black;height: 50px;color: white;line-height: 3.7;margin: auto;text-decoration: none;text-align: center" href="/admin/gestionuser/deleteusers/delete_users.php">Passer vers l\'êtap suivant</a>');
    exit(0);
} catch(Exception $e) {
    if (ERROR_REPORT_RECEIVER !== NULL) {
        $buffer = debug(NULL);
        array_unshift($buffer, "Log du traitement : ", "");
        $buffer[] = "";
        $buffer[] = "Message d'erreur :";
        $buffer[] = $e->getMessage();

        send_mail(ERROR_REPORT_RECEIVER, "ERREUR PENDANT L'EXECUTION DE SCRIPT IMPORTATION", implode("\r\n", $buffer));
    }
    $pdo->exec("DROP TABLE csvdatatemptable");
    debug("L'erreur suivante est survenue : {$e->getMessage()} ");
    debug("L'execution du script ne peux pas continuer");
    exit(1);
}

function debug($text) {

    static $buffer = [];

    if ($text !== NULL) {

      $line = '[' . date('c') . "]: $text";
      $buffer[] = $line;
    }

    if (!ENABLE_LOG) {
      return $buffer;
    }

    if ($text !== NULL) {
      echo $line . PHP_EOL;
    }

    return $buffer;
}

function getMysqlConnection() {


    static $pdo = NULL;

    if ($pdo === NULL) {

      debug("Tentative de connexion à la base de donnée <br>");

      $pdo = new PDO('mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ]);

      debug("Connexion réussie <br>");
    }

    return $pdo;
  }

/**
 * @desc This function is to differentiate between two array i.e data from Table users and csvtemptable
 * It takes data from both tables in form of arrays and then by using this function we compare the two and find the values that are in the table users and not in the csv file
 * Remember that it will only return the different values from the first array passed to it
 * @param $array1 , $array2
 */

function md_array_diff(array $array1, array $array2, array $_ = null) {
    $diff = [];
    $args = array_slice(func_get_args(), 1);

    foreach ($array1 as $key => $value) {
        foreach ($args as $item) {
            if (is_array($item)) {
                if (array_key_exists($key, $item)) {
                    if (is_array($value) && is_array($item[$key])) {
                        $tmpDiff = md_array_diff($value, $item[$key]);

                        if (!empty($tmpDiff)) {
                            foreach ($tmpDiff as $tmpKey => $tmpValue) {
                                if (isset($item[$key][$tmpKey])) {
                                    if (is_array($value[$tmpKey]) && is_array($item[$key][$tmpKey])) {
                                        $newDiff = array_diff($value[$tmpKey], $item[$key][$tmpKey]);
                                    } else if ($value[$tmpKey] !== $item[$key][$tmpKey]) {
                                        $newDiff = $value[$tmpKey];
                                    }

                                    if (isset($newDiff)) {
                                        $diff[$key][$tmpKey] = $newDiff;
                                    }
                                } else {
                                    $diff[$key][$tmpKey] = $tmpDiff;
                                }
                            }
                        }
                    } else if ($value !== $item[$key]) {
                        $diff[$key] = $value;

                    }
                } else {
                    $diff[$key] = $value;
                }
            }
        }
    }

    return $diff;
}


function createTableIfNotExists() {

    $pdo = getMysqlConnection();

    debug("On créer la table '" . MYSQL_TABLE_USERS . "' si elle n'existe pas encore <br>");

    $sql = "CREATE TABLE IF NOT EXISTS " . MYSQL_TABLE_USERS . " (
      username VARCHAR(255),
      passsword VARCHAR(255),
      firstname VARCHAR(255),
      lastname VARCHAR(255),
      email VARCHAR(255),
      function VARCHAR(255),
      etab_name TEXT,
      siren TEXT,
      region VARCHAR(255),
      segmentation VARCHAR(255),
      branch_app VARCHAR(255),
      num_adherent varchar(255),
      rh_inv VARCHAR(255),
      rh_delegate VARCHAR(255)
    ) COLLATE utf8_general_ci";

    $pdo->exec($sql);
  }