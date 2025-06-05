<?php

require('../../../config.php');

require_once($CFG->dirroot.'/'.$CFG->admin.'/gestionuser/uploadcsvfile/uploadfile_form.php');

echo '<link href="style.css" rel="stylesheet" type="text/css" media="all" />';

    $mform1 = new admin_uploadcsv_form1();

    if ($formdata = $mform1->get_data()) {



        $filename = $mform1->get_new_filename('userfile');

        $filepath = '../../../deposecsv/';

        $filexist = $filepath.$filename;



        if (!file_exists($filexist)) {

            $mform1->save_file('userfile', $filepath .$filename, false);

            redirect($CFG->wwwroot.'/admin/gestionuser/uploadcsvfile/success_form.php', 'Votre fichier csv a été déposé dans le dossier deposecv avec succès', null, \core\output\notification::NOTIFY_SUCCESS);

        }else{

            $confirm = optional_param('confirm', false, PARAM_BOOL);

            if (!$confirm or !confirm_sesskey()) {

                echo $OUTPUT->header();

                echo $OUTPUT->heading('<span style="display: block; color: #be226e; font-size: 19px; text-align: center">Le fichier : <em> '.$filename.'</em> ne peut pas être déposé car un même nom de fichier exite déjà dans le repertoire</span>');

                echo $OUTPUT->confirm('<span style="color:#75287c">Cliquez sur <b>Continuer</b> pour recommencer ou sur <b>Annuler</b> pour retourner à l\'accueil</span>',

                    new moodle_url($PAGE->url, array('confirm' => 1)),

                    new moodle_url($CFG->wwwroot));

                echo $OUTPUT->footer();

                die();

            }

        }



    } else {

        echo $OUTPUT->header();

        // echo $OUTPUT->heading_with_help('<span style="color:#3e2881">Formulaire de transfert de fichier.csv vers ftp</span>', 'dépot de fichier vers ftp');

        $mform1->display();

        echo $OUTPUT->footer();

        die;

    }



die;