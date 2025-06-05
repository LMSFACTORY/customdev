<?php

defined('MOODLE_INTERNAL') || die();
require_once $CFG->libdir.'/formslib.php'; 

/**
 * formulaire pour déposer les fichiers csv uniquement
 */
class admin_uploadcsv_form1 extends moodleform {
    function definition () {
        $mform = $this->_form;
        $mform->addElement('header', 'settingsheader', 'Déposer un fichier csv');
        //$url = new moodle_url('exemple_de_fichier.csv'); 
        //$link = html_writer::link($url, 'exemple_de_fichier.csv');
        //$mform->addElement('static', 'exemple de fichier csv', 'exemple de fichier csv', $link);
        //$mform->addHelpButton('exemple de fichier csv', 'exemple de fichier csv', 'Télécharger un exemple de fichier');
        $mform->addElement('filepicker', 'userfile', get_string('file'), null,
        array('maxbytes' => $maxbytes, 'accepted_types' => '.csv'));
        $mform->addRule('userfile', null, 'required');
        $mform->addElement('submit', 'submitbutton', 'Déposer ce fichier dans le répertoire ftp');
    }
}
