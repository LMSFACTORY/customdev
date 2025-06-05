<?php
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
require('../../../config.php');
require_once('../inc/init.php');
echo $OUTPUT->header();
/**
 * Script d'import CSV.
 */
define('MYSQL_HOSTNAME', 'localhost');
define('MYSQL_USERNAME', 'elearningopco_2022ext');
define('MYSQL_PASSWORD', '~{6=q+E3m(D$');
define('MYSQL_DATABASE', 'elearningopco_pr202ext');

define('MYSQL_TABLE_USERS', 'users');
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

    $pdo = getMysqlConnection();
    $existing_user_array = [];
    $sql_for_existing_users = "SELECT * FROM " . MYSQL_TABLE_USERS;

    $stmt =  $pdo->query($sql_for_existing_users);

    debug("<span>Getting all existing users </br></span>");
    $counter = 0;

    while($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_user_array[] = $result;
    }

    debug("Number of users: " . count($existing_user_array));


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

    debug("new user counter : " . count($new_user_array));

    debug("All the users stocked in an array <br>");



    /**
 * @desc This function is to differentiate between two array i.e data from Table users and csvtemptable
 * It takes data from both tables in form of arrays and then by using this function we compare the two and find the values that are in the table users and not in the csv file
 * @param $array1 , $array2
 */


debug("Now differentiating between two tables <br>");
$diff_between_arrays = md_array_diff($existing_user_array,$new_user_array);

debug("array diff" . count($diff_between_arrays));
debug("Deleting all the users in the Existing users which are not in the CSV file <br>");
    for ($i=0; $i < count($diff_between_arrays) ; $i++) {
        $diff_array_email = $diff_between_arrays[$i]['email'];
        if ($diff_array_email != '' || $diff_array_email != null) {
            try {
                $sql_for_deleting_users = "DELETE FROM users WHERE email = '$diff_array_email' AND rh_inv= 'Oui'";
                $pdo->exec($sql_for_deleting_users);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
$update_counter = 0;
$registered_counter = 0;
    for ($i=0; $i < count($new_user_array) ; $i++) {
        try {
            $user_exist = in_array($new_user_array[$i]['email'], $existing_user_array[$i], $strict = true);
            if ($user_exist) {
                // We do UPDATE Except MDP AND Email.
                $upd_firstname = $new_user_array[$i]['firstname'];
                $upd_lastname = $new_user_array[$i]['lastname'];
                $upd_fonction = $new_user_array[$i]['fonction'];
                $upd_nom_etab = $new_user_array[$i]['nom_etab'];
                $upd_siren = $new_user_array[$i]['siren'];
                $upd_region = $new_user_array[$i]['region'];
                $upd_segmentation = $new_user_array[$i]['segmentation'];
                $upd_branche_app = $new_user_array[$i]['branche_app'];
                $upd_num_adherent = $new_user_array[$i]['num_adherent'];
                $upd_rh_delegue = $new_user_array[$i]['rh_delegue'];
                $upd_email = $new_user_array[$i]['email'];

                execRequete("UPDATE users SET firstname = '$upd_firstname', lastname = '$upd_lastname', fonction = '$upd_fonction', nom_etab = '$upd_nom_etab', siren = '$upd_siren', region = '$upd_region', segmentation = '$upd_segmentation', branche_app = '$upd_branceh_app', num_adherent = '$upd_num_adherent' , rh_delegue = '$upd_rh_delegue' WHERE email = '$upd_email' AND rh_inv= 'Oui'");

                // $sql_to_update_users = "UPDATE users SET firstname = '$upd_firstname', lastname = '$upd_lastname', fonction = '$upd_fonction', nom_etab = '$upd_nom_etab', siren = '$upd_siren', region = '$upd_region', segmentation = '$upd_segmentation', branche_app = '$upd_branceh_app', num_adherent = '$upd_num_adherent' , rh_delegue = '$upd_rh_delegue' WHERE email = '$upd_email' AND rh_inv= 'Oui'";
                // $query = $pdo->prepare($sql);
                // if($query->execute() !== True) {
                //     throw new Exception("An error occured while updating users in the database");
                // }
                // $query->execute();

                $update_counter++;


            }else if (!$user_exist) {
                // WE do a Replace
                execRequete("REPLACE INTO users VALUES (:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue)", $new_user_array[$i]);
                // $sql_to_replace_users = "REPLACE INTO users VALUES (:username,:password,:firstname,:lastname,:email,:fonction,:nom_etab,:siren,:region,:segmentation,:branche_app,:num_adherent,:rh_inv,:rh_delegue)";

                // $query_register = $pdo->prepare($sql);
                // if($query_register->execute($new_user_array[$i]) !== True) {
                //     throw new Exception("An error occured during the registration of users in the database");
                // }
                $registered_counter++;
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    debug($registered_counter++ . "<span> new users registered.</span>");
    debug($update_counter++ . "<span> users updated</span>");
    debug("Truncating Table csvtemptable");
    $pdo->exec("TRUNCATE TABLE csvdatatemptable");
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

      debug("Tentative de connexion à la base de donnée");

      $pdo = new PDO('mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ]);

      debug("Connexion réussie");
    }

    return $pdo;
  }



/**
 * @desc This function is to differentiate between two array i.e data from Table users and csvtemptable
 * It takes data from both tables in form of arrays and then by using this function we compare the two and find the values that are in the table users and not in the csv file
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



// function md_array_diff(array $array1, array $array2, array $_ = null) {
//     $diff = [];
//     $args = array_slice(func_get_args(), 1);

//     foreach ($array1 as $key => $value) {
//         foreach ($args as $item) {
//             if (is_array($item)) {
//                 if (array_key_exists($key, $item)) {
//                     if (is_array($value) && is_array($item[$key])) {
//                         $tmpDiff = md_array_diff($value, $item[$key]);

//                         if (!empty($tmpDiff)) {
//                             foreach ($tmpDiff as $tmpKey => $tmpValue) {
//                                 if (isset($item[$key][$tmpKey])) {
//                                     if (is_array($value[$tmpKey]) && is_array($item[$key][$tmpKey])) {
//                                         $newDiff = array_diff($value[$tmpKey], $item[$key][$tmpKey]);
//                                     } else if ($value[$tmpKey] !== $item[$key][$tmpKey]) {
//                                         $newDiff = $value[$tmpKey];
//                                     }

//                                     if (isset($newDiff)) {
//                                         $diff[$key][$tmpKey] = $newDiff;
//                                     }
//                                 } else {
//                                     $diff[$key][$tmpKey] = $tmpDiff;
//                                 }
//                             }
//                         }
//                     } else if ($value !== $item[$key]) {
//                         $diff[$key] = $value;

//                     }
//                 } else {
//                     $diff[$key] = $value;
//                 }
//             }
//         }
//     }

//     return $diff;
// }