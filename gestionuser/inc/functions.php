<?php

function execRequete($req, $params = array() ){
    // Sanitize / Assainissement
    if ( !empty($params) ){
        foreach($params as $indice => $valeur){
            $params[$indice] = htmlspecialchars(trim($valeur),ENT_NOQUOTES);
        }
    }

    global $pdo; // globalisation de la variable $pdo pour y avoir droit dans cet espace local

    $r = $pdo->prepare($req);
    $r->execute($params);
    return $r;
}

//pour pdo2

function execRequete2($req2, $params2 = array() ){
    // Sanitize / Assainissement
    if ( !empty($params2) ){
        foreach($params2 as $indice2 => $valeur2){
            $params2[$indice2] = htmlspecialchars(trim($valeur2),ENT_NOQUOTES);
        }

    }


    global $pdo2; // globalisation de la variable $pdo pour y avoir droit dans cet espace local

    $r2 = $pdo2->prepare($req2);

    $r2->execute($params2);
    return $r2;

}

function send_mail($receiver, $subject, $body) {

    $body = wordwrap($body, 70, "\r\n");

    return mail($receiver, $subject, $body);

}